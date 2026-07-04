<?php

namespace App\Contracts;

use App\Models\order;

/**
 * Interface for Order PDF generation
 * Implemented by POS module or fallback service
 */
interface OrderPdfServiceInterface
{
    /**
     * Print receipt PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function printReceipt($order);

    /**
     * Print invoice PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function printInvoice($order);

    /**
     * Print package slip PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function printPackageSlip($order);

    /**
     * Download receipt PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($order);

    /**
     * Download invoice PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice($order);

    /**
     * Download package slip PDF for an order
     *
     * @param int|order $order Order ID or Order model
     * @return \Illuminate\Http\Response
     */
    public function downloadPackageSlip($order);
}
