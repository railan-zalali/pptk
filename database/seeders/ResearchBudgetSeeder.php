<?php

namespace Database\Seeders;

use App\Models\ResearchBudget;
use App\Models\ResearchBudgetBalance;
use Illuminate\Database\Seeder;

class ResearchBudgetSeeder extends Seeder
{
    /**
     * Data diambil dari file "Realisasi Anggaran Penelitian 2025.xlsx"
     * Struktur: 3 section — Saldo Awal, Income (Pencairan), Pengeluaran
     */
    public function run(): void
    {
        $year = 2025;

        // =====================================================
        // Saldo Awal (Opening Balance) per kegiatan
        // Dari baris 5-14 kolom D di Excel
        // =====================================================
        $openingBalances = [
            'Kina'              => 11560533 - 2100000,  // =11560533-2100000
            'KUT'               => 19231936,
            'Malabar/Sedep'     => 39568543,
            'Drone'             => 0,
            'GWP'               => 0,
            'IPM Helopeltis'    => 0,
            'Bio Kompos'        => 0,
            'Daur Petik'        => 0,
            'Inkubasi Riset'    => 26437171,
            'Inkubasi Booster'  => 0,
        ];

        foreach ($openingBalances as $activity => $balance) {
            ResearchBudgetBalance::updateOrCreate(
                ['activity_name' => $activity, 'year' => $year],
                ['opening_balance' => $balance]
            );
        }

        // =====================================================
        // Income (Pencairan Dana) per kegiatan per bulan
        // Dari baris 19-28 di Excel
        // =====================================================
        $incomes = [
            //                       Jan        Feb        Mar        Apr        May        Jun         Jul       Aug         Sep        Oct         Nov        Dec
            'Kina'              => [0,         0,         0,         0,         0,         0,          0,        0,          0,         0,          0,         0],
            'KUT'               => [0,         0,         64799300,  0,         0,         0,          0,        0,          0,         73711511,   0,         0],
            'Malabar/Sedep'     => [0,         0,         0,         0,         0,         0,          0,        0,          0,         0,          0,         0],
            'Drone'             => [0,         0,         0,         0,         0,         25243000,   0,        0,          0,         0,          0,         0],
            'GWP'               => [0,         0,         0,         0,         0,         25400000,   0,        0,          0,         0,          0,         0],
            'IPM Helopeltis'    => [0,         0,         0,         0,         0,         42200000,   0,        0,          0,         0,          0,         0],
            'Bio Kompos'        => [0,         0,         0,         0,         0,         15867000,   0,        0,          0,         0,          0,         0],
            'Daur Petik'        => [0,         0,         0,         0,         0,         28950000,   0,        0,          0,         0,          0,         0],
            'Inkubasi Riset'    => [0,         89275000,  0,         0,         0,         84783000,   0,        0,          0,         0,          0,         0],
            'Inkubasi Booster'  => [0,         0,         0,         0,         0,         0,          0,        54000000,   0,         0,          0,         0],
        ];

        // =====================================================
        // Pengeluaran per kegiatan per bulan
        // Dari baris 32-41 di Excel
        // =====================================================
        $expenditures = [
            //                       Jan         Feb         Mar        Apr         May         Jun         Jul         Aug         Sep         Oct         Nov         Dec
            'Kina'              => [4859400,    2612000,    3511000,   0,          0,          0,          0,          0,          0,          0,          0,          0],
            'KUT'               => [4091030,    6291930,    0,         18109000,   31948591,   13445000,   7186000,    3692250,    0,          7740750,    26067100,   38326694],
            'Malabar/Sedep'     => [10810220,   3719650,    459000,    1658500,    688500,     1557000,    1476500,    1787716,    1797077,    3024000,    8849258,    1253750],
            'Drone'             => [0,          0,          0,         0,          0,          0,          1490500,    2337500,    0,          7000000,    7398000,    63508],
            'GWP'               => [0,          0,          0,         0,          0,          0,          12569228,   275000,     3000000,    3704776,    0,          0],
            'IPM Helopeltis'    => [0,          0,          0,         0,          0,          0,          18298500,   1636750,    6085000,    4322520,    254000,     4825230],
            'Bio Kompos'        => [0,          0,          0,         0,          0,          0,          7664561,    80000,      2206000,    0,          725000,     0],
            'Daur Petik'        => [0,          0,          0,         0,          0,          0,          4479816,    1205750,    11674997,   5674800,    2055250,    1156820],
            'Inkubasi Riset'    => [575418,     14110269,   8898983,   51297189,   5561118,    7638362,    27608698,   13145981,   2473150,    16695550,   1382750,    31991181],
            'Inkubasi Booster'  => [0,          0,          0,         0,          0,          0,          0,          0,          4184500,    0,          1886500,    201275],
        ];

        // Insert semua data
        foreach ($incomes as $activity => $monthlyIncomes) {
            for ($month = 1; $month <= 12; $month++) {
                $inc = $monthlyIncomes[$month - 1];
                $exp = $expenditures[$activity][$month - 1] ?? 0;

                // Hanya insert jika ada income atau pengeluaran
                if ($inc > 0 || $exp > 0) {
                    ResearchBudget::updateOrCreate(
                        [
                            'activity_name' => $activity,
                            'year' => $year,
                            'month' => $month,
                        ],
                        [
                            'income' => $inc,
                            'expenditure' => $exp,
                        ]
                    );
                }
            }
        }
    }
}
