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
        if (Schema::connection('central')->hasTable('saas_tenants') && !Schema::connection('central')->hasColumn('saas_tenants', 'free_promotion')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->boolean('free_promotion')->default(false)->after('is_active');
            });
        }
        
        if (Schema::hasTable('saas_tenants') && !Schema::hasColumn('saas_tenants', 'free_promotion')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->boolean('free_promotion')->default(false)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('central')->hasColumn('saas_tenants', 'free_promotion')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('free_promotion');
            });
        }
    }
};
