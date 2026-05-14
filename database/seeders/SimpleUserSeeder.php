<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a simple test user
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@tmcssmart.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create additional test users
        $testUsers = [
            [
                'email' => 'chaplain@tmcssmart.com',
                'name' => 'Fr. John Chaplain',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'email' => 'member@tmcssmart.com',
                'name' => 'John Member',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($testUsers as $userData) {
            \App\Models\User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: admin@tmcssmart.com | Password: password');
        $this->command->info('Email: chaplain@tmcssmart.com | Password: password');
        $this->command->info('Email: member@tmcssmart.com | Password: password');
    }
}
