<?php

namespace App\Services;

use App\Contracts\OrderPdfServiceInterface;
use App\Models\order;

/**
 * Default (fallback) PDF service when POS module is not installed
 * Provides basic PDF generation functionality
 */
class DefaultOrderPdfService implements OrderPdfServiceInterface
{
    /**
     * Print receipt PDF for an order
     */
    public function printReceipt($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf([80, 200]);
            $html = $this->generateReceiptHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("receipt-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="receipt-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating receipt PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice PDF for an order
     */
    public function printInvoice($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf('A4');
            $html = $this->generateInvoiceHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("invoice-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="invoice-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print package slip PDF for an order
     */
    public function printPackageSlip($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf([80, 150]);
            $html = $this->generatePackageSlipHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("package-slip-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="package-slip-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating package slip PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download receipt PDF
     */
    public function downloadReceipt($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf([80, 200]);
            $html = $this->generateReceiptHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("receipt-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="receipt-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating receipt PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf('A4');
            $html = $this->generateInvoiceHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("invoice-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="invoice-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download package slip PDF
     */
    public function downloadPackageSlip($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf([80, 150]);
            $html = $this->generatePackageSlipHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("package-slip-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="package-slip-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating package slip PDF: ' . $e->getMessage());
        }
    }

    /**
     * Resolve order from ID or model
     */
    protected function resolveOrder($order): order
    {
        if ($order instanceof order) {
            return $order->load(['order_items.product', 'order_items.variationCombination', 'user']);
        }

        return order::with(['order_items.product', 'order_items.variationCombination', 'user'])
            ->findOrFail($order);
    }

    /**
     * Create mPDF instance with standard settings
     */
    protected function createMpdf($format): \Mpdf\Mpdf
    {
        $config = [
            'mode' => 'utf-8',
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ];

        if (is_array($format)) {
            $config['format'] = $format;
            $config['margin_left'] = 2;
            $config['margin_right'] = 2;
            $config['margin_top'] = 5;
            $config['margin_bottom'] = 5;
        } else {
            $config['format'] = $format;
            $config['margin_left'] = 15;
            $config['margin_right'] = 15;
            $config['margin_top'] = 15;
            $config['margin_bottom'] = 15;
        }

        return new \Mpdf\Mpdf($config);
    }

    /**
     * Get site settings for PDF
     */
    protected function getSiteSettings(): array
    {
        return [
            'site_name' => SettingsService::getSiteName(),
            'contact_email' => SettingsService::getContactEmail(),
            'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
            'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
            'website' => config('app.url', 'www.thikana.shop'),
        ];
    }

    /**
     * Generate basic receipt HTML
     */
    protected function generateReceiptHtml(order $order): string
    {
        $settings = $this->getSiteSettings();

        $itemsHtml = '';
        foreach ($order->order_items as $item) {
            $itemsHtml .= "<tr>
                <td>{$item->product->title}</td>
                <td style='text-align:center;'>{$item->quantity}</td>
                <td style='text-align:right;'>Tk. " . number_format($item->sub_total, 2) . "</td>
            </tr>";
        }

        return "
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
                .header { text-align: center; margin-bottom: 10px; }
                .items { width: 100%; border-collapse: collapse; }
                .items th, .items td { padding: 3px; border-bottom: 1px dashed #ccc; }
                .total { text-align: right; margin-top: 10px; font-weight: bold; }
                .footer { text-align: center; margin-top: 15px; font-size: 9px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h3 style='margin:0;'>{$settings['site_name']}</h3>
                <p style='margin:3px 0;'>{$settings['address']}</p>
                <p style='margin:3px 0;'>Phone: {$settings['phone_number']}</p>
            </div>

            <p><strong>Order #:</strong> {$order->id}</p>
            <p><strong>Date:</strong> {$order->created_at->format('M d, Y H:i')}</p>
            <p><strong>Customer:</strong> {$order->name}</p>

            <table class='items'>
                <thead>
                    <tr>
                        <th style='text-align:left;'>Item</th>
                        <th style='text-align:center;'>Qty</th>
                        <th style='text-align:right;'>Total</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>

            <div class='total'>
                <p>Subtotal: Tk. " . number_format($order->total - $order->shipping + $order->discount, 2) . "</p>
                " . ($order->discount > 0 ? "<p>Discount: -Tk. " . number_format($order->discount, 2) . "</p>" : "") . "
                " . ($order->shipping > 0 ? "<p>Shipping: Tk. " . number_format($order->shipping, 2) . "</p>" : "") . "
                <p style='font-size:12px;'>Total: Tk. " . number_format($order->total, 2) . "</p>
            </div>

            <div class='footer'>
                <p>Thank you for your purchase!</p>
                <p>{$settings['website']}</p>
            </div>
        </body>
        </html>";
    }

    /**
     * Generate basic invoice HTML
     */
    protected function generateInvoiceHtml(order $order): string
    {
        $settings = $this->getSiteSettings();

        $itemsHtml = '';
        foreach ($order->order_items as $item) {
            $itemsHtml .= "<tr>
                <td>{$item->product->title}</td>
                <td style='text-align:center;'>{$item->quantity}</td>
                <td style='text-align:right;'>Tk. " . number_format($item->price, 2) . "</td>
                <td style='text-align:right;'>Tk. " . number_format($item->sub_total, 2) . "</td>
            </tr>";
        }

        return "
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
                .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
                .company { }
                .invoice-info { text-align: right; }
                .customer { margin-bottom: 20px; padding: 15px; background: #f5f5f5; }
                .items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .items th { background: #333; color: white; padding: 10px; }
                .items td { padding: 10px; border-bottom: 1px solid #ddd; }
                .totals { float: right; width: 250px; }
                .totals table { width: 100%; }
                .totals td { padding: 5px; }
                .totals .grand-total { font-size: 14px; font-weight: bold; border-top: 2px solid #333; }
                .footer { clear: both; text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <table width='100%'>
                <tr>
                    <td>
                        <h2 style='margin:0;'>{$settings['site_name']}</h2>
                        <p>{$settings['address']}</p>
                        <p>Phone: {$settings['phone_number']}</p>
                        <p>Email: {$settings['contact_email']}</p>
                    </td>
                    <td style='text-align:right;'>
                        <h1 style='margin:0; color:#333;'>INVOICE</h1>
                        <p><strong>Invoice #:</strong> INV-{$order->id}</p>
                        <p><strong>Date:</strong> {$order->created_at->format('M d, Y')}</p>
                        <p><strong>Status:</strong> " . ucfirst($order->status) . "</p>
                    </td>
                </tr>
            </table>

            <div class='customer'>
                <strong>Bill To:</strong><br>
                {$order->name}<br>
                {$order->address}<br>
                {$order->city}<br>
                Phone: {$order->phone}
            </div>

            <table class='items'>
                <thead>
                    <tr>
                        <th style='text-align:left;'>Product</th>
                        <th style='text-align:center;'>Qty</th>
                        <th style='text-align:right;'>Price</th>
                        <th style='text-align:right;'>Total</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>

            <div class='totals'>
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td style='text-align:right;'>Tk. " . number_format($order->total - $order->shipping + $order->discount, 2) . "</td>
                    </tr>
                    " . ($order->discount > 0 ? "<tr><td>Discount:</td><td style='text-align:right;'>-Tk. " . number_format($order->discount, 2) . "</td></tr>" : "") . "
                    " . ($order->shipping > 0 ? "<tr><td>Shipping:</td><td style='text-align:right;'>Tk. " . number_format($order->shipping, 2) . "</td></tr>" : "") . "
                    <tr class='grand-total'>
                        <td>Total:</td>
                        <td style='text-align:right;'>Tk. " . number_format($order->total, 2) . "</td>
                    </tr>
                </table>
            </div>

            <div class='footer'>
                <p>Thank you for your business!</p>
                <p>{$settings['website']}</p>
            </div>
        </body>
        </html>";
    }

    /**
     * Generate basic package slip HTML
     */
    protected function generatePackageSlipHtml(order $order): string
    {
        $settings = $this->getSiteSettings();

        $itemsHtml = '';
        foreach ($order->order_items as $item) {
            $itemsHtml .= "<tr>
                <td>{$item->product->title}</td>
                <td style='text-align:center;'>{$item->quantity}</td>
            </tr>";
        }

        return "
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
                .header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
                .info { margin-bottom: 10px; }
                .items { width: 100%; border-collapse: collapse; }
                .items th, .items td { padding: 3px; border-bottom: 1px dashed #ccc; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h3 style='margin:0;'>PACKAGE SLIP</h3>
                <p style='margin:3px 0;'>{$settings['site_name']}</p>
            </div>

            <div class='info'>
                <p><strong>Order #:</strong> {$order->id}</p>
                <p><strong>Date:</strong> {$order->created_at->format('M d, Y')}</p>
                <p><strong>Ship To:</strong></p>
                <p>{$order->name}<br>{$order->address}<br>{$order->city}</p>
                <p><strong>Phone:</strong> {$order->phone}</p>
            </div>

            <table class='items'>
                <thead>
                    <tr>
                        <th style='text-align:left;'>Item</th>
                        <th style='text-align:center;'>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>
        </body>
        </html>";
    }

    /**
     * Print Steadfast style Invoice PDF for an order
     */
    public function printSteadfastInvoice($order)
    {
        $order = $this->resolveOrder($order);

        try {
            $mpdf = $this->createMpdf('A4');
            $html = $this->generateSteadfastInvoiceHtml($order);
            $mpdf->WriteHTML($html);

            return response($mpdf->Output("steadfast-invoice-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="steadfast-invoice-' . $order->id . '.pdf"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating Steadfast invoice PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate Steadfast format Invoice HTML matching courier receipt design
     */
    protected function generateSteadfastInvoiceHtml(order $order): string
    {
        $settings = $this->getSiteSettings();
        
        $deliveryData = $order->delivery_data ?? [];
        $consignmentId = $deliveryData['consignment_id'] 
            ?? $deliveryData['courier_response']['consignment']['consignment_id'] 
            ?? $deliveryData['courier_response']['consignment_id'] 
            ?? 'N/A';
        $trackingCode = $deliveryData['tracking_code'] 
            ?? $deliveryData['courier_response']['consignment']['tracking_code'] 
            ?? $deliveryData['courier_response']['tracking_code'] 
            ?? null;

        if (!$trackingCode && $consignmentId !== 'N/A') {
            $trackingCode = $consignmentId;
        }

        $trackingLink = $trackingCode ? "https://steadfast.com.bd/tl/{$trackingCode}" : null;

        $codAmount = (float) match($order->payment_type) {
            'full_paid' => 0,
            'partial' => $order->due_amount,
            default => ($order->total_with_charge ?? $order->total ?? 0),
        };

        $dateFormatted = $order->created_at ? $order->created_at->format('d-m-Y') : date('d-m-Y');
        $invoiceNo = $order->invoice_no ?? $order->order_number ?? $order->id;
        $note = $order->courier_note ?? $order->admin_note ?? '';

        $qrCodeUrl = $trackingLink 
            ? "https://quickchart.io/qr?text=" . urlencode($trackingLink) . "&size=90"
            : ($consignmentId !== 'N/A' ? "https://quickchart.io/qr?text=" . urlencode($consignmentId) . "&size=90" : null);

        $qrCodeHtml = $qrCodeUrl ? "<div style='margin-top: 10px; text-align: right;'><img src='{$qrCodeUrl}' style='width: 80px; height: 80px;' /></div>" : "";

        $trackingHtml = '';
        if ($trackingCode) {
            $trackingHtml = "
                <div class='field-line' style='margin-top: 10px;'>Tracking Code : <strong>{$trackingCode}</strong></div>
                <div class='field-line'>Tracking Link : <a href='{$trackingLink}' target='_blank' style='color:#0284c7; text-decoration:none;'>{$trackingLink}</a></div>";
        }

        return "
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #000000; margin: 0; padding: 20px; }
                
                .header-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                
                .circle-logo {
                    width: 75px;
                    height: 75px;
                    background-color: #9e9e9e;
                    border-radius: 50px;
                    text-align: center;
                    vertical-align: middle;
                }
                
                .store-name { font-size: 16px; font-weight: bold; margin-top: 14px; margin-bottom: 4px; color: #000000; }
                .store-info { font-size: 13px; color: #111111; margin: 3px 0; line-height: 1.4; }
                
                .invoice-heading { font-size: 34px; font-weight: bold; margin: 0 0 12px 0; text-align: right; color: #000000; }
                .meta-line { font-size: 14px; text-align: right; color: #000000; margin: 4px 0; font-weight: normal; }
                
                .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 30px; }
                .section-title { font-size: 17px; font-weight: bold; margin-bottom: 12px; color: #000000; }
                .field-line { font-size: 14px; margin: 6px 0; color: #000000; line-height: 1.45; word-wrap: break-word; }
                
                .parcel-title { font-size: 15px; font-weight: bold; color: #000000; margin-bottom: 6px; }
                
                .cod-badge {
                    border: 2px solid #000000;
                    border-radius: 6px;
                    padding: 6px 18px;
                    font-weight: bold;
                    font-size: 16px;
                    display: inline-block;
                    margin-top: 12px;
                    color: #000000;
                }
                
                .note-box { margin-top: 20px; font-size: 14px; font-weight: bold; color: #000000; }
                .footer-brand { text-align: right; font-size: 12px; color: #333333; margin-top: 250px; }
            </style>
        </head>
        <body>
            <table class='header-table'>
                <tr>
                    <td style='vertical-align: top; width: 60%;'>
                        <div class='circle-logo'>
                            <img src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNmZmZmZmYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48Y2lyY2xlIGN4PSI5IiBjeT0iMjEiIHI9IjEiLz48Y2lyY2xlIGN4PSIyMCIgY3k9IjIxIiByPSIxIi8+PHBhdGggZD0iTTEgMWg0bDIuNjggMTMuMzlhMiAyIDAgMCAwIDIgMS42MWg5LjcyYTIgMiAwIDAgMCAyLTEuNjFMMjMgNkg2Ii8+PC9zdmc+' style='width: 42px; height: 42px; margin-top: 16px;' />
                        </div>
                        <div class='store-name'>{$settings['site_name']}</div>
                        <div class='store-info'>Phone: {$settings['phone_number']}</div>
                        <div class='store-info'>Address: {$settings['address']}</div>
                    </td>
                    <td style='vertical-align: top; text-align: right; width: 40%;'>
                        <div class='invoice-heading'>Invoice</div>
                        <div class='meta-line'><strong>Invoice No :</strong> #{$invoiceNo}</div>
                        <div class='meta-line'><strong>Date :</strong> {$dateFormatted}</div>
                    </td>
                </tr>
            </table>

            <table class='details-table'>
                <tr>
                    <td style='vertical-align: top; width: 55%;'>
                        <div class='section-title'>Ship To</div>
                        <div class='field-line'>Name : <strong>{$order->name}</strong></div>
                        <div class='field-line'>Phone : <strong>{$order->phone}</strong></div>
                        <div class='field-line'>Address : {$order->address}</div>
                        {$trackingHtml}
                    </td>
                    <td style='vertical-align: top; text-align: right; width: 45%;'>
                        <div class='parcel-title'>Parcel ID : #{$consignmentId}</div>
                        <div style='margin-top: 6px; margin-bottom: 10px;'>
                            <barcode code='{$consignmentId}' type='C128A' size='1.2' height='1.3' />
                        </div>
                        <div>
                            <div class='cod-badge'>COD : {$codAmount} BDT</div>
                        </div>
                        {$qrCodeHtml}
                    </td>
                </tr>
            </table>

            <div class='note-box'>
                Note : {$note}
            </div>

            <div class='footer-brand'>
                Powered By Steadfast Courier Ltd.
            </div>
        </body>
        </html>";
    }
}
