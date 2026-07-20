<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchBudget;
use App\Models\ResearchBudgetActivity;
use App\Models\ResearchBudgetBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminResearchBudgetController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $annualSummary = ResearchBudget::getAnnualSummary($year);
        $monthlyData = ResearchBudget::getMonthlyData($year);
        $grandTotals = ResearchBudget::getGrandTotals($year);
        $openingBalances = ResearchBudgetBalance::where('year', $year)
            ->pluck('opening_balance', 'activity_name');

        // Ambil sebagai objek agar view bisa pakai id (untuk hapus)
        $activities = ResearchBudgetActivity::orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
        
        return view('admin.research-budgets.index', compact(
            'year',
            'annualSummary',
            'monthlyData',
            'grandTotals',
            'openingBalances',
            'activities'
        ));
    }

    public function edit(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $monthlyData = ResearchBudget::getMonthlyData($year);
        $openingBalances = ResearchBudgetBalance::where('year', $year)
            ->pluck('opening_balance', 'activity_name');
        $activities = ResearchBudget::activityList();
        
        return view('admin.research-budgets.edit', compact(
            'year',
            'monthlyData',
            'openingBalances',
            'activities'
        ));
    }

    public function update(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Update opening balances
        if ($request->has('opening_balances')) {
            foreach ($request->opening_balances as $activity => $balance) {
                ResearchBudgetBalance::updateOrCreate(
                    ['activity_name' => $activity, 'year' => $year],
                    ['opening_balance' => $balance ?: 0]
                );
            }
        }
        
        // Update monthly data
        if ($request->has('monthly')) {
            foreach ($request->monthly as $activity => $months) {
                foreach ($months as $month => $data) {
                    $income = $data['income'] ?: 0;
                    $expenditure = $data['expenditure'] ?: 0;
                    
                    if ($income > 0 || $expenditure > 0) {
                        ResearchBudget::updateOrCreate(
                            [
                                'activity_name' => $activity,
                                'year' => $year,
                                'month' => $month,
                            ],
                            [
                                'income' => $income,
                                'expenditure' => $expenditure,
                                'notes' => $data['notes'] ?? null,
                            ]
                        );
                    } else {
                        // Delete if both are zero
                        ResearchBudget::where([
                            'activity_name' => $activity,
                            'year' => $year,
                            'month' => $month,
                        ])->delete();
                    }
                }
            }
        }
        
        return redirect()->route('manajemen.research-budgets.index', ['year' => $year])
            ->with('success', 'Data anggaran penelitian berhasil diperbarui.');
    }

    /** Tambah kegiatan baru ke daftar */
    public function storeActivity(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:research_budget_activities,name']);

        $max = ResearchBudgetActivity::max('sort_order') ?? -1;
        ResearchBudgetActivity::create(['name' => $request->name, 'sort_order' => $max + 1]);

        return back()->with('success', 'Kegiatan "' . $request->name . '" berhasil ditambahkan.');
    }

    /** Hapus kegiatan dari daftar */
    public function destroyActivity(ResearchBudgetActivity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
