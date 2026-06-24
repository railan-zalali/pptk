<?php

namespace Database\Seeders;

use App\Models\InsightConfig;
use Illuminate\Database\Seeder;

class InsightConfigSeeder extends Seeder
{
    /**
     * Seed konfigurasi threshold default untuk semua rule insight.
     * Nilai-nilai ini dapat diubah melalui Admin Panel tanpa mengubah kode.
     */
    public function run(): void
    {
        $configs = [
            // ==========================================
            // PRODUCTIVITY RULES (kg/ha/tahun)
            // ==========================================
            [
                'insight_type' => 'productivity',
                'rule_key'     => 'threshold_low',
                'rule_value'   => 1000,
                'unit'         => 'kg/ha',
                'description'  => 'Di bawah nilai ini = produktivitas rendah (alert HIGH)',
            ],
            [
                'insight_type' => 'productivity',
                'rule_key'     => 'threshold_medium',
                'rule_value'   => 1300,
                'unit'         => 'kg/ha',
                'description'  => 'Di bawah nilai ini = produktivitas menengah (alert MEDIUM), di atas = optimal (alert LOW)',
            ],

            // ==========================================
            // QUALITY RULES (skor mutu pucuk 0-10)
            // ==========================================
            [
                'insight_type' => 'quality',
                'rule_key'     => 'threshold_low',
                'rule_value'   => 7.0,
                'unit'         => 'skor',
                'description'  => 'Di bawah nilai ini = mutu rendah (alert HIGH)',
            ],
            [
                'insight_type' => 'quality',
                'rule_key'     => 'threshold_high',
                'rule_value'   => 8.5,
                'unit'         => 'skor',
                'description'  => 'Di atas nilai ini = mutu sangat baik (alert LOW)',
            ],

            // ==========================================
            // STRATEGIC — FERTILIZER ROOT RULES
            // ==========================================
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'realization_alert_low',
                'rule_value'   => 70,
                'unit'         => '%',
                'description'  => 'Jika realisasi < nilai ini → alert HIGH (pemupukan akar jauh dari target)',
            ],
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'realization_alert_medium',
                'rule_value'   => 85,
                'unit'         => '%',
                'description'  => 'Jika realisasi antara threshold_low dan nilai ini → alert MEDIUM',
            ],
            [
                'insight_type' => 'strategic_fertilizer_root',
                'rule_key'     => 'dosis_deficit_percent',
                'rule_value'   => 20,
                'unit'         => '%',
                'description'  => 'Jika dosis realisasi < dosis target - nilai ini persen → flag defisit pupuk',
            ],

            // ==========================================
            // STRATEGIC — FERTILIZER LEAF / WEED / CULTIVATOR
            // ==========================================
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

            // ==========================================
            // STRATEGIC — MACHINE RULES
            // ==========================================
            [
                'insight_type' => 'strategic_machine',
                'rule_key'     => 'age_alert_medium',
                'rule_value'   => 5,
                'unit'         => 'tahun',
                'description'  => 'Rata-rata umur mesin melebihi nilai ini → alert MEDIUM (peremajaan direkomendasikan)',
            ],
            [
                'insight_type' => 'strategic_machine',
                'rule_key'     => 'age_alert_high',
                'rule_value'   => 8,
                'unit'         => 'tahun',
                'description'  => 'Rata-rata umur mesin melebihi nilai ini → alert HIGH (peremajaan mendesak)',
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

        $this->command->info('InsightConfig: ' . count($configs) . ' konfigurasi berhasil di-seed.');
    }
}
