<?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Str;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activities>
     */
    class ActivitiesFactory extends Factory {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition (): array {
            $title =fake ()->text ("35");
            $slug = Str::slug ($title);
            $thumb_image = "activities/".fake ()->image (public_path ()."/activities",500,500,null,false);
            return [
                "title" => $title,
                "slug" => $slug,
                "note"  => fake ()->text (30),
                "thumb_images" => $thumb_image,
                "descriptions" => fake ()->text(),
            ];
        }
    }
