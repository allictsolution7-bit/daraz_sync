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
        if (Schema::connection('central')->hasTable('saas_tenants') && !Schema::connection('central')->hasColumn('saas_tenants', 'commission_rate')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->decimal('commission_rate', 5, 2)->nullable()->default(null)->after('free_promotion');
            });
        }
        
        if (Schema::hasTable('saas_tenants') && !Schema::hasColumn('saas_tenants', 'commission_rate')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->decimal('commission_rate', 5, 2)->nullable()->default(null)->after('free_promotion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('central')->hasColumn('saas_tenants', 'commission_rate')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('commission_rate');
            });
        }
    }
};
