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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('upazila');
            $table->string('city');
            $table->string('address');
            $table->json('delivery_data')->nullable()->after('address');
            $table->string('phone');
            $table->string('message')->nullable();
            $table->foreignIdFor (App\Models\User::class)->constrained ()->cascadeOnDelete ();
            $table->string ('status')->default ('pending');
            $table->string ('admin_note')->nullable();
            $table->bigInteger ('assign')->nullable();
            $table->decimal('total', 14, 2);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('shipping', 14, 2)->default(0);
            $table->string('payment_method');
            $table->string('order_source');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
