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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'return_period')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('return_period')->default(0)->after('video_url');
            });
        }

        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'delivered_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('delivered_at')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'return_period')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('return_period');
            });
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'delivered_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('delivered_at');
            });
        }
    }
};
