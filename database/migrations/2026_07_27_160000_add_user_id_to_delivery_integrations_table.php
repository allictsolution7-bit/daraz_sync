<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('delivery_integrations', 'user_id')) {
            Schema::table('delivery_integrations', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }

        // Assign existing integrations to sabbir@purnobd.com (User ID 130 or sabbir)
        $sabbir = DB::table('users')
            ->where('email', 'like', '%sabbir%')
            ->orWhere('name', 'like', '%sabbir%')
            ->first();

        if ($sabbir) {
            DB::table('delivery_integrations')->update(['user_id' => $sabbir->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('delivery_integrations', 'user_id')) {
            Schema::table('delivery_integrations', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
