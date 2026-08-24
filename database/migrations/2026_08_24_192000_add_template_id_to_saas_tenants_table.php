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
        if (Schema::connection('central')->hasTable('saas_tenants') && !Schema::connection('central')->hasColumn('saas_tenants', 'template_id')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->string('template_id', 20)->default('1')->after('commission_rate');
            });
        }
        if (Schema::hasTable('saas_tenants') && !Schema::hasColumn('saas_tenants', 'template_id')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->string('template_id', 20)->default('1')->after('commission_rate');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('central')->hasColumn('saas_tenants', 'template_id')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('template_id');
            });
        }
        if (Schema::hasColumn('saas_tenants', 'template_id')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('template_id');
            });
        }
    }
};
