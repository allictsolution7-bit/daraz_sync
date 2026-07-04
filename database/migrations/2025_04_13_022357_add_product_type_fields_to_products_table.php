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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_type', ['simple', 'variable', 'digital', 'affiliate'])->default('simple')->after('status');
            $table->string('digital_file')->nullable()->after('product_type');
            $table->integer('download_limit')->nullable()->after('digital_file');
            $table->string('external_url')->nullable()->after('download_limit');
            $table->decimal('affiliate_commission', 8, 2)->nullable()->after('external_url');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock')->after('quantity');
            $table->boolean('manage_stock')->default(true)->after('stock_status');
            $table->integer('low_stock_threshold')->default(5)->after('manage_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_type');
            $table->dropColumn('digital_file');
            $table->dropColumn('download_limit');
            $table->dropColumn('external_url');
            $table->dropColumn('affiliate_commission');
            $table->dropColumn('stock_status');
            $table->dropColumn('manage_stock');
            $table->dropColumn('low_stock_threshold');
        });
    }
};