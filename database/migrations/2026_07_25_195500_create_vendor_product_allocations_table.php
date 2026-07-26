<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_product_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('requested_quantity')->default(0);
            $table->integer('allocated_quantity')->default(0);
            $table->json('variation_allocations')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });

        if (!Schema::hasColumn('vendor_wallet_transactions', 'product_id')) {
            Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
                $table->foreignId('product_id')->nullable()->after('vendor_id')->constrained('products')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vendor_wallet_transactions', 'product_id')) {
            Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            });
        }
        Schema::dropIfExists('vendor_product_allocations');
    }
};
