<?php

namespace App\Services;

use App\Models\BackupSetting;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ThirdCategory;
use App\Models\Brand;
use App\Models\Variation;
use App\Models\VariationOption;
use App\Models\VariationCombination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class GoogleSheetSyncService
{
    private ?string $spreadsheetId = null;
    private ?string $sheetTab = 'Sheet1';
    private ?string $serviceAccountEmail = null;
    private ?string $privateKey = null;
    private ?string $cachedAccessToken = null;

    public function __construct()
    {
        $this->spreadsheetId = BackupSetting::get('google_sheet_spreadsheet_id');
        $this->sheetTab = BackupSetting::get('google_sheet_tab_name', 'Sheet1') ?: 'Sheet1';
        $this->serviceAccountEmail = BackupSetting::get('google_sheet_client_email');
        $this->privateKey = BackupSetting::get('google_sheet_private_key');
    }

    public function setSpreadsheetId(string $id): self
    {
        $this->spreadsheetId = $id;
        return $this;
    }

    public function setSheetTab(string $tab): self
    {
        $this->sheetTab = $tab;
        return $this;
    }

    /**
     * Get or generate OAuth2 access token for Google Sheets API using Service Account JWT
     */
    public function getAccessToken(): string
    {
        if ($this->cachedAccessToken) {
            return $this->cachedAccessToken;
        }

        if (empty($this->serviceAccountEmail) || empty($this->privateKey)) {
            // Also check if json key payload is stored directly
            $jsonKey = BackupSetting::get('google_sheet_service_account_json');
            if (!empty($jsonKey)) {
                $decoded = json_decode($jsonKey, true);
                if (isset($decoded['client_email'], $decoded['private_key'])) {
                    $this->serviceAccountEmail = $decoded['client_email'];
                    $this->privateKey = $decoded['private_key'];
                }
            }
        }

        if (empty($this->serviceAccountEmail) || empty($this->privateKey)) {
            throw new Exception('Google Sheets Service Account credentials are not configured.');
        }

        // Format private key properly if newlines are escaped
        $privateKey = str_replace(['\n', "\\n"], "\n", $this->privateKey);

        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claim = [
            'iss' => $this->serviceAccountEmail,
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlClaim = $this->base64UrlEncode(json_encode($claim));
        $dataToSign = $base64UrlHeader . '.' . $base64UrlClaim;

        $signature = '';
        $binarySignature = '';
        $keyResource = openssl_pkey_get_private($privateKey);
        
        if (!$keyResource) {
            throw new Exception('Invalid Google Service Account private key provided: ' . openssl_error_string());
        }

        if (!openssl_sign($dataToSign, $binarySignature, $keyResource, OPENSSL_ALGO_SHA256)) {
            throw new Exception('Failed to sign JWT with private key.');
        }

        $base64UrlSignature = $this->base64UrlEncode($binarySignature);
        $jwt = $dataToSign . '.' . $base64UrlSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to obtain Google Sheets access token: ' . $response->body());
        }

        $data = $response->json();
        $this->cachedAccessToken = $data['access_token'] ?? null;

        if (!$this->cachedAccessToken) {
            throw new Exception('Google OAuth returned response without access token.');
        }

        return $this->cachedAccessToken;
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Test connection and retrieve sheet rows count
     */
    public function testConnection(): array
    {
        if (empty($this->spreadsheetId)) {
            throw new Exception('Spreadsheet ID is missing.');
        }

        $token = $this->getAccessToken();
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/" . urlencode($this->sheetTab . '!A1:Z2');

        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            throw new Exception('Google Sheets API error: ' . $response->body());
        }

        $data = $response->json();
        return [
            'success' => true,
            'message' => 'Successfully connected to Google Sheet.',
            'sample_range' => $data['range'] ?? '',
            'headers' => $data['values'][0] ?? [],
        ];
    }

    /**
     * Read all rows from the Google Sheet and sync/import products into software database
     */
    public function pullAndSyncFromSheet(): array
    {
        if (empty($this->spreadsheetId)) {
            throw new Exception('Spreadsheet ID is not configured.');
        }

        $token = $this->getAccessToken();
        $range = urlencode($this->sheetTab . '!A1:BA10000');
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$range}";

        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            throw new Exception('Failed to fetch rows from Google Sheets: ' . $response->body());
        }

        $rows = $response->json()['values'] ?? [];
        if (count($rows) <= 1) {
            return ['imported' => 0, 'updated' => 0, 'skipped' => 0, 'message' => 'No data rows found in sheet.'];
        }

        $headers = array_map('trim', $rows[0]);
        $headerMap = array_flip($headers);

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty($row)) continue;

            $getField = function(string $headerName, $default = '') use ($row, $headerMap) {
                if (isset($headerMap[$headerName]) && isset($row[$headerMap[$headerName]])) {
                    return trim($row[$headerMap[$headerName]]);
                }
                return $default;
            };

            $id = $getField('ID');
            $sku = $getField('SKU');
            $title = $getField('Product Name') ?: $getField('Title');
            $slug = $getField('Slug');
            $quantity = $getField('Stock Quantity');
            $cost = $getField('Product Cost (Tk)');
            $salePrice = $getField('Sale Price / Offer (Tk)');
            $regularPrice = $getField('Regular Price / Old Price (Tk)');
            $wholesalePrice = $getField('Wholesale Price (Tk)');
            $categoryName = $getField('Category');
            $status = strtolower($getField('Status', 'Active')) === 'active' ? 1 : 0;
            $mainImageUrl = $getField('Main Image URL');
            $shortDesc = $getField('Short Description');
            $desc = $getField('Detailed Description');
            $weight = $getField('Weight (KG)');
            $productType = strtolower($getField('Product Type', 'simple'));

            if (empty($title) && empty($id) && empty($sku)) {
                $skipped++;
                continue;
            }

            // Find existing product by ID or SKU
            $product = null;
            if (!empty($id) && is_numeric($id)) {
                $product = Product::find((int) $id);
            }
            if (!$product && !empty($sku)) {
                $product = Product::where('sku', $sku)->first();
            }

            if ($product) {
                // Update Existing Product Stock & Pricing
                if ($quantity !== '') {
                    $product->quantity = (int) $quantity;
                    $product->stock_status = (int) $quantity > 0 ? 'in_stock' : 'out_of_stock';
                }
                if ($cost !== '') {
                    $product->product_cost = (float) str_replace(',', '', $cost);
                }
                if ($salePrice !== '') {
                    $product->offer = (float) str_replace(',', '', $salePrice);
                }
                if ($regularPrice !== '') {
                    $product->old_price = (float) str_replace(',', '', $regularPrice);
                }
                if ($wholesalePrice !== '') {
                    $product->wholesale_price = (float) str_replace(',', '', $wholesalePrice);
                }
                if ($status !== null) {
                    $product->status = $status;
                }
                if (!empty($title) && $title !== $product->title) {
                    $product->title = $title;
                }

                $product->save();
                $updated++;
            } else {
                // Create New Product for this Admin / Store
                if (empty($title)) {
                    $skipped++;
                    continue;
                }

                $categoryId = 1;
                if (!empty($categoryName)) {
                    $cat = ProductCategory::firstOrCreate(
                        ['name' => $categoryName],
                        ['slug' => Str::slug($categoryName), 'status' => 1]
                    );
                    $categoryId = $cat->id;
                }

                $currentUserId = auth()->id() ?? 1;

                $newProduct = new Product();
                $newProduct->created_by = $currentUserId;
                $newProduct->title = $title;
                $newProduct->slug = !empty($slug) ? $slug : Str::slug($title) . '-' . uniqid();
                $newProduct->sku = !empty($sku) ? $sku : 'SKU-' . strtoupper(Str::random(6));
                $newProduct->category_id = $categoryId;
                $newProduct->product_type = $productType ?: 'simple';
                $newProduct->status = $status;
                $newProduct->quantity = $quantity !== '' ? (int) $quantity : 0;
                $newProduct->stock_status = ((int) $quantity > 0) ? 'in_stock' : 'out_of_stock';
                $newProduct->manage_stock = true;
                $newProduct->product_cost = $cost !== '' ? (float) str_replace(',', '', $cost) : null;
                $newProduct->offer = $salePrice !== '' ? (float) str_replace(',', '', $salePrice) : 0;
                $newProduct->old_price = $regularPrice !== '' ? (float) str_replace(',', '', $regularPrice) : null;
                $newProduct->wholesale_price = $wholesalePrice !== '' ? (float) str_replace(',', '', $wholesalePrice) : null;
                $newProduct->thumb_image = $mainImageUrl ?: 'uploads/product/default.png';
                $newProduct->short_description = $shortDesc ?: null;
                $newProduct->description = $desc ?: null;
                $newProduct->save();

                $imported++;
            }
        }

        return [
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'total_rows' => count($rows) - 1,
            'message' => "Sync complete: {$updated} updated, {$imported} imported, {$skipped} skipped."
        ];
    }

    /**
     * Push a product stock/price update to the Google Sheet
     */
    public function pushProductToSheet(Product $product): bool
    {
        if (empty($this->spreadsheetId)) {
            return false;
        }

        try {
            $token = $this->getAccessToken();
            $range = urlencode($this->sheetTab . '!A:W');
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$range}";

            $response = Http::withToken($token)->get($url);
            if ($response->failed()) {
                Log::error('Google Sheet fetch error during push: ' . $response->body());
                return false;
            }

            $rows = $response->json()['values'] ?? [];
            if (empty($rows)) {
                return false;
            }

            $headers = array_map('trim', $rows[0]);
            $headerMap = array_flip($headers);

            $idColIndex = $headerMap['ID'] ?? null;
            $stockColIndex = $headerMap['Stock Quantity'] ?? null;
            $salePriceColIndex = $headerMap['Sale Price / Offer (Tk)'] ?? null;
            $regularPriceColIndex = $headerMap['Regular Price / Old Price (Tk)'] ?? null;
            $costColIndex = $headerMap['Product Cost (Tk)'] ?? null;

            if ($idColIndex === null || $stockColIndex === null) {
                return false;
            }

            // Look for matching row
            $rowIndexToUpdate = null;
            for ($r = 1; $r < count($rows); $r++) {
                $row = $rows[$r];
                if (isset($row[$idColIndex]) && (int) $row[$idColIndex] === (int) $product->id) {
                    $rowIndexToUpdate = $r + 1; // 1-indexed for Sheet A1 notation
                    break;
                }
            }

            if ($rowIndexToUpdate) {
                // Update stock cell
                $stockColLetter = $this->columnNumberToLetter($stockColIndex + 1);
                $updateRange = urlencode("{$this->sheetTab}!{$stockColLetter}{$rowIndexToUpdate}");
                
                $updateUrl = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$updateRange}?valueInputOption=USER_ENTERED";
                Http::withToken($token)->put($updateUrl, [
                    'range' => "{$this->sheetTab}!{$stockColLetter}{$rowIndexToUpdate}",
                    'majorDimension' => 'ROWS',
                    'values' => [
                        [(string) $product->quantity]
                    ]
                ]);

                return true;
            } else {
                // Product doesn't exist on sheet; append it
                $appendRange = urlencode($this->sheetTab . '!A:BA');
                $appendUrl = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$appendRange}:append?valueInputOption=USER_ENTERED";
                
                $newRow = array_fill(0, count($headers), '');
                if (isset($headerMap['ID'])) $newRow[$headerMap['ID']] = $product->id;
                if (isset($headerMap['Product Name'])) $newRow[$headerMap['Product Name']] = $product->title;
                if (isset($headerMap['Slug'])) $newRow[$headerMap['Slug']] = $product->slug;
                if (isset($headerMap['SKU'])) $newRow[$headerMap['SKU']] = $product->sku;
                if (isset($headerMap['Status'])) $newRow[$headerMap['Status']] = $product->status ? 'Active' : 'Inactive';
                if (isset($headerMap['Stock Quantity'])) $newRow[$headerMap['Stock Quantity']] = $product->quantity;
                if (isset($headerMap['Sale Price / Offer (Tk)'])) $newRow[$headerMap['Sale Price / Offer (Tk)']] = $product->offer;
                if (isset($headerMap['Regular Price / Old Price (Tk)'])) $newRow[$headerMap['Regular Price / Old Price (Tk)']] = $product->old_price;
                if (isset($headerMap['Product Cost (Tk)'])) $newRow[$headerMap['Product Cost (Tk)']] = $product->product_cost;
                if (isset($headerMap['Main Image URL'])) $newRow[$headerMap['Main Image URL']] = asset($product->thumb_image);

                Http::withToken($token)->post($appendUrl, [
                    'values' => [$newRow]
                ]);

                return true;
            }
        } catch (Exception $e) {
            Log::error('Error pushing product to Google Sheet: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Push all software products and current stocks to Google Sheet in bulk
     */
    public function pushAllProductsToSheet(): array
    {
        if (empty($this->spreadsheetId)) {
            throw new Exception('Spreadsheet ID is not configured.');
        }

        $token = $this->getAccessToken();
        $range = urlencode($this->sheetTab . '!A1:W1');
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$range}";

        $response = Http::withToken($token)->get($url);
        if ($response->failed()) {
            throw new Exception('Failed to access Google Sheet: ' . $response->body());
        }

        $headers = [
            'ID', 'Product Image', 'Image Link', 'Product Name', 'Slug', 'SKU', 'Product Type', 'Status',
            'Is Featured', 'Category', 'Sub Category', 'Third Category', 'All Categories', 'Brand',
            'Product Cost (Tk)', 'Sale Price / Offer (Tk)', 'Regular Price / Old Price (Tk)', 'Discount Percentage (%)',
            'Profit Margin (%)', 'Wholesale Price (Tk)', 'Wholesale Pricing Tiers', 'Reseller Price (Tk)', 'Stock Quantity',
            'Weight (KG)', 'Pay Advance Delivery', 'Easy Return Period (Days)', 'Short Description', 'Detailed Description',
            'Tags', 'Main Image URL', 'Gallery Image URLs'
        ];

        $products = Product::query()
            ->forUser()
            ->with(['category', 'subCategory', 'brand'])
            ->get();
        $allRows = [];
        $allRows[] = $headers;

        // Helper function to sanitize and limit cell content length (Google Sheet limit is 50,000 chars)
        $cleanSheetCell = function(?string $text, int $limit = 5000): string {
            if (empty($text)) {
                return '';
            }
            // Strip base64 data URIs from rich text/description
            $cleaned = preg_replace('/data:image\/[^;]+;base64,[a-zA-Z0-9+\/+=]+/i', '', $text);
            $cleaned = preg_replace('/<img[^>]+src=["\']data:image\/[^;]+;base64,[^"\']+["\'][^>]*>/i', '', $cleaned);
            $cleaned = strip_tags($cleaned);
            $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleaned = trim(preg_replace("/\s+/", ' ', $cleaned));
            return mb_substr($cleaned, 0, $limit);
        };

        foreach ($products as $product) {
            $catName = $product->category ? $product->category->name : '';
            $subCatName = $product->subCategory ? $product->subCategory->name : '';
            $brandName = $product->brand ? $product->brand->name : '';
            $discountPct = ($product->old_price > 0 && $product->offer > 0 && $product->old_price > $product->offer) 
                ? round((($product->old_price - $product->offer) / $product->old_price) * 100, 2) . '%' 
                : '0%';

            $profitMargin = ($product->offer > 0 && $product->product_cost > 0)
                ? round((($product->offer - $product->product_cost) / $product->offer) * 100, 2) . '%'
                : '0%';

            $imageUrl = $product->thumb_image ? asset($product->thumb_image) : '';

            $row = [
                $product->id,
                '', // image formula placeholder
                'Open Photo',
                $cleanSheetCell($product->title, 255),
                $product->slug,
                $product->sku ?: '',
                $product->product_type ?: 'simple',
                $product->status ? 'Active' : 'Inactive',
                $product->is_featured ? 'Yes' : 'No',
                $catName,
                $subCatName,
                '',
                $catName,
                $brandName,
                $product->product_cost ?: 0,
                $product->offer ?: 0,
                $product->old_price ?: 0,
                $discountPct,
                $profitMargin,
                $product->wholesale_price ?: 0,
                '',
                '',
                $product->quantity ?: 0,
                $product->weight ?: 0,
                'No',
                0,
                $cleanSheetCell($product->short_description, 1000),
                $cleanSheetCell($product->description, 5000),
                $cleanSheetCell($product->tags, 255),
                $imageUrl,
                ''
            ];

            $allRows[] = $row;
        }

        // Overwrite full sheet with new data
        $writeRange = urlencode($this->sheetTab . '!A1:AE' . count($allRows));
        $writeUrl = "https://sheets.googleapis.com/v4/spreadsheets/{$this->spreadsheetId}/values/{$writeRange}?valueInputOption=USER_ENTERED";

        $updateResponse = Http::withToken($token)->put($writeUrl, [
            'range' => "{$this->sheetTab}!A1:AE" . count($allRows),
            'majorDimension' => 'ROWS',
            'values' => $allRows
        ]);

        if ($updateResponse->failed()) {
            throw new Exception('Failed to push products to Google Sheet: ' . $updateResponse->body());
        }

        return [
            'success' => true,
            'pushed_count' => count($products),
            'message' => 'Successfully pushed ' . count($products) . ' products and stock levels to Google Sheet!'
        ];
    }

    private function columnNumberToLetter(int $colNumber): string
    {
        $letter = '';
        while ($colNumber > 0) {
            $mod = ($colNumber - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colNumber = (int) (($colNumber - $mod) / 26);
        }
        return $letter;
    }
}
