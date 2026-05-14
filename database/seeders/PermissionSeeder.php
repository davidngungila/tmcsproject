<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            
            // Member management permissions
            ['name' => 'members.view', 'display_name' => 'View Members', 'module' => 'members'],
            ['name' => 'members.create', 'display_name' => 'Create Members', 'module' => 'members'],
            ['name' => 'members.edit', 'display_name' => 'Edit Members', 'module' => 'members'],
            ['name' => 'members.delete', 'display_name' => 'Delete Members', 'module' => 'members'],
            ['name' => 'members.import', 'display_name' => 'Import Members', 'module' => 'members'],
            ['name' => 'members.export', 'display_name' => 'Export Members', 'module' => 'members'],
            
            // Finance management permissions
            ['name' => 'finance.view', 'display_name' => 'View Finance', 'module' => 'finance'],
            ['name' => 'finance.create', 'display_name' => 'Record Contributions', 'module' => 'finance'],
            ['name' => 'finance.edit', 'display_name' => 'Edit Contributions', 'module' => 'finance'],
            ['name' => 'finance.delete', 'display_name' => 'Delete Contributions', 'module' => 'finance'],
            ['name' => 'finance.reports', 'display_name' => 'Generate Financial Reports', 'module' => 'finance'],
            ['name' => 'finance.receipts', 'display_name' => 'Generate Receipts', 'module' => 'finance'],
            
            // Group management permissions
            ['name' => 'groups.view', 'display_name' => 'View Groups', 'module' => 'groups'],
            ['name' => 'groups.create', 'display_name' => 'Create Groups', 'module' => 'groups'],
            ['name' => 'groups.edit', 'display_name' => 'Edit Groups', 'module' => 'groups'],
            ['name' => 'groups.delete', 'display_name' => 'Delete Groups', 'module' => 'groups'],
            ['name' => 'groups.assign_members', 'display_name' => 'Assign Members to Groups', 'module' => 'groups'],
            ['name' => 'groups.manage_own', 'display_name' => 'Manage Own Group', 'module' => 'groups'],
            
            // Communication permissions
            ['name' => 'communications.view', 'display_name' => 'View Communications', 'module' => 'communications'],
            ['name' => 'communications.create', 'display_name' => 'Send Communications', 'module' => 'communications'],
            ['name' => 'communications.schedule', 'display_name' => 'Schedule Communications', 'module' => 'communications'],
            ['name' => 'communications.broadcast', 'display_name' => 'Broadcast to All', 'module' => 'communications'],
            
            // Certificate management permissions
            ['name' => 'certificates.view', 'display_name' => 'View Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.create', 'display_name' => 'Generate Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.verify', 'display_name' => 'Verify Certificates', 'module' => 'certificates'],
            ['name' => 'certificates.revoke', 'display_name' => 'Revoke Certificates', 'module' => 'certificates'],
            
            // Event management permissions
            ['name' => 'events.view', 'display_name' => 'View Events', 'module' => 'events'],
            ['name' => 'events.create', 'display_name' => 'Create Events', 'module' => 'events'],
            ['name' => 'events.edit', 'display_name' => 'Edit Events', 'module' => 'events'],
            ['name' => 'events.delete', 'display_name' => 'Delete Events', 'module' => 'events'],
            ['name' => 'events.manage_attendance', 'display_name' => 'Manage Event Attendance', 'module' => 'events'],
            
            // Election management permissions
            ['name' => 'elections.view', 'display_name' => 'View Elections', 'module' => 'elections'],
            ['name' => 'elections.create', 'display_name' => 'Create Elections', 'module' => 'elections'],
            ['name' => 'elections.manage', 'display_name' => 'Manage Elections', 'module' => 'elections'],
            ['name' => 'elections.vote', 'display_name' => 'Vote in Elections', 'module' => 'elections'],
            ['name' => 'elections.results', 'display_name' => 'View Election Results', 'module' => 'elections'],
            
            // Asset management permissions
            ['name' => 'assets.view', 'display_name' => 'View Assets', 'module' => 'assets'],
            ['name' => 'assets.create', 'display_name' => 'Create Assets', 'module' => 'assets'],
            ['name' => 'assets.edit', 'display_name' => 'Edit Assets', 'module' => 'assets'],
            ['name' => 'assets.delete', 'display_name' => 'Delete Assets', 'module' => 'assets'],
            ['name' => 'assets.assign', 'display_name' => 'Assign Assets', 'module' => 'assets'],
            ['name' => 'assets.reports', 'display_name' => 'Generate Asset Reports', 'module' => 'assets'],
            
            // Shop management permissions
            ['name' => 'shop.view', 'display_name' => 'View Shop', 'module' => 'shop'],
            ['name' => 'shop.manage_products', 'display_name' => 'Manage Products', 'module' => 'shop'],
            ['name' => 'shop.sell', 'display_name' => 'Process Sales', 'module' => 'shop'],
            ['name' => 'shop.reports', 'display_name' => 'Generate Sales Reports', 'module' => 'shop'],
            
            // User management permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            ['name' => 'users.manage_roles', 'display_name' => 'Manage User Roles', 'module' => 'users'],
            
            // System permissions
            ['name' => 'system.activity_logs', 'display_name' => 'View Activity Logs', 'module' => 'system'],
            ['name' => 'system.settings', 'display_name' => 'System Settings', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            $permission['created_at'] = now();
            $permission['updated_at'] = now();
            \App\Models\Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
