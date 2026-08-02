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
        Schema::table('delivery_locations', function (Blueprint $table) {
            $table->string('division')->nullable()->after('city');
            $table->string('district')->nullable()->after('division');
            $table->string('post_code')->nullable()->after('upazila');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_locations', function (Blueprint $table) {
            $table->dropColumn(['division', 'district', 'post_code']);
        });
    }
};
