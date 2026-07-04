<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\order;
use App\Services\VendorService;

class ProcessVendorEarnings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vendor:process-earnings {--order-id= : Process specific order ID}';

    /**
     * The console command description.
     */
    protected $description = 'Process vendor earnings from delivered orders';

    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('order-id');

        if ($orderId) {
            // Process specific order
            $order = order::find($orderId);
            if (!$order) {
                $this->error("Order #{$orderId} not found!");
                return 1;
            }

            $this->processOrder($order);
        } else {
            // Process all delivered orders that haven't been credited
            $this->info('Finding delivered orders with unpaid vendor items...');
            
            $orders = order::where('status', 'delivered')
                ->whereHas('order_items', function ($query) {
                    $query->whereNotNull('vendor_id')
                          ->where('vendor_paid', false);
                })
                ->get();

            if ($orders->isEmpty()) {
                $this->info('✅ No orders found that need processing.');
                return 0;
            }

            $this->info("Found {$orders->count()} orders to process...");
            $bar = $this->output->createProgressBar($orders->count());

            foreach ($orders as $order) {
                $this->processOrder($order);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            $this->info('✅ All vendor earnings processed successfully!');
        }

        return 0;
    }

    protected function processOrder(order $order)
    {
        $this->line("\nProcessing Order #{$order->id}...");
        
        $vendorItems = $order->order_items()
            ->whereNotNull('vendor_id')
            ->where('vendor_paid', false)
            ->get();

        if ($vendorItems->isEmpty()) {
            $this->warn("  No unpaid vendor items in this order.");
            return;
        }

        foreach ($vendorItems as $item) {
            if ($item->vendor_earning > 0) {
                $this->vendorService->creditVendorEarning($item);
                
                $this->info("  ✓ Credited ৳{$item->vendor_earning} to Vendor #{$item->vendor_id}");
            }
        }
    }
}

