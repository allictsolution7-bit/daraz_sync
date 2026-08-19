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
        if (Schema::connection('central')->hasTable('saas_tenants') && !Schema::connection('central')->hasColumn('saas_tenants', 'custom_domain')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->string('custom_domain')->nullable()->unique()->after('subdomain');
            });
        }
        if (Schema::hasTable('saas_tenants') && !Schema::hasColumn('saas_tenants', 'custom_domain')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->string('custom_domain')->nullable()->unique()->after('subdomain');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('central')->hasColumn('saas_tenants', 'custom_domain')) {
            Schema::connection('central')->table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('custom_domain');
            });
        }
        if (Schema::hasColumn('saas_tenants', 'custom_domain')) {
            Schema::table('saas_tenants', function (Blueprint $table) {
                $table->dropColumn('custom_domain');
            });
        }
    }
};
