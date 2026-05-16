<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;

class SimpleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Roles exist (or assume they do from RoleSeeder)
        
        // 2. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@tmcssmart.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        
        // Ensure 'chaplain' (Super Admin) role exists
        $adminRole = Role::firstOrCreate(
            ['name' => 'chaplain'],
            ['display_name' => 'Chaplain', 'description' => 'Full system access', 'is_active' => true]
        );

        // Assign Chaplain role to Admin User
        if (!$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }

        // 3. Create Chaplain User
        $chaplain = User::updateOrCreate(
            ['email' => 'chaplain@tmcssmart.com'],
            [
                'name' => 'Fr. John Chaplain',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        if ($adminRole && !$chaplain->roles()->where('role_id', $adminRole->id)->exists()) {
            $chaplain->roles()->attach($adminRole->id);
        }

        // 4. Create Member User
        $member = User::updateOrCreate(
            ['email' => 'member@tmcssmart.com'],
            [
                'name' => 'John Member',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $memberRole = Role::where('name', 'member')->first();
        if ($memberRole && !$member->roles()->where('role_id', $memberRole->id)->exists()) {
            $member->roles()->attach($memberRole->id);
        }

        $this->command->info('Sample users created and roles assigned successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: admin@tmcssmart.com | Password: password (Role: Chaplain)');
        $this->command->info('Email: chaplain@tmcssmart.com | Password: password (Role: Chaplain)');
        $this->command->info('Email: member@tmcssmart.com | Password: password (Role: Member)');
    }
}
