<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BackupPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // Create Backup Permissions
        // ==========================================
        
        $permissions = [
            // Backup Settings
            'backup.settings.view',
            'backup.settings.update',
            
            // Google Drive
            'backup.google-drive.connect',
            'backup.google-drive.test',
            
            // Backup Execution
            'backup.run',
            
            // Schedules
            'backup.schedules.view',
            'backup.schedules.create',
            'backup.schedules.edit',
            'backup.schedules.delete',
            'backup.schedules.run',
            
            // History
            'backup.history.view',
            'backup.download',
            'backup.restore',
            'backup.history.delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $this->command->info('✅ Backup permissions created successfully');

        // ==========================================
        // Assign Permissions to Roles
        // ==========================================
        
        // Super Admin - All permissions
        $superAdmin = Role::findByName('super_admin', 'web');
        $superAdmin->givePermissionTo(Permission::where('name', 'like', 'backup.%')->get());
        
        // Admin - All backup permissions
        $admin = Role::findByName('admin', 'web');
        $admin->givePermissionTo(Permission::where('name', 'like', 'backup.%')->get());

        $this->command->info('✅ Backup permissions assigned to roles successfully');

        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->newLine();
        $this->command->info('✅ Backup permissions seeded successfully!');
    }
}

