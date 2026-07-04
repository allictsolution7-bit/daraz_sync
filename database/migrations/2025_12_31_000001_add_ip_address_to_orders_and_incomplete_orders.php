<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'ip_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('ip_address')->nullable()->after('order_source');
            });
        }

        if (Schema::hasTable('incomplete_orders') && !Schema::hasColumn('incomplete_orders', 'ip_address')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                $table->string('ip_address')->nullable()->after('source');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'ip_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('ip_address');
            });
        }

        if (Schema::hasTable('incomplete_orders') && Schema::hasColumn('incomplete_orders', 'ip_address')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                $table->dropColumn('ip_address');
            });
        }
    }
};
