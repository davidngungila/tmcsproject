<?php

namespace Database\Seeders;

use App\Models\MemberCategory;
use Illuminate\Database\Seeder;

class MemberCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Student', 'description' => 'Student members', 'color' => 'blue', 'is_active' => true],
            ['name' => 'Staff', 'description' => 'Staff members', 'color' => 'green', 'is_active' => true],
            ['name' => 'Child', 'description' => 'Child members (under 18)', 'color' => 'purple', 'is_active' => true],
            ['name' => 'Non-Staff', 'description' => 'Non-staff adult members', 'color' => 'orange', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            MemberCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}