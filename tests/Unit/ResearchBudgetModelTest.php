<?php

namespace Tests\Unit;

use App\Models\ResearchBudget;
use App\Models\ResearchBudgetActivity;
use App\Models\ResearchBudgetBalance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearchBudgetModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed activities dan data anggaran untuk tahun 2025
        $this->seed(\Database\Seeders\ResearchBudgetSeeder::class);
    }

    // ----------------------------------------------------------------
    // activityList() — ambil dari DB
    // ----------------------------------------------------------------

    public function test_activity_list_returns_array(): void
    {
        $list = ResearchBudget::activityList();

        $this->assertIsArray($list);
        $this->assertNotEmpty($list);
    }

    public function test_activity_list_contains_expected_activities(): void
    {
        $list = ResearchBudget::activityList();

        $this->assertContains('Kina', $list);
        $this->assertContains('KUT', $list);
        $this->assertContains('Drone', $list);
    }

    // ----------------------------------------------------------------
    // getAnnualSummary() — struktur output
    // ----------------------------------------------------------------

    public function test_annual_summary_returns_array_of_activities(): void
    {
        $summary = ResearchBudget::getAnnualSummary(2025);

        $this->assertIsArray($summary);
        $this->assertNotEmpty($summary);
    }

    public function test_annual_summary_each_item_has_required_keys(): void
    {
        $summary = ResearchBudget::getAnnualSummary(2025);

        $required = [
            'activity_name', 'opening_balance', 'total_income',
            'total_expenditure', 'remaining_balance', 'realization_pct',
        ];

        foreach ($summary as $item) {
            foreach ($required as $key) {
                $this->assertArrayHasKey($key, $item, "Missing key '{$key}' in summary item");
            }
        }
    }

    public function test_annual_summary_remaining_balance_calculated_correctly(): void
    {
        $summary = ResearchBudget::getAnnualSummary(2025);

        foreach ($summary as $item) {
            $expected = $item['opening_balance'] + $item['total_income'] - $item['total_expenditure'];
            $this->assertEqualsWithDelta(
                $expected,
                $item['remaining_balance'],
                0.01,
                "remaining_balance tidak sesuai untuk {$item['activity_name']}"
            );
        }
    }

    public function test_annual_summary_for_nonexistent_year_returns_all_zeroes(): void
    {
        $summary = ResearchBudget::getAnnualSummary(1800); // tahun tidak ada data

        foreach ($summary as $item) {
            $this->assertEquals(0, $item['total_income']);
            $this->assertEquals(0, $item['total_expenditure']);
        }
    }

    // ----------------------------------------------------------------
    // getMonthlyData() — struktur output
    // ----------------------------------------------------------------

    public function test_monthly_data_returns_array(): void
    {
        $data = ResearchBudget::getMonthlyData(2025);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    public function test_monthly_data_has_12_months_per_activity(): void
    {
        $data = ResearchBudget::getMonthlyData(2025);

        foreach ($data as $activity => $months) {
            $this->assertCount(12, $months, "Activity '{$activity}' tidak memiliki 12 bulan");
        }
    }

    public function test_monthly_data_each_month_has_income_and_expenditure(): void
    {
        $data = ResearchBudget::getMonthlyData(2025);

        $firstActivity = array_key_first($data);
        $months = $data[$firstActivity];

        foreach ($months as $month => $values) {
            $this->assertArrayHasKey('income', $values);
            $this->assertArrayHasKey('expenditure', $values);
            // Nilai bisa integer 0 (default) atau float dari DB — keduanya valid numerik
            $this->assertIsNumeric($values['income']);
            $this->assertIsNumeric($values['expenditure']);
        }
    }

    // ----------------------------------------------------------------
    // getGrandTotals() — struktur output
    // ----------------------------------------------------------------

    public function test_grand_totals_returns_expected_keys(): void
    {
        $totals = ResearchBudget::getGrandTotals(2025);

        $this->assertArrayHasKey('total_opening', $totals);
        $this->assertArrayHasKey('total_income', $totals);
        $this->assertArrayHasKey('total_expenditure', $totals);
        $this->assertArrayHasKey('total_remaining', $totals);
    }

    public function test_grand_totals_remaining_is_correctly_calculated(): void
    {
        $totals = ResearchBudget::getGrandTotals(2025);

        $expected = $totals['total_opening'] + $totals['total_income'] - $totals['total_expenditure'];
        $this->assertEqualsWithDelta($expected, $totals['total_remaining'], 0.01);
    }

    public function test_grand_totals_are_numeric(): void
    {
        $totals = ResearchBudget::getGrandTotals(2025);

        $this->assertIsFloat($totals['total_opening']);
        $this->assertIsFloat($totals['total_income']);
        $this->assertIsFloat($totals['total_expenditure']);
        $this->assertIsFloat($totals['total_remaining']);
    }
}
