<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up (): void {
            Schema::create ('sub_categories', function (Blueprint $table) {
                $table->id ();
                $table->foreignIdFor (\App\Models\ProductCategory::class)->constrained ()->cascadeOnDelete ();
                $table->string ("name");
                $table->string ("slug");
                $table->string ("description")->nullable ();
                $table->boolean ("status")->default (true);
                $table->boolean ("show_menu")->default (false);
                $table->timestamps ();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down (): void {
            Schema::dropIfExists ('sub_categories');
        }
    };
