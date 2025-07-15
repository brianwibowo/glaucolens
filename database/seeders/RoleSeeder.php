<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Daftar peran yang ingin Anda buat
        $roles = [
            'admin',
            'waka_kurikulum',
            'perusahaan',
            'siswa',
            'guru',
            'waka_humas',
            'alumni',
            'lsp',
            'user',
        ];

        // Loop melalui setiap peran dan buat jika belum ada
        foreach ($roles as $roleName) {
            // Periksa apakah peran sudah ada untuk guard 'web'
            if (! Role::where('name', $roleName)->where('guard_name', 'web')->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
                $this->command->info("Role '{$roleName}' created successfully."); // Opsional: untuk debug
            } else {
                $this->command->warn("Role '{$roleName}' already exists. Skipping creation."); // Opsional: untuk debug
            }
        }
    }
}