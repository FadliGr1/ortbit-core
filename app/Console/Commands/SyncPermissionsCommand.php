<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all Filament Resources and custom permissions, then sync them to the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting permission synchronization...');

        $resourcePath = app_path('Filament/Resources');
        $files = File::allFiles($resourcePath);
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // --- SINKRONISASI PERMISSION DARI RESOURCE ---
        $this->info('Scanning Filament Resources...');
        foreach ($files as $file) {
            if (Str::endsWith($file->getFilename(), 'Resource.php')) {
                // Dapatkan nama model dari nama resource, contoh: BrandResource -> brand
                $modelName = Str::lower(str_replace('Resource.php', '', $file->getFilename()));

                $permissions = [
                    'view_' . $modelName,
                    'view_any_' . $modelName,
                    'create_' . $modelName,
                    'update_' . $modelName,
                    'delete_' . $modelName,
                    'delete_any_' . $modelName,
                ];

                foreach ($permissions as $permissionName) {
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);
                    $this->line('  Ensuring permission: ' . $permissionName);

                    // Berikan semua permission ke Super Admin
                    $superAdminRole->givePermissionTo($permission);
                }
            }
        }
        $this->info('Resource permissions synchronized.');

        // --- TAMBAHAN: SINKRONISASI CUSTOM PERMISSION ---
        $this->info('Scanning for Custom Permissions...');
        $customPermissions = [
            'approve_leave',
            'reject_leave',
            'review_attendance_adjustment',
            
            // Tambahkan permission custom lainnya di sini jika ada
        ];

        foreach ($customPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $this->line('  Ensuring permission: ' . $permissionName);
            $superAdminRole->givePermissionTo($permission);
        }
        $this->info('Custom permissions synchronized.');


        $this->info('All permissions have been synchronized successfully.');
        $this->info('Super Admin role has been granted all permissions.');

        return 0;
    }
}
