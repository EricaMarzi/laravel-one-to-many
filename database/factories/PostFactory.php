<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Storage::makeDirectory('image_post');

        $title = fake()->text(20);
        $slug = Str::slug($title);
        $img = fake()->image(null, 250, 250);
        $img_url = Storage::putFileAs('image_post', $img, $slug);

        Storage::makeDirectory('post_image');

        return [
            'title' => $title,
            'slug' => $slug,
            'content' => fake()->paragraph(40, true),
            'image' => $img_url,
            'is_published' => fake()->boolean()
        ];
    }
}
