<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get all permissions
        $allPermissions = Permission::all();

        // 2. Assign ALL permissions to Chaplain (Super Admin)
        $chaplain = Role::where('name', 'chaplain')->first();
        if ($chaplain) {
            $chaplain->permissions()->sync($allPermissions->pluck('id'));
        }

        // 3. Chairperson Permissions
        $chairperson = Role::where('name', 'chairperson')->first();
        if ($chairperson) {
            $chairpersonPerms = Permission::whereIn('module', ['dashboard', 'members', 'groups', 'events', 'elections', 'certificates'])
                ->orWhere('name', 'finance.view')
                ->orWhere('name', 'finance.reports')
                ->pluck('id');
            $chairperson->permissions()->sync($chairpersonPerms);
        }

        // 4. Secretary Permissions
        $secretary = Role::where('name', 'secretary')->first();
        if ($secretary) {
            $secretaryPerms = Permission::whereIn('module', ['dashboard', 'members', 'communications', 'certificates', 'events'])
                ->pluck('id');
            $secretary->permissions()->sync($secretaryPerms);
        }

        // 5. Accountant Permissions
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $accountantPerms = Permission::whereIn('module', ['dashboard', 'finance', 'assets', 'shop'])
                ->pluck('id');
            $accountant->permissions()->sync($accountantPerms);
        }

        // 6. Group Leader Permissions
        $groupLeader = Role::where('name', 'groupleader')->first();
        if ($groupLeader) {
            $groupLeaderPerms = Permission::whereIn('name', [
                'dashboard.view',
                'groups.view',
                'groups.manage_own',
                'members.view',
                'events.view',
            ])->pluck('id');
            $groupLeader->permissions()->sync($groupLeaderPerms);
        }
    }
}
