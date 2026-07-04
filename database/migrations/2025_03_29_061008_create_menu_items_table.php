<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->constrained()->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
                $table->string('title');
                $table->string('url')->nullable();
                $table->string('target')->default('_self');
                $table->string('icon_class')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}