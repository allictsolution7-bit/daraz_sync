<?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Str;
    use Random\RandomException;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
     */
    class ProductFactory extends Factory {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         * @throws \JsonException
         * @throws RandomException
         */
        public function definition (): array {
            $images = [];
            $random = random_int (1,5);
            for ($i = 0; $i < $random; $i++) {
                $image = fake ()->image (public_path ()."/products",500,500,null,false);
                $images[]= "products/".$image;
            }
            $thumb_image = "products/".fake ()->image (public_path ()."/products",500,500,null,false);
            $title = fake ()->text (50);
            return [
                "title" => $title,
                "slug"  => Str::slug ($title),
                "category_id"     => random_int (1, 10),
                "old_price"       => random_int (200, 500),
                "sub_category_id" => random_int (1, 10),
                "quantity"        => random_int (10, 100),
                "description"     => fake ()->text (350),
                "thumb_image"     =>$thumb_image,
                "images"          => json_encode ($images, JSON_THROW_ON_ERROR),
                "tags"            => fake ()->text ("15")
            ];
        }
    }
