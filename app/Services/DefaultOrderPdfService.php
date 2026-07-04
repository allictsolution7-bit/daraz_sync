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
                <td style='text-align:right;'>৳" . number_format($item->sub_total, 2) . "</td>
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
                <p>Subtotal: ৳" . number_format($order->total - $order->shipping + $order->discount, 2) . "</p>
                " . ($order->discount > 0 ? "<p>Discount: -৳" . number_format($order->discount, 2) . "</p>" : "") . "
                " . ($order->shipping > 0 ? "<p>Shipping: ৳" . number_format($order->shipping, 2) . "</p>" : "") . "
                <p style='font-size:12px;'>Total: ৳" . number_format($order->total, 2) . "</p>
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
                <td style='text-align:right;'>৳" . number_format($item->price, 2) . "</td>
                <td style='text-align:right;'>৳" . number_format($item->sub_total, 2) . "</td>
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
                        <td style='text-align:right;'>৳" . number_format($order->total - $order->shipping + $order->discount, 2) . "</td>
                    </tr>
                    " . ($order->discount > 0 ? "<tr><td>Discount:</td><td style='text-align:right;'>-৳" . number_format($order->discount, 2) . "</td></tr>" : "") . "
                    " . ($order->shipping > 0 ? "<tr><td>Shipping:</td><td style='text-align:right;'>৳" . number_format($order->shipping, 2) . "</td></tr>" : "") . "
                    <tr class='grand-total'>
                        <td>Total:</td>
                        <td style='text-align:right;'>৳" . number_format($order->total, 2) . "</td>
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
}
