<?php

namespace App\Http\Controllers;

use App\Services\DefaultOrderPdfService;
use App\Services\Modules\Module;
use Illuminate\Http\Request;

/**
 * Controller for public Order PDF routes
 * Uses POS module if available, otherwise falls back to default service
 */
class OrderPdfController extends Controller
{
    /**
     * Print receipt PDF
     */
    public function printReceipt($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->printReceipt($order);
        }
        return app(DefaultOrderPdfService::class)->printReceipt($order);
    }

    /**
     * Print invoice PDF
     */
    public function printInvoice($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->printInvoice($order);
        }
        return app(DefaultOrderPdfService::class)->printInvoice($order);
    }

    /**
     * Print package slip PDF
     */
    public function printPackageSlip($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->printPackageSlip($order);
        }
        return app(DefaultOrderPdfService::class)->printPackageSlip($order);
    }

    /**
     * Download receipt PDF
     */
    public function downloadReceipt($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->downloadReceipt($order);
        }
        return app(DefaultOrderPdfService::class)->downloadReceipt($order);
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->downloadInvoice($order);
        }
        return app(DefaultOrderPdfService::class)->downloadInvoice($order);
    }

    /**
     * Download package slip PDF
     */
    public function downloadPackageSlip($order)
    {
        if (Module::isEnabled('POS')) {
            return app(\Modules\POS\Http\Controllers\POSController::class)->downloadPackageSlip($order);
        }
        return app(DefaultOrderPdfService::class)->downloadPackageSlip($order);
    }
}
