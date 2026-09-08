<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImageSearchService
{
    /**
     * Cache key for precomputed product visual fingerprints
     */
    protected const CACHE_KEY = 'image_search_local_fingerprints_v2';
    protected const CACHE_TTL = 86400; // 24 hours

    /**
     * Search products matching an uploaded query image using 100% local computer vision algorithms.
     * Zero external AI, zero API keys, zero monthly costs.
     *
     * @param string $uploadedImagePath Absolute path to the uploaded image file
     * @param int $limit Max results to return
     * @return array
     */
    public function searchByImage(string $uploadedImagePath, int $limit = 12): array
    {
        if (!file_exists($uploadedImagePath) || !extension_loaded('gd')) {
            return [];
        }

        // 1. Calculate local visual fingerprints for query image
        $queryDhash = $this->calculateDhash($uploadedImagePath);
        $queryAhash = $this->calculateAhash($uploadedImagePath);
        $queryColorSignature = $this->calculateColorSignature($uploadedImagePath);

        // 2. Load precomputed catalog fingerprints from cache
        $catalogHashes = $this->getCatalogHashes();

        // 3. Score every product in the catalog using Hamming distance and color similarity
        $scoredProducts = [];

        foreach ($catalogHashes as $productId => $data) {
            $productDhash = $data['dhash'] ?? null;
            $productAhash = $data['ahash'] ?? null;
            $productColor = $data['color'] ?? null;

            if (!$productDhash) {
                continue;
            }

            // Difference hash similarity (weight: 45%)
            $dDist = $this->hammingDistance($queryDhash, $productDhash);
            $dSimilarity = max(0, (1 - ($dDist / 64)) * 100);

            // Average hash similarity (weight: 25%)
            $aDist = $productAhash ? $this->hammingDistance($queryAhash, $productAhash) : $dDist;
            $aSimilarity = max(0, (1 - ($aDist / 64)) * 100);

            // Color palette & histogram similarity (weight: 30%)
            $colorSimilarity = 100;
            if ($queryColorSignature && $productColor) {
                $colorSimilarity = $this->compareColorSignatures($queryColorSignature, $productColor);
            }

            // Combined multi-layer visual match score (0 - 100)
            $totalScore = ($dSimilarity * 0.45) + ($aSimilarity * 0.25) + ($colorSimilarity * 0.30);

            // Keep only true high-precision matches (75%+ similarity)
            if ($totalScore >= 75.0) {
                $scoredProducts[$productId] = round($totalScore, 1);
            }
        }

        // Sort descending by highest visual match
        arsort($scoredProducts);

        // If top match exists, discard anything that is significantly divergent from the top match
        if (!empty($scoredProducts)) {
            $topScore = reset($scoredProducts);
            $minAllowedScore = max(75.0, $topScore - 22.0);
            $scoredProducts = array_filter($scoredProducts, fn($s) => $s >= $minAllowedScore);
        }

        $topProductIds = array_slice(array_keys($scoredProducts), 0, $limit);

        // If no high-precision matches, return empty so UI shows clean "No match" state
        if (empty($topProductIds)) {
            return [];
        }

        // 4. Fetch full product models in order of best visual match
        $products = Product::whereIn('id', $topProductIds)
            ->where('status', 1)
            ->with(['category'])
            ->get()
            ->keyBy('id');

        $results = [];
        foreach ($topProductIds as $id) {
            if (isset($products[$id])) {
                $similarity = $scoredProducts[$id] ?? 50.0;
                $results[] = $this->formatProductResult($products[$id], $similarity);
            }
        }

        return $results;
    }

    /**
     * Compute Difference Hash (dHash) for an image.
     * Grayscale 9x8 matrix tracking horizontal gradients (64 bits).
     */
    public function calculateDhash(string $imagePath): string
    {
        $src = $this->createGdImage($imagePath);
        if (!$src) {
            return str_repeat('0', 16);
        }

        $width = 9;
        $height = 8;
        $resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
        imagedestroy($src);

        $pixels = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($resized, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $pixels[$y][$x] = (int) (0.299 * $r + 0.587 * $g + 0.114 * $b);
            }
        }
        imagedestroy($resized);

        $bits = '';
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $bits .= ($pixels[$y][$x] > $pixels[$y][$x + 1]) ? '1' : '0';
            }
        }

        $hex = '';
        for ($i = 0; $i < 64; $i += 4) {
            $hex .= dechex(bindec(substr($bits, $i, 4)));
        }

        return str_pad($hex, 16, '0', STR_PAD_LEFT);
    }

    /**
     * Compute Average Hash (aHash) for an image.
     * Grayscale 8x8 matrix comparing pixel values to mean intensity (64 bits).
     */
    public function calculateAhash(string $imagePath): string
    {
        $src = $this->createGdImage($imagePath);
        if (!$src) {
            return str_repeat('0', 16);
        }

        $width = 8;
        $height = 8;
        $resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
        imagedestroy($src);

        $pixels = [];
        $total = 0;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($resized, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $gray = (int) (0.299 * $r + 0.587 * $g + 0.114 * $b);
                $pixels[$y][$x] = $gray;
                $total += $gray;
            }
        }
        imagedestroy($resized);

        $avg = $total / 64;
        $bits = '';
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $bits .= ($pixels[$y][$x] >= $avg) ? '1' : '0';
            }
        }

        $hex = '';
        for ($i = 0; $i < 64; $i += 4) {
            $hex .= dechex(bindec(substr($bits, $i, 4)));
        }

        return str_pad($hex, 16, '0', STR_PAD_LEFT);
    }

    /**
     * Compute 16-quadrant RGB color signature.
     */
    public function calculateColorSignature(string $imagePath): ?array
    {
        $src = $this->createGdImage($imagePath);
        if (!$src) {
            return null;
        }

        $target = imagecreatetruecolor(4, 4);
        imagecopyresampled($target, $src, 0, 0, 0, 0, 4, 4, imagesx($src), imagesy($src));
        imagedestroy($src);

        $colors = [];
        for ($y = 0; $y < 4; $y++) {
            for ($x = 0; $x < 4; $x++) {
                $rgb = imagecolorat($target, $x, $y);
                $colors[] = [
                    'r' => ($rgb >> 16) & 0xFF,
                    'g' => ($rgb >> 8) & 0xFF,
                    'b' => $rgb & 0xFF,
                ];
            }
        }
        imagedestroy($target);

        return $colors;
    }

    /**
     * Helper to load GD image resource safely for JPEG, PNG, WEBP, GIF.
     */
    protected function createGdImage(string $imagePath)
    {
        if (!file_exists($imagePath)) {
            return null;
        }

        $imageInfo = @getimagesize($imagePath);
        if (!$imageInfo) {
            return null;
        }

        return match ($imageInfo[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($imagePath),
            IMAGETYPE_PNG => @imagecreatefrompng($imagePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($imagePath) : null,
            IMAGETYPE_GIF => @imagecreatefromgif($imagePath),
            default => null,
        };
    }

    /**
     * Calculate Hamming distance between two hex hashes (0 to 64).
     */
    protected function hammingDistance(string $hex1, string $hex2): int
    {
        $bin1 = str_pad(base_convert($hex1, 16, 2), 64, '0', STR_PAD_LEFT);
        $bin2 = str_pad(base_convert($hex2, 16, 2), 64, '0', STR_PAD_LEFT);

        $distance = 0;
        for ($i = 0; $i < 64; $i++) {
            if ($bin1[$i] !== $bin2[$i]) {
                $distance++;
            }
        }

        return $distance;
    }

    /**
     * Compare two color signatures (similarity 0 - 100).
     */
    protected function compareColorSignatures(array $sig1, array $sig2): float
    {
        if (count($sig1) !== count($sig2)) {
            return 50.0;
        }

        $totalDiff = 0;
        $count = count($sig1);

        for ($i = 0; $i < $count; $i++) {
            $dr = abs($sig1[$i]['r'] - $sig2[$i]['r']);
            $dg = abs($sig1[$i]['g'] - $sig2[$i]['g']);
            $db = abs($sig1[$i]['b'] - $sig2[$i]['b']);
            $totalDiff += ($dr + $dg + $db) / (255 * 3);
        }

        $avgDiff = $totalDiff / $count;
        return max(0, (1 - $avgDiff) * 100);
    }

    /**
     * Get or build cached catalog fingerprints for all active products.
     */
    public function getCatalogHashes(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $products = Product::where('status', 1)
                ->select(['id', 'title', 'thumb_image'])
                ->get();

            $hashes = [];
            foreach ($products as $product) {
                if (!$product->thumb_image) {
                    continue;
                }

                $imagePath = public_path('storage/' . $product->thumb_image);
                if (!file_exists($imagePath)) {
                    $altPath = public_path($product->thumb_image);
                    if (file_exists($altPath)) {
                        $imagePath = $altPath;
                    } else {
                        continue;
                    }
                }

                $dhash = $this->calculateDhash($imagePath);
                $ahash = $this->calculateAhash($imagePath);
                $color = $this->calculateColorSignature($imagePath);

                $hashes[$product->id] = [
                    'dhash' => $dhash,
                    'ahash' => $ahash,
                    'color' => $color,
                    'title' => $product->title,
                ];
            }

            return $hashes;
        });
    }

    /**
     * Rebuild and refresh catalog visual fingerprints.
     */
    public function rebuildCatalogHashes(): int
    {
        Cache::forget(self::CACHE_KEY);
        $hashes = $this->getCatalogHashes();
        return count($hashes);
    }

    /**
     * Format product data for JSON response.
     */
    protected function formatProductResult(Product $product, float $similarity): array
    {
        $price = $product->offer ?? $product->price ?? $product->old_price ?? 0;
        $imageUrl = $product->thumb_image 
            ? (str_starts_with($product->thumb_image, 'http') ? $product->thumb_image : asset('storage/' . $product->thumb_image))
            : asset('new/placeholder.png');

        return [
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => (float) $price,
            'old_price' => $product->old_price ? (float) $product->old_price : null,
            'formatted_price' => '৳ ' . number_format((float) $price, 2),
            'formatted_old_price' => $product->old_price ? '৳ ' . number_format((float) $product->old_price, 2) : null,
            'image' => $imageUrl,
            'url' => route('product.single', ['id' => $product->id, 'slug' => $product->slug]),
            'similarity' => min(99, round($similarity)),
            'category_name' => $product->category->name ?? null,
        ];
    }
}
