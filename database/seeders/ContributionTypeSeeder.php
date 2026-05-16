<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ContributionType;

class ContributionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'code' => 'TTH-01',
                'name' => 'Tithe',
                'description' => 'Regular monthly tithe contributions (10% of income).',
                'gl_account' => '4000-01',
                'min_amount' => 0,
                'is_mandatory' => true,
                'frequency' => 'monthly',
                'color' => 'blue',
                'icon' => 'bank',
                'is_active' => true,
            ],
            [
                'code' => 'OFF-01',
                'name' => 'General Offering',
                'description' => 'Weekly Sunday service offerings.',
                'gl_account' => '4000-02',
                'min_amount' => 0,
                'is_mandatory' => false,
                'frequency' => 'weekly',
                'color' => 'green',
                'icon' => 'cash',
                'is_active' => true,
            ],
            [
                'code' => 'BLD-01',
                'name' => 'Building Fund',
                'description' => 'Special contributions for church construction and maintenance.',
                'gl_account' => '4000-03',
                'min_amount' => 5000,
                'is_mandatory' => false,
                'frequency' => 'one-time',
                'color' => 'amber',
                'icon' => 'office-building',
                'is_active' => true,
            ],
            [
                'code' => 'ZKA-01',
                'name' => 'Almsgiving/Zaka',
                'description' => 'Charitable contributions for the poor and needy.',
                'gl_account' => '4000-04',
                'min_amount' => 0,
                'is_mandatory' => false,
                'frequency' => 'one-time',
                'color' => 'purple',
                'icon' => 'heart',
                'is_active' => true,
            ],
            [
                'code' => 'MIS-01',
                'name' => 'Missionary Support',
                'description' => 'Funds dedicated to supporting missionary work and outreach programs.',
                'gl_account' => '4000-05',
                'min_amount' => 1000,
                'is_mandatory' => false,
                'frequency' => 'monthly',
                'color' => 'indigo',
                'icon' => 'globe',
                'is_active' => true,
            ],
            [
                'code' => 'YTH-01',
                'name' => 'Youth Ministry',
                'description' => 'Special fund for youth activities, seminars, and equipment.',
                'gl_account' => '4000-06',
                'min_amount' => 0,
                'is_mandatory' => false,
                'frequency' => 'one-time',
                'color' => 'red',
                'icon' => 'user-group',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            ContributionType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
