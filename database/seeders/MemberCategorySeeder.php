<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MemberCategory;

class MemberCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Undergraduate', 'color' => 'blue', 'icon' => 'academic-cap'],
            ['name' => 'Postgraduate', 'color' => 'purple', 'icon' => 'academic-cap'],
            ['name' => 'Teaching Staff', 'color' => 'green', 'icon' => 'user-group'],
            ['name' => 'Non-Teaching Staff', 'color' => 'amber', 'icon' => 'briefcase'],
            ['name' => 'Sunday School', 'color' => 'pink', 'icon' => 'academic-cap'],
            ['name' => 'Community Member', 'color' => 'indigo', 'icon' => 'home'],
            ['name' => 'Elder', 'color' => 'red', 'icon' => 'star'],
        ];

        foreach ($categories as $category) {
            MemberCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
