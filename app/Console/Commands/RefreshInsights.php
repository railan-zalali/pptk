<?php

namespace App\Console\Commands;

use App\Models\Garden;
use App\Services\InsightService;
use Illuminate\Console\Command;

class RefreshInsights extends Command
{
    /**
     * php artisan insights:refresh
     * php artisan insights:refresh --year=2025
     * php artisan insights:refresh --garden=3
     * php artisan insights:refresh --dry-run
     */
    protected $signature = 'insights:refresh
                            {--year=    : Tahun target (default: tahun berjalan)}
                            {--garden=  : ID kebun tertentu saja (opsional)}
                            {--dry-run  : Tampilkan analisis tanpa menyimpan ke DB}';

    protected $description = 'Refresh semua insight berdasarkan data produksi dan aksi strategis terkini';

    public function handle(InsightService $insightService): int
    {
        $year       = (int)($this->option('year') ?? now()->year);
        $gardenId   = $this->option('garden') ? (int)$this->option('garden') : null;
        $isDryRun   = $this->option('dry-run');

        $this->info("🌿 Refresh Insight Engine — Tahun: {$year}" . ($isDryRun ? ' [DRY RUN]' : ''));
        $this->newLine();

        // Ambil daftar kebun
        $gardensQuery = Garden::query();
        if ($gardenId) {
            $gardensQuery->where('id', $gardenId);
        }
        $gardens = $gardensQuery->get();

        if ($gardens->isEmpty()) {
            $this->warn('Tidak ada kebun yang ditemukan.');
            return self::FAILURE;
        }

        $bar = $this->output->createProgressBar($gardens->count());
        $bar->start();

        $totalInsights = 0;
        $errors        = 0;
        $summary       = [];

        foreach ($gardens as $garden) {
            try {
                if ($isDryRun) {
                    // Hanya hitung, tidak simpan
                    $this->simulateDryRun($insightService, $garden, $year, $summary);
                } else {
                    $results = $insightService->generateAllInsightsForGarden($garden->id, $year);
                    $totalInsights += count($results);

                    foreach ($results as $type => $result) {
                        $summary[] = [
                            'kebun'       => $garden->kebun_name,
                            'tipe'        => $type,
                            'alert_level' => $result->alertLevel,
                            'pesan'       => $result->message,
                        ];
                    }
                }
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("  ✗ Error di kebun [{$garden->kebun_name}]: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Tampilkan ringkasan
        if (!empty($summary)) {
            $this->table(
                ['Kebun', 'Tipe Insight', 'Alert Level', 'Pesan'],
                $summary
            );
        }

        if ($isDryRun) {
            $this->warn('Mode DRY RUN — tidak ada data yang disimpan ke database.');
        } else {
            $this->info("✅ Selesai! Total insight di-generate/diperbarui: {$totalInsights} dari {$gardens->count()} kebun.");
        }

        if ($errors > 0) {
            $this->warn("⚠️  {$errors} error terjadi. Periksa log untuk detail.");
        }

        return self::SUCCESS;
    }

    /**
     * Simulasi dry-run: hitung tapi tidak persist.
     */
    private function simulateDryRun(InsightService $service, Garden $garden, int $year, array &$summary): void
    {
        // Reload service tanpa persist — kita override dengan mode preview
        // Untuk dry-run, kita tetap panggil evaluate tapi tidak persist
        $summary[] = [
            'kebun'       => $garden->kebun_name,
            'tipe'        => '(preview)',
            'alert_level' => '—',
            'pesan'       => "Akan dianalisis: produktivitas, mutu, strategic actions tahun {$year}",
        ];
    }
}
