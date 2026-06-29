<?php

namespace Database\Seeders;

use App\Models\InsightConfig;
use Illuminate\Database\Seeder;

class InsightConfigSeeder extends Seeder
{
    /**
     * Seed konfigurasi threshold default untuk semua rule insight.
     *
     * Nilai-nilai ini dapat diubah melalui Admin Panel tanpa mengubah kode.
     * Format: insight_type + rule_key = unik, digunakan oleh Rule Engine.
     */
    public function run(): void
    {
        $configs = [
            // ──────────────────────────────────────────────────────
            // PRODUCTIVITY RULES — Produktivitas Tahunan (kg/ha)
            // ──────────────────────────────────────────────────────
            [
                'insight_type' => 'productivity',
                'rule_key'     => 'threshold_low',
                'rule_value'   => 1000,
                'unit'         => 'kg/ha',
                'description'  => 'Di bawah nilai ini → produktivitas rendah (alert HIGH)',
            ],
            [
                'insight_type' => 'productivity',
                'rule_key'     => 'threshold_medium',
                'rule_value'   => 1300,
                'unit'         => 'kg/ha',
                'description'  => 'Di bawah nilai ini → produktivitas menengah (alert MEDIUM), di atas → optimal (alert LOW)',
            ],

            // ──────────────────────────────────────────────────────
            // QUALITY RULES — Skor Mutu Pucuk (0–10)
            // ──────────────────────────────────────────────────────
            [
                'insight_type' => 'quality',
                'rule_key'     => 'threshold_low',
                'rule_value'   => 7.0,
                'unit'         => 'skor',
                'description'  => 'Di bawah nilai ini → mutu rendah (alert HIGH)',
            ],
            [
                'insight_type' => 'quality',
                'rule_key'     => 'threshold_high',
                'rule_value'   => 8.5,
                'unit'         => 'skor',
                'description'  => 'Di atas nilai ini → mutu sangat baik (alert LOW)',
            ],

            // ──────────────────────────────────────────────────────
            // STRATEGIC — FERTILIZER ROOT (Pemupukan Akar)
            // ──────────────────────────────────────────────────────
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'realization_alert_low',
                'rule_value'   => 70,
                'unit'         => '%',
                'description'  => 'Realisasi < nilai ini → alert HIGH (pemupukan akar jauh dari target)',
            ],
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'realization_alert_medium',
                'rule_value'   => 85,
                'unit'         => '%',
                'description'  => 'Realisasi antara threshold_low dan nilai ini → alert MEDIUM',
            ],
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'dosis_deficit_percent',
                'rule_value'   => 20,
                'unit'         => '%',
                'description'  => 'Jika dosis realisasi < dosis target - nilai ini persen → flag defisit pupuk',
            ],

            // ──────────────────────────────────────────────────────
            // STRATEGIC — COVERAGE (Pemupukan Daun / Gulma / Kultivator)
            // ──────────────────────────────────────────────────────
            [
                'insight_type' => 'strategic_coverage',
                'rule_key'     => 'realization_alert_low',
                'rule_value'   => 60,
                'unit'         => '%',
                'description'  => 'Realisasi cakupan < nilai ini → alert HIGH (weed_control, cultivator, fertilizer_leaf)',
            ],
            [
                'insight_type' => 'strategic_coverage',
                'rule_key'     => 'realization_alert_medium',
                'rule_value'   => 80,
                'unit'         => '%',
                'description'  => 'Realisasi cakupan antara threshold_low dan nilai ini → alert MEDIUM',
            ],

            // ──────────────────────────────────────────────────────
            // STRATEGIC — MACHINE (Umur Mesin Petik)
            // ──────────────────────────────────────────────────────
            [
                'insight_type' => 'strategic_machine',
                'rule_key'     => 'age_alert_medium',
                'rule_value'   => 5,
                'unit'         => 'tahun',
                'description'  => 'Rata-rata umur mesin ≥ nilai ini → alert MEDIUM (peremajaan direkomendasikan)',
            ],
            [
                'insight_type' => 'strategic_machine',
                'rule_key'     => 'age_alert_high',
                'rule_value'   => 8,
                'unit'         => 'tahun',
                'description'  => 'Rata-rata umur mesin ≥ nilai ini → alert HIGH (peremajaan mendesak)',
            ],
        ];

        foreach ($configs as $config) {
            InsightConfig::updateOrCreate(
                [
                    'insight_type' => $config['insight_type'],
                    'rule_key'     => $config['rule_key'],
                ],
                $config
            );
        }

        $this->command->info('InsightConfigSeeder: ' . count($configs) . ' konfigurasi rule engine berhasil di-seed.');
    }
}
