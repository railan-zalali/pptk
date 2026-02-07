<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $tokenTime = $now->copy()->subDays(2);

        $users = [
            ['name' => 'Admin PPTK', 'email' => 'admin@pptk.test', 'role' => 'admin'],
            ['name' => 'Manajer Regional Barat', 'email' => 'manager.barat@pptk.test', 'role' => 'manager'],
            ['name' => 'Manajer Regional Timur', 'email' => 'manager.timur@pptk.test', 'role' => 'manager'],
            ['name' => 'Manajer Operasional Kebun', 'email' => 'manager.kebun@pptk.test', 'role' => 'manager'],
            ['name' => 'Viewer Eksekutif 01', 'email' => 'viewer01@pptk.test', 'role' => 'viewer'],
            ['name' => 'Petugas Kunjungan', 'email' => 'petugas.kunjungan@pptk.test', 'role' => 'viewer'],
        ];

        foreach ($users as $idx => $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'role' => $u['role'],
                'email_verified_at' => $now,
            ]);

            // Seed Password Reset Token
            DB::table('password_reset_tokens')->insert([
                'email' => $u['email'],
                'token' => Str::random(60),
                'created_at' => $tokenTime,
            ]);

            // Seed Session
            DB::table('sessions')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'ip_address' => '10.10.0.' . ($idx + 10),
                'user_agent' => 'SeededSession/1.0 (Laravel)',
                'payload' => base64_encode(json_encode(['user_id' => $user->id, 'email' => $u['email']])),
                'last_activity' => $now->timestamp - ($idx * 600),
            ]);
        }
    }
}
