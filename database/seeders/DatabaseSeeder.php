<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // 1. Panggil perintah untuk membuat semua permissions dan peran Super Admin
        Artisan::call('app:sync-permissions');
        $this->command->info('Permissions and Super Admin role synced.');

        // 2. Buat pengguna Super Admin default
        $user = User::firstOrCreate(
            ['email' => 'fadligrr@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'), // Ganti dengan password yang aman
            ]
        );
        $this->command->info('Default Super Admin user created.');

        // 3. Temukan peran Super Admin
        $superAdminRole = Role::findByName('Super Admin');

        // 4. Berikan peran Super Admin ke pengguna
        if ($user && $superAdminRole) {
            $user->assignRole($superAdminRole);
            $this->command->info('Super Admin role assigned to the default user.');
        }
    }
}
