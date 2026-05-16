<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'System administrator with full access to all settings and configurations',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'chaplain',
                'display_name' => 'Chaplain',
                'description' => 'Full access to all modules and features',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'chairperson',
                'display_name' => 'Chairperson',
                'description' => 'Read-only on finances, manage events, verify certificates, manage elections',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'secretary',
                'display_name' => 'Secretary',
                'description' => 'Register members, manage member records, issue certificates, manage communications',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Full finance module access, no member management',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'groupleader',
                'display_name' => 'Community/Spiritual Group Leader',
                'description' => 'Manage only assigned group members and activities',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'member',
                'display_name' => 'Member (Self-service)',
                'description' => 'View own profile, contributions, certificates, verify QR codes',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
