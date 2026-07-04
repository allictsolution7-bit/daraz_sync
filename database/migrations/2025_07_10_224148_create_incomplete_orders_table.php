<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncompleteOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('incomplete_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone');
            $table->string('upazila')->nullable();
            $table->string('city')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable(); // 'checkout' or 'buynow'
            $table->json('product_details')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incomplete_orders');
    }
}
