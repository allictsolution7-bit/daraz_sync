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
        Schema::table('licenses', function (Blueprint $table) {
            // Support period fields
            $table->date('support_start_date')->nullable()->after('last_synced_at');
            $table->integer('support_duration')->default(0)->comment('Support duration in days')->after('support_start_date');
            $table->date('support_end_date')->nullable()->after('support_duration');
            
            // Update period fields
            $table->date('update_start_date')->nullable()->after('support_end_date');
            $table->integer('update_duration')->default(0)->comment('Update access duration in days')->after('update_start_date');
            $table->date('update_end_date')->nullable()->after('update_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'support_start_date',
                'support_duration', 
                'support_end_date',
                'update_start_date',
                'update_duration',
                'update_end_date'
            ]);
        });
    }
};
