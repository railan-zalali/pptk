<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed akun pengguna untuk sistem PPTK.
     *
     * Kredensial:
     *   Admin PPTK  : admin@pptk.test   / password
     *   Manajemen   : manajemen@pptk.test / password
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Admin PPTK',
                'email'             => 'admin@pptk.test',
                'password'          => Hash::make('password'),
                'role'              => 'admin_pptk',
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Manajemen Eksekutif',
                'email'             => 'manajemen@pptk.test',
                'password'          => Hash::make('password'),
                'role'              => 'manajemen',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('UserSeeder: 2 akun berhasil dibuat (admin_pptk & manajemen).');
    }
}
