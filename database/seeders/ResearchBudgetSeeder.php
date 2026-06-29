<?php

namespace Database\Seeders;

use App\Models\ResearchBudget;
use App\Models\ResearchBudgetBalance;
use Illuminate\Database\Seeder;

class ResearchBudgetSeeder extends Seeder
{
    /**
     * Seed data anggaran penelitian 2025.
     *
     * Data diambil dari "Realisasi Anggaran Penelitian 2025.xlsx":
     *   - Saldo Awal  : kolom D, baris 5–14
     *   - Income      : baris 19–28 (pencairan per bulan)
     *   - Pengeluaran : baris 32–41 (pengeluaran per bulan)
     *
     * Hanya baris yang memiliki income > 0 ATAU pengeluaran > 0 yang di-insert.
     */
    public function run(): void
    {
        $year = 2025;

        // ─── Saldo Awal (Opening Balance) ─────────────────────────────────
        $openingBalances = [
            'Kina'             => 9_460_533,   // 11560533 - 2100000
            'KUT'              => 19_231_936,
            'Malabar/Sedep'    => 39_568_543,
            'Drone'            => 0,
            'GWP'              => 0,
            'IPM Helopeltis'   => 0,
            'Bio Kompos'       => 0,
            'Daur Petik'       => 0,
            'Inkubasi Riset'   => 26_437_171,
            'Inkubasi Booster' => 0,
        ];

        foreach ($openingBalances as $activity => $balance) {
            ResearchBudgetBalance::updateOrCreate(
                ['activity_name' => $activity, 'year' => $year],
                ['opening_balance' => $balance]
            );
        }

        // ─── Income (Pencairan Dana) per bulan ────────────────────────────
        //     Index: [Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec]
        $incomes = [
            'Kina'             => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'KUT'              => [0, 0, 64_799_300, 0, 0, 0, 0, 0, 0, 73_711_511, 0, 0],
            'Malabar/Sedep'    => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'Drone'            => [0, 0, 0, 0, 0, 25_243_000, 0, 0, 0, 0, 0, 0],
            'GWP'              => [0, 0, 0, 0, 0, 25_400_000, 0, 0, 0, 0, 0, 0],
            'IPM Helopeltis'   => [0, 0, 0, 0, 0, 42_200_000, 0, 0, 0, 0, 0, 0],
            'Bio Kompos'       => [0, 0, 0, 0, 0, 15_867_000, 0, 0, 0, 0, 0, 0],
            'Daur Petik'       => [0, 0, 0, 0, 0, 28_950_000, 0, 0, 0, 0, 0, 0],
            'Inkubasi Riset'   => [0, 89_275_000, 0, 0, 0, 84_783_000, 0, 0, 0, 0, 0, 0],
            'Inkubasi Booster' => [0, 0, 0, 0, 0, 0, 0, 54_000_000, 0, 0, 0, 0],
        ];

        // ─── Pengeluaran per bulan ─────────────────────────────────────────
        $expenditures = [
            'Kina'             => [4_859_400, 2_612_000, 3_511_000, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'KUT'              => [4_091_030, 6_291_930, 0, 18_109_000, 31_948_591, 13_445_000, 7_186_000, 3_692_250, 0, 7_740_750, 26_067_100, 38_326_694],
            'Malabar/Sedep'    => [10_810_220, 3_719_650, 459_000, 1_658_500, 688_500, 1_557_000, 1_476_500, 1_787_716, 1_797_077, 3_024_000, 8_849_258, 1_253_750],
            'Drone'            => [0, 0, 0, 0, 0, 0, 1_490_500, 2_337_500, 0, 7_000_000, 7_398_000, 63_508],
            'GWP'              => [0, 0, 0, 0, 0, 0, 12_569_228, 275_000, 3_000_000, 3_704_776, 0, 0],
            'IPM Helopeltis'   => [0, 0, 0, 0, 0, 0, 18_298_500, 1_636_750, 6_085_000, 4_322_520, 254_000, 4_825_230],
            'Bio Kompos'       => [0, 0, 0, 0, 0, 0, 7_664_561, 80_000, 2_206_000, 0, 725_000, 0],
            'Daur Petik'       => [0, 0, 0, 0, 0, 0, 4_479_816, 1_205_750, 11_674_997, 5_674_800, 2_055_250, 1_156_820],
            'Inkubasi Riset'   => [575_418, 14_110_269, 8_898_983, 51_297_189, 5_561_118, 7_638_362, 27_608_698, 13_145_981, 2_473_150, 16_695_550, 1_382_750, 31_991_181],
            'Inkubasi Booster' => [0, 0, 0, 0, 0, 0, 0, 0, 4_184_500, 0, 1_886_500, 201_275],
        ];

        $inserted = 0;
        foreach ($incomes as $activity => $monthlyIncomes) {
            for ($month = 1; $month <= 12; $month++) {
                $inc = $monthlyIncomes[$month - 1];
                $exp = $expenditures[$activity][$month - 1] ?? 0;

                if ($inc > 0 || $exp > 0) {
                    ResearchBudget::updateOrCreate(
                        ['activity_name' => $activity, 'year' => $year, 'month' => $month],
                        ['income' => $inc, 'expenditure' => $exp]
                    );
                    $inserted++;
                }
            }
        }

        $this->command->info("ResearchBudgetSeeder: {$inserted} record anggaran penelitian {$year} berhasil di-seed.");
    }
}
