<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class BasicRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'is_active' => true],
            ['name' => 'chaplain', 'display_name' => 'Chaplain', 'is_active' => true],
            ['name' => 'chairperson', 'display_name' => 'Chairperson', 'is_active' => true],
            ['name' => 'secretary', 'display_name' => 'Secretary', 'is_active' => true],
            ['name' => 'accountant', 'display_name' => 'Accountant', 'is_active' => true],
            ['name' => 'groupleader', 'display_name' => 'Group Leader', 'is_active' => true],
            ['name' => 'member', 'display_name' => 'Member', 'is_active' => true],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(['name' => $roleData['name']], $roleData);
        }

        // Create basic permissions
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            
            // Members
            ['name' => 'members.view', 'display_name' => 'View Members', 'module' => 'members'],
            ['name' => 'members.create', 'display_name' => 'Create Members', 'module' => 'members'],
            ['name' => 'members.edit', 'display_name' => 'Edit Members', 'module' => 'members'],
            ['name' => 'members.delete', 'display_name' => 'Delete Members', 'module' => 'members'],
            
            // Finance
            ['name' => 'finance.view', 'display_name' => 'View Finance', 'module' => 'finance'],
            ['name' => 'finance.create', 'display_name' => 'Create Contributions', 'module' => 'finance'],
            ['name' => 'finance.edit', 'display_name' => 'Edit Contributions', 'module' => 'finance'],
            ['name' => 'finance.delete', 'display_name' => 'Delete Contributions', 'module' => 'finance'],
            
            // Groups
            ['name' => 'groups.view', 'display_name' => 'View Groups', 'module' => 'groups'],
            ['name' => 'groups.create', 'display_name' => 'Create Groups', 'module' => 'groups'],
            ['name' => 'groups.edit', 'display_name' => 'Edit Groups', 'module' => 'groups'],
            ['name' => 'groups.delete', 'display_name' => 'Delete Groups', 'module' => 'groups'],
            
            // Communications
            ['name' => 'communications.view', 'display_name' => 'View Communications', 'module' => 'communications'],
            ['name' => 'communications.create', 'display_name' => 'Create Communications', 'module' => 'communications'],
            ['name' => 'communications.edit', 'display_name' => 'Edit Communications', 'module' => 'communications'],
            ['name' => 'communications.delete', 'display_name' => 'Delete Communications', 'module' => 'communications'],
            
            // Certificates
            ['name' => 'certificates.view', 'display_name' => 'View Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.create', 'display_name' => 'Create Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.edit', 'display_name' => 'Edit Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.delete', 'display_name' => 'Delete Certificates', 'module' => 'certificates'],
            
            // Events
            ['name' => 'events.view', 'display_name' => 'View Events', 'module' => 'events'],
            ['name' => 'events.create', 'display_name' => 'Create Events', 'module' => 'events'],
            ['name' => 'events.edit', 'display_name' => 'Edit Events', 'module' => 'events'],
            ['name' => 'events.delete', 'display_name' => 'Delete Events', 'module' => 'events'],
            
            // Elections
            ['name' => 'elections.view', 'display_name' => 'View Elections', 'module' => 'elections'],
            ['name' => 'elections.create', 'display_name' => 'Create Elections', 'module' => 'elections'],
            ['name' => 'elections.edit', 'display_name' => 'Edit Elections', 'module' => 'elections'],
            ['name' => 'elections.delete', 'display_name' => 'Delete Elections', 'module' => 'elections'],
            
            // Assets
            ['name' => 'assets.view', 'display_name' => 'View Assets', 'module' => 'assets'],
            ['name' => 'assets.create', 'display_name' => 'Create Assets', 'module' => 'assets'],
            ['name' => 'assets.edit', 'display_name' => 'Edit Assets', 'module' => 'assets'],
            ['name' => 'assets.delete', 'display_name' => 'Delete Assets', 'module' => 'assets'],
            
            // Shop
            ['name' => 'shop.view', 'display_name' => 'View Shop', 'module' => 'shop'],
            ['name' => 'shop.create', 'display_name' => 'Create Products', 'module' => 'shop'],
            ['name' => 'shop.edit', 'display_name' => 'Edit Products', 'module' => 'shop'],
            ['name' => 'shop.delete', 'display_name' => 'Delete Products', 'module' => 'shop'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(['name' => $permissionData['name']], $permissionData);
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        $allPermissions = Permission::all();
        
        if ($adminRole) {
            foreach ($allPermissions as $permission) {
                $adminRole->permissions()->syncWithoutDetaching($permission->id);
            }
        }

        // Assign basic permissions to member role
        $memberRole = Role::where('name', 'member')->first();
        if ($memberRole) {
            $basicPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'members.view',
                'finance.view',
                'groups.view',
                'communications.view',
                'certificates.view',
                'events.view',
                'elections.view',
                'assets.view',
                'shop.view'
            ])->get();
            
            foreach ($basicPermissions as $permission) {
                $memberRole->permissions()->syncWithoutDetaching($permission->id);
            }
        }

        // Assign admin role to admin user
        $adminUser = \App\Models\User::where('email', 'admin@tmcssmart.com')->first();
        if ($adminUser && $adminRole) {
            $adminUser->roles()->syncWithoutDetaching($adminRole->id);
        }

        // Assign member role to member user
        $memberRole = Role::where('name', 'member')->first();
        $memberUser = \App\Models\User::where('email', 'member@tmcssmart.com')->first();
        if ($memberUser && $memberRole) {
            $memberUser->roles()->syncWithoutDetaching($memberRole->id);
        }

        $this->command->info('Basic roles and permissions created successfully!');
    }
}
