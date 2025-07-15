<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        $usersToCreate = [
            // ... (array usersToCreate Anda tetap sama)
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin',
                'role' => 'admin',
            ],
            [
                'name' => 'siswa',
                'email' => 'siswa@example.com',
                'password' => 'siswa',
                'role' => 'siswa',
            ],
            [
                'name' => 'guru',
                'email' => 'guru@example.com',
                'password' => 'guru',
                'role' => 'guru',
            ],
            [
                'name' => 'perusahaan',
                'email' => 'perusahaan@example.com',
                'password' => 'perusahaan',
                'role' => 'perusahaan',
            ],
            [
                'name' => 'waka_kurikulum',
                'email' => 'waka_kurikulum@example.com',
                'password' => 'waka_kurikulum',
                'role' => 'waka_kurikulum',
            ],
            [
                'name' => 'waka_humas',
                'email' => 'waka_humas@example.com',
                'password' => 'waka_humas',
                'role' => 'waka_humas',
            ],
            [
                'name' => 'alumni',
                'email' => 'alumni@example.com',
                'password' => 'alumni',
                'role' => 'alumni',
            ],
            [
                'name' => 'lsp',
                'email' => 'lsp@example.com',
                'password' => 'lsp',
                'role' => 'lsp',
            ],
            [
                'name' => 'alifian',
                'email' => 'alifian@example.com',
                'password' => 'alifian',
                'role' => 'perusahaan',
            ],
            [
                'name' => 'viantech',
                'email' => 'viantech@example.com',
                'password' => 'viantech',
                'role' => 'perusahaan',
            ],
            [
                'name' => 'guru_produktif',
                'email' => 'produktif@example.com',
                'password' => 'produktif',
                'role' => 'guru',
                'jenis_guru' => 'guru-produktif', 
            ],
        ];

        foreach ($usersToCreate as $userData) {
            if (! User::where('email', $userData['email'])->exists()) {
                
                // ===============================================
                // LOGIKA YANG DIPERBAIKI ADA DI SINI
                // ===============================================

                // 1. Siapkan data dasar yang selalu ada
                $userCreateData = [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ];

                // 2. Gunakan isset() untuk memeriksa apakah 'jenis_guru' ada
                // Jika ada, tambahkan ke array data.
                if (isset($userData['jenis_guru'])) {
                    $userCreateData['jenis_guru'] = $userData['jenis_guru'];
                }

                // 3. Buat user dengan data yang sudah disiapkan (hanya satu kali pemanggilan)
                $user = User::create($userCreateData);

                // ===============================================
                // AKHIR PERBAIKAN LOGIKA
                // ===============================================
                
                $user->assignRole($userData['role']);

                $this->command->info("User '{$userData['email']}' created and assigned role '{$userData['role']}'.");

            } else {
                $this->command->warn("User '{$userData['email']}' already exists. Skipping creation.");

                // Logika untuk assign role ke user yang sudah ada (opsional, tapi baik untuk dimiliki)
                $existingUser = User::where('email', $userData['email'])->first();
                if ($existingUser && ! $existingUser->hasRole($userData['role'])) {
                    $existingUser->assignRole($userData['role']);
                    $this->command->info("Assigned role '{$userData['role']}' to existing user '{$userData['email']}'.");
                }
            }
        }
    }
}