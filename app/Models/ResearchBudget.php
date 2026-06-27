<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResearchBudget extends Model
{
    protected $fillable = [
        'activity_name',
        'year',
        'month',
        'income',
        'expenditure',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'income' => 'decimal:2',
        'expenditure' => 'decimal:2',
    ];

    /**
     * Daftar kegiatan penelitian standar (sesuai data Excel).
     */
    public static function activityList(): array
    {
        return [
            'Kina',
            'KUT',
            'Malabar/Sedep',
            'Drone',
            'GWP',
            'IPM Helopeltis',
            'Bio Kompos',
            'Daur Petik',
            'Inkubasi Riset',
            'Inkubasi Booster',
        ];
    }

    /**
     * Ambil ringkasan anggaran per kegiatan untuk satu tahun.
     * Mengembalikan: activity_name, total_income, total_expenditure, opening_balance, remaining_balance
     */
    public static function getAnnualSummary(int $year): array
    {
        $budgets = static::where('year', $year)
            ->selectRaw('activity_name, SUM(income) as total_income, SUM(expenditure) as total_expenditure')
            ->groupBy('activity_name')
            ->get()
            ->keyBy('activity_name');

        $balances = ResearchBudgetBalance::where('year', $year)
            ->pluck('opening_balance', 'activity_name');

        $summary = [];
        foreach (self::activityList() as $activity) {
            $data = $budgets->get($activity);
            $opening = (float)($balances[$activity] ?? 0);
            $totalIncome = (float)($data->total_income ?? 0);
            $totalExpenditure = (float)($data->total_expenditure ?? 0);

            $summary[] = [
                'activity_name' => $activity,
                'opening_balance' => $opening,
                'total_income' => $totalIncome,
                'total_expenditure' => $totalExpenditure,
                'remaining_balance' => $opening + $totalIncome - $totalExpenditure,
            ];
        }

        return $summary;
    }

    /**
     * Ambil data bulanan per kegiatan untuk satu tahun.
     */
    public static function getMonthlyData(int $year): array
    {
        $records = static::where('year', $year)
            ->orderBy('activity_name')
            ->orderBy('month')
            ->get()
            ->groupBy('activity_name');

        $result = [];
        foreach (self::activityList() as $activity) {
            $months = [];
            for ($m = 1; $m <= 12; $m++) {
                $months[$m] = ['income' => 0, 'expenditure' => 0];
            }

            if ($records->has($activity)) {
                foreach ($records[$activity] as $record) {
                    $months[$record->month] = [
                        'income' => (float)$record->income,
                        'expenditure' => (float)$record->expenditure,
                    ];
                }
            }

            $result[$activity] = $months;
        }

        return $result;
    }

    /**
     * Hitung grand total untuk satu tahun.
     */
    public static function getGrandTotals(int $year): array
    {
        $totals = static::where('year', $year)
            ->selectRaw('SUM(income) as total_income, SUM(expenditure) as total_expenditure')
            ->first();

        $totalOpening = ResearchBudgetBalance::where('year', $year)
            ->sum('opening_balance');

        return [
            'total_opening' => (float)$totalOpening,
            'total_income' => (float)($totals->total_income ?? 0),
            'total_expenditure' => (float)($totals->total_expenditure ?? 0),
            'total_remaining' => (float)$totalOpening + (float)($totals->total_income ?? 0) - (float)($totals->total_expenditure ?? 0),
        ];
    }
}
