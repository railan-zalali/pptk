<?php

namespace Database\Seeders;

use App\Models\Garden;
use App\Models\Visit;
use App\Models\CommunityService;
use App\Models\Insight;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ResearchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Visits
        $gardens = Garden::all();
        $purposes = ['Studi Banding', 'Penelitian Mahasiswa', 'Monitoring Dinas', 'Wisata Edukasi'];
        $statuses = ['scheduled', 'completed', 'cancelled'];

        foreach ($gardens as $garden) {
            for ($i = 0; $i < 10; $i++) {
                $date = Carbon::create(2026, rand(1, 12), rand(1, 28));
                Visit::create([
                    'garden_id' => $garden->id,
                    'visit_date' => $date,
                    'visitor_name' => 'Dr. Peneliti ' . rand(1, 100),
                    'purpose' => $purposes[array_rand($purposes)],
                    'status' => $date->isPast() ? 'completed' : 'scheduled',
                    'title' => 'Kunjungan ' . ($i + 1), // Assuming field exists
                    'participants_list' => 'Peserta A, Peserta B', // Assuming field exists
                    'description' => 'Deskripsi detail kunjungan.',
                ]);
            }
        }

        // 2. Community Services
        $activities = [
            'Pelatihan Pemangkasan Teh',
            'Sosialisasi Pupuk Organik',
            'Workshop Pengolahan Pascapanen',
            'Pemberdayaan Petani Milenial',
        ];

        foreach ($activities as $idx => $act) {
            CommunityService::create([
                'activity_name' => $act,
                'team_name' => 'Tim PPTK ' . ($idx + 1),
                'total_budget' => rand(50, 200) * 1000000,
                'remaining_budget' => rand(10, 50) * 1000000,
                'year' => 2026,
                'description' => 'Kegiatan pengabdian masyarakat di desa binaan.',
                'location' => 'Desa Binaan ' . ($idx + 1),
            ]);
        }

        // 3. Insights
        $alertLevels = ['low', 'medium', 'high'];
        foreach ($gardens as $garden) {
            Insight::create([
                'garden_id' => $garden->id,
                'insight_type' => 'productivity',
                'title' => 'Analisis Produktivitas ' . $garden->kebun_name,
                'description' => 'Evaluasi hasil produksi teh pada kebun ini menunjukkan tren tertentu.',
                'message' => 'Pesan indikator tingkat produktivitas.',
                'alert_level' => $alertLevels[array_rand($alertLevels)],
                'recommendations' => ['Optimalkan pemupukan', 'Pantau kelembaban tanah'],
            ]);
        }
    }
}
