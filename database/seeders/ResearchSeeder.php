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
        $purposes = [
            'Studi Banding Teknologi Budidaya',
            'Penelitian Mahasiswa',
            'Monitoring Kinerja Kebun',
            'Wisata Edukasi Pertanian',
            'Audit Kualitas Produksi'
        ];
        $visitorNames = [
            'Dr. Agus Santoso',
            'Prof. Dr. Sri Wahyuni',
            'Ir. Budi Hartono, M.Sc.',
            'Dr. Ratna Dewi',
            'Ir. Ahmad Fauzi, M.Eng.',
            'Tim Peneliti Universitas Padjadjaran',
            'Tim Dinas Pertanian Jawa Barat'
        ];

        foreach ($gardens as $garden) {
            for ($i = 0; $i < 8; $i++) {
                $date = Carbon::create(2024 + rand(0, 1), rand(1, 12), rand(1, 28));
                Visit::create([
                    'garden_id' => $garden->id,
                    'visit_date' => $date,
                    'visitor_name' => $visitorNames[array_rand($visitorNames)],
                    'purpose' => $purposes[array_rand($purposes)],
                    'status' => $date->isPast() ? 'completed' : 'scheduled',
                    'title' => 'Kunjungan ke ' . $garden->kebun_name . ' - #' . ($i + 1),
                    'participants_list' => 'Peserta ' . ($i + 2) . ' orang',
                    'description' => 'Kunjungan dengan tujuan untuk ' . strtolower($purposes[array_rand($purposes)]),
                ]);
            }
        }

        // 2. Community Services
        $activities = [
            [
                'activity_name' => 'Pelatihan Pemangkasan dan Perawatan Tanaman Teh',
                'team_name' => 'Tim PPTK Bidang Budidaya',
                'description' => 'Pelatihan intensif tentang teknik pemangkasan untuk meningkatkan produktivitas tanaman teh',
                'location' => 'Desa Ciwidey, Bandung'
            ],
            [
                'activity_name' => 'Sosialisasi Penggunaan Pupuk Organik Berkelanjutan',
                'team_name' => 'Tim PPTK Bidang Lingkungan',
                'description' => 'Program sosialisasi dan demo pembuatan pupuk organik dari limbah kebun',
                'location' => 'Desa Pangalengan, Bandung'
            ],
            [
                'activity_name' => 'Workshop Pengolahan Pascapanen Teh Hijau',
                'team_name' => 'Tim PPTK Bidang Teknologi',
                'description' => 'Workshop tentang teknik pengolahan teh hijau untuk meningkatkan kualitas produk',
                'location' => 'Desa Kertasari, Wonosobo'
            ],
            [
                'activity_name' => 'Pemberdayaan Petani Milenial dalam Agroteknologi',
                'team_name' => 'Tim PPTK Bidang Pengembangan Masyarakat',
                'description' => 'Program mentoring dan pelatihan kewirausahaan bagi petani muda',
                'location' => 'Desa Cipanas, Cianjur'
            ],
        ];

        foreach ($activities as $idx => $act) {
            $totalBudget = (50 + rand(0, 150)) * 1000000;
            $remainingBudget = $totalBudget * (rand(5, 20) / 100);
            CommunityService::create([
                'activity_name' => $act['activity_name'],
                'team_name' => $act['team_name'],
                'total_budget' => $totalBudget,
                'remaining_budget' => $remainingBudget,
                'year' => 2024 + $idx,
                'description' => $act['description'],
                'location' => $act['location'],
            ]);
        }

        // 3. Insights (Generated dynamically via Rule Engine)
        $insightService = resolve(\App\Services\InsightService::class);
        foreach ($gardens as $garden) {
            $insightService->generateAllInsightsForGarden($garden->id, 2024);
            $insightService->generateAllInsightsForGarden($garden->id, 2025);
        }
        
        $this->command->info('ResearchSeeder: Data kunjungan, pengabdian masyarakat, dan insight berhasil di-seed dengan detail realistis.');
    }
}
