<?php

namespace Database\Seeders;

use App\Models\ResourceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Prayers', 'icon' => 'book-open', 'description' => 'Collection of daily and special prayers.'],
            ['name' => 'Novenas', 'icon' => 'calendar', 'description' => 'Nine-day devotional prayers.'],
            ['name' => 'Rosary', 'icon' => 'refresh-cw', 'description' => 'Guides and meditations for the Holy Rosary.'],
            ['name' => 'Hymns', 'icon' => 'music', 'description' => 'Songbooks and lyrics for liturgical hymns.'],
            ['name' => 'Scripture', 'icon' => 'book', 'description' => 'Bible studies and scripture readings.'],
        ];

        foreach ($categories as $category) {
            ResourceCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'description' => $category['description'],
            ]);
        }
    }
}
