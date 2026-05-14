<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Fr. John Chaplain',
                'email' => 'chaplain@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567890',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'chaplain',
            ],
            [
                'name' => 'Mary Chairperson',
                'email' => 'chairperson@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567891',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'chairperson',
            ],
            [
                'name' => 'James Secretary',
                'email' => 'secretary@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567892',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'secretary',
            ],
            [
                'name' => 'Peter Accountant',
                'email' => 'accountant@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567893',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'accountant',
            ],
            [
                'name' => 'Sarah Group Leader',
                'email' => 'groupleader@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567894',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'groupleader',
            ],
            [
                'name' => 'John Member',
                'email' => 'member@tmcssmart.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567895',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'member',
            ],
        ];

        foreach ($users as $userData) {
            $user = \App\Models\User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role to user
            $role = \App\Models\Role::where('name', $userData['role'])->first();
            if ($role && !$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
        }
    }
}
