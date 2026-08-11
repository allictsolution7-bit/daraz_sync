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
        // Try-catch block to handle index dropping gracefully if needed
        try {
            Schema::table('chat_rooms', function (Blueprint $table) {
                // Drop foreign keys first to allow index removal
                try {
                    $table->dropForeign(['customer_id']);
                } catch (\Exception $e) {}
                try {
                    $table->dropForeign(['vendor_id']);
                } catch (\Exception $e) {}

                // Drop old unique constraint
                try {
                    $table->dropUnique('chat_rooms_customer_id_vendor_id_unique');
                } catch (\Exception $e) {}

                // Re-add foreign keys
                try {
                    $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                } catch (\Exception $e) {}
                try {
                    $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {}

        // Add wholeseller_id column if not exists
        if (!Schema::hasColumn('chat_rooms', 'wholeseller_id')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->unsignedBigInteger('wholeseller_id')->nullable()->after('vendor_id');
            });
        }

        // Add wholeseller foreign key and new unique index
        try {
            Schema::table('chat_rooms', function (Blueprint $table) {
                try {
                    $table->foreign('wholeseller_id')->references('id')->on('users')->onDelete('set null');
                } catch (\Exception $e) {}
                try {
                    $table->unique(['customer_id', 'vendor_id', 'wholeseller_id'], 'chat_rooms_cust_vend_whole_unique');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            try {
                $table->dropForeign(['customer_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['vendor_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['wholeseller_id']);
            } catch (\Exception $e) {}
            
            try {
                $table->dropUnique('chat_rooms_cust_vend_whole_unique');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('wholeseller_id');
            } catch (\Exception $e) {}

            try {
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            } catch (\Exception $e) {}
            try {
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            } catch (\Exception $e) {}
            try {
                $table->unique(['customer_id', 'vendor_id'], 'chat_rooms_customer_id_vendor_id_unique');
            } catch (\Exception $e) {}
        });
    }
};
