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
            ['name' => 'Admin PPKT', 'email' => 'admin@pptk.test', 'role' => 'admin_ppkt'],
            ['name' => 'Manajemen Eksekutif', 'email' => 'manajemen@pptk.test', 'role' => 'manajemen'],
            ['name' => 'Manajemen Regional Barat', 'email' => 'manajemen.barat@pptk.test', 'role' => 'manajemen'],
            ['name' => 'Manajemen Regional Timur', 'email' => 'manajemen.timur@pptk.test', 'role' => 'manajemen'],
            ['name' => 'Manajemen Operasional', 'email' => 'manajemen.ops@pptk.test', 'role' => 'manajemen'],
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
