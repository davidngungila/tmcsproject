<?php

use App\Models\Member;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Member to User synchronization...\n";

$members = Member::whereNotNull('email')->get();
$memberRole = Role::where('name', 'member')->first();

if (!$memberRole) {
    echo "Error: 'member' role not found. Please ensure roles are seeded.\n";
    exit(1);
}

$count = 0;
foreach ($members as $member) {
    // Get last name and capitalize it for the password
    $nameParts = explode(' ', trim($member->full_name));
    $lastName = end($nameParts);
    $password = strtoupper($lastName);

    // Find or create user
    $userData = [
        'name' => $member->full_name,
        'password' => Hash::make($password),
        'phone' => $member->phone,
    ];

    // Only add is_active if it exists in the model/table
    // (Based on error, it might be missing from the table)
    $user = User::updateOrCreate(
        ['email' => $member->email],
        $userData
    );

    // Assign 'member' role if not already assigned
    if (!$user->roles()->where('role_id', $memberRole->id)->exists()) {
        $user->roles()->attach($memberRole->id);
    }

    // Link member to user
    $member->update(['user_id' => $user->id]);

    echo "Synced: {$member->full_name} ({$member->email}) - Password set to: {$password}\n";
    $count++;
}

echo "Synchronization complete. Total members synced: {$count}\n";
