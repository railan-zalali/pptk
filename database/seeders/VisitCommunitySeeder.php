<?php

namespace Database\Seeders;

use App\Models\CommunityService;
use App\Models\Garden;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VisitCommunitySeeder extends Seeder
{
    /**
     * Seed data kunjungan dinas dan pengabdian masyarakat.
     *
     * Kunjungan: 5 per kebun (2024–2025)
     * Pengabdian Masyarakat: 4 kegiatan (global, tidak per kebun)
     */
    public function run(): void
    {
        $gardens = Garden::all();

        // ─── 1. Kunjungan Dinas ───────────────────────────────────────────
        $purposes = [
            'Studi Banding Teknologi Budidaya',
            'Penelitian dan Pengembangan Varietas',
            'Monitoring Kinerja Kebun',
            'Wisata Edukasi Pertanian',
            'Audit Mutu dan Kualitas Produksi',
        ];
        $visitorNames = [
            'Dr. Agus Santoso (Universitas Padjadjaran)',
            'Prof. Dr. Sri Wahyuni (IPB University)',
            'Ir. Budi Hartono, M.Sc. (Dinas Pertanian Jabar)',
            'Dr. Ratna Dewi (Balai Penelitian Tanaman Industri)',
            'Tim Peneliti Universitas Gadjah Mada',
            'Delegasi Dinas Pertanian Jawa Tengah',
            'Ir. Ahmad Fauzi, M.Eng. (PTPN VIII)',
        ];

        $visitCount = 0;
        foreach ($gardens as $garden) {
            for ($i = 0; $i < 5; $i++) {
                $year    = 2024 + rand(0, 1);
                $month   = rand(1, 12);
                $day     = rand(1, 28);
                $date    = Carbon::create($year, $month, $day);
                $purpose = $purposes[$i % count($purposes)];

                Visit::create([
                    'garden_id'        => $garden->id,
                    'visit_date'       => $date,
                    'visitor_name'     => $visitorNames[array_rand($visitorNames)],
                    'purpose'          => $purpose,
                    'status'           => $date->isPast() ? 'completed' : 'scheduled',
                    'title'            => "Kunjungan ke {$garden->kebun_name} — " . $date->format('M Y'),
                    'participants_list'=> rand(3, 20) . ' peserta',
                    'description'      => "Kunjungan dalam rangka {$purpose} di {$garden->kebun_name}.",
                ]);
                $visitCount++;
            }
        }

        // ─── 2. Pengabdian Masyarakat ─────────────────────────────────────
        $communityActivities = [
            [
                'activity_name' => 'Pelatihan Pemangkasan dan Perawatan Tanaman Teh',
                'team_name'     => 'Tim PPTK Bidang Budidaya',
                'description'   => 'Pelatihan intensif teknik pemangkasan untuk meningkatkan produktivitas tanaman teh bagi petani sekitar kebun.',
                'location'      => 'Desa Ciwidey, Bandung',
                'year'          => 2024,
                'total_budget'  => 75_000_000,
                'remaining_budget' => 5_250_000,
            ],
            [
                'activity_name' => 'Sosialisasi Penggunaan Pupuk Organik Berkelanjutan',
                'team_name'     => 'Tim PPTK Bidang Lingkungan',
                'description'   => 'Program sosialisasi dan demonstrasi pembuatan pupuk organik dari limbah kebun untuk pertanian berkelanjutan.',
                'location'      => 'Desa Pangalengan, Bandung',
                'year'          => 2024,
                'total_budget'  => 50_000_000,
                'remaining_budget' => 3_100_000,
            ],
            [
                'activity_name' => 'Workshop Pengolahan Pascapanen Teh Hijau',
                'team_name'     => 'Tim PPTK Bidang Teknologi Pengolahan',
                'description'   => 'Workshop tentang teknik pengolahan teh hijau yang benar untuk meningkatkan kualitas produk dan nilai jual.',
                'location'      => 'Desa Kertasari, Wonosobo',
                'year'          => 2025,
                'total_budget'  => 120_000_000,
                'remaining_budget' => 18_500_000,
            ],
            [
                'activity_name' => 'Pemberdayaan Petani Milenial dalam Agroteknologi Teh',
                'team_name'     => 'Tim PPTK Bidang Pengembangan Masyarakat',
                'description'   => 'Program mentoring, pelatihan kewirausahaan, dan digitalisasi pertanian bagi generasi muda petani teh.',
                'location'      => 'Desa Cipanas, Cianjur',
                'year'          => 2025,
                'total_budget'  => 95_000_000,
                'remaining_budget' => 22_300_000,
            ],
        ];

        foreach ($communityActivities as $act) {
            CommunityService::create($act);
        }

        $this->command->info(sprintf(
            'VisitCommunitySeeder: %d kunjungan dan %d pengabdian masyarakat berhasil di-seed.',
            $visitCount, count($communityActivities)
        ));
    }
}
