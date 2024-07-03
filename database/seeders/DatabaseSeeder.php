<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Videos;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create 5 categories, each with 5 videos
        Category::factory(5)
            ->has(Videos::factory()->count(5), 'videos')
            ->create();

        // Create 10 tags
        $tags = Tag::factory(10)->create();

        // Associate tags with videos
        Videos::all()->each(function ($video) use ($tags) {
            $video->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
