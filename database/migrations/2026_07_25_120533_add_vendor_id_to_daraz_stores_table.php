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
        if (Schema::hasTable('daraz_stores') && !Schema::hasColumn('daraz_stores', 'vendor_id')) {
            Schema::table('daraz_stores', function (Blueprint $table) {
                $table->foreignId('vendor_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('daraz_stores') && Schema::hasColumn('daraz_stores', 'vendor_id')) {
            Schema::table('daraz_stores', function (Blueprint $table) {
                $table->dropForeign(['vendor_id']);
                $table->dropColumn('vendor_id');
            });
        }
    }
};
