<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Videos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Videos>
 */
class VideosFactory extends Factory
{
    protected $model = Videos::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'photo' => $this->faker->imageUrl,
            'video' => $this->faker->url,
            'category_id' => Category::factory(),
        ];
    }
}
