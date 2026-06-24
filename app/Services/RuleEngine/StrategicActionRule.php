<?php

namespace App\Services\RuleEngine;

use App\Models\InsightConfig;

/**
 * Rule untuk mengevaluasi realisasi aksi strategis perkebunan.
 *
 * Mendukung 7 tipe aksi: fertilizer_root, fertilizer_leaf, weed_control,
 * cultivator, picking, machine, opt.
 *
 * @param  array  $data  Harus berisi field:
 *   - action_type         (string)
 *   - realization_percent (float, nullable)
 *   - coverage_target_percent (float, nullable)
 *   - dosis_n_kg_ha       (float, nullable) — untuk fertilizer_root
 *   - realized_dosis_n_kg_ha (float, nullable) — untuk fertilizer_root
 *   - avg_machine_age     (float, nullable) — untuk machine
 *   - kandas_risk         (bool, nullable) — untuk picking
 *   - opt_status          (string, nullable) — untuk opt
 */
class StrategicActionRule implements InsightRuleContract
{
    public function getName(): string
    {
        return 'strategic_action';
    }

    public function evaluate(mixed $data): ?InsightResult
    {
        $type = $data['action_type'] ?? '';

        return match ($type) {
            'fertilizer_root' => $this->evaluateFertilizerRoot($data),
            'fertilizer_leaf' => $this->evaluateCoverage($data, 'Pemupukan Daun'),
            'weed_control'    => $this->evaluateCoverage($data, 'Pengendalian Gulma'),
            'cultivator'      => $this->evaluateCoverage($data, 'Kultivator'),
            'picking'         => $this->evaluatePicking($data),
            'machine'         => $this->evaluateMachine($data),
            'opt'             => $this->evaluateOpt($data),
            default           => null,
        };
    }

    // --------------------------------------------------------
    // RULE: Pemupukan Akar (fertilizer_root)
    // --------------------------------------------------------
    private function evaluateFertilizerRoot(array $data): ?InsightResult
    {
        $realizationPct = (float)($data['realization_percent'] ?? 0);
        $dosisPlan      = (float)($data['dosis_n_kg_ha'] ?? 0);
        $dosisRealized  = (float)($data['realized_dosis_n_kg_ha'] ?? 0);

        $alertLow    = InsightConfig::getValue('strategic_fertilizer_root', 'realization_alert_low',    70);
        $alertMedium = InsightConfig::getValue('strategic_fertilizer_root', 'realization_alert_medium', 85);
        $dosisDeficitPct = InsightConfig::getValue('strategic_fertilizer_root', 'dosis_deficit_percent', 20);

        // Cek defisit dosis
        $hasDosisDeficit = ($dosisPlan > 0) &&
            ($dosisRealized < $dosisPlan * (1 - $dosisDeficitPct / 100));

        if ($realizationPct < $alertLow) {
            return InsightResult::high(
                message: "Realisasi pemupukan akar sangat rendah ({$realizationPct}%)",
                recommendations: [
                    'Identifikasi penyebab keterlambatan aplikasi pupuk akar',
                    'Koordinasi dengan tim lapangan untuk percepatan realisasi',
                    'Evaluasi ketersediaan pupuk di gudang kebun',
                    $hasDosisDeficit
                        ? "Dosis realisasi ({$dosisRealized} kg/ha) jauh di bawah target ({$dosisPlan} kg/ha) — tambah aplikasi segera"
                        : 'Pastikan dosis aplikasi sesuai rekomendasi agronomis',
                ],
                title: 'Realisasi Pemupukan Akar Rendah',
                insightType: 'strategic_fertilizer_root',
            );
        }

        if ($realizationPct < $alertMedium) {
            return InsightResult::medium(
                message: "Realisasi pemupukan akar masih di bawah target ({$realizationPct}%)",
                recommendations: [
                    'Tingkatkan frekuensi aplikasi pupuk untuk mengejar target',
                    'Monitor progres realisasi mingguan',
                    $hasDosisDeficit
                        ? "Dosis realisasi ({$dosisRealized} kg/ha) perlu ditingkatkan untuk mencapai target ({$dosisPlan} kg/ha)"
                        : 'Pertahankan dosis aplikasi yang sudah sesuai',
                ],
                title: 'Pemupukan Akar Perlu Ditingkatkan',
                insightType: 'strategic_fertilizer_root',
            );
        }

        return InsightResult::low(
            message: "Realisasi pemupukan akar baik ({$realizationPct}%)",
            recommendations: [
                'Pertahankan jadwal dan dosis pemupukan yang sudah berjalan',
                'Dokumentasikan metode aplikasi sebagai acuan',
            ],
            title: 'Pemupukan Akar On Track',
            insightType: 'strategic_fertilizer_root',
        );
    }

    // --------------------------------------------------------
    // RULE: Cakupan (fertilizer_leaf, weed_control, cultivator)
    // --------------------------------------------------------
    private function evaluateCoverage(array $data, string $label): ?InsightResult
    {
        $realizationPct = (float)($data['realization_percent'] ?? 0);
        $insightType    = 'strategic_' . ($data['action_type'] ?? 'coverage');

        $alertLow    = InsightConfig::getValue('strategic_coverage', 'realization_alert_low',    60);
        $alertMedium = InsightConfig::getValue('strategic_coverage', 'realization_alert_medium', 80);

        if ($realizationPct <= 0) {
            return null;
        }

        if ($realizationPct < $alertLow) {
            return InsightResult::high(
                message: "Realisasi {$label} sangat rendah ({$realizationPct}%)",
                recommendations: [
                    "Segera lakukan percepatan program {$label}",
                    'Tambah tenaga kerja atau jam operasional jika memungkinkan',
                    'Evaluasi hambatan teknis di lapangan',
                    'Koordinasikan dengan mandor afdeling untuk rencana tindak lanjut',
                ],
                title: "Peringatan Realisasi {$label}",
                insightType: $insightType,
            );
        }

        if ($realizationPct < $alertMedium) {
            return InsightResult::medium(
                message: "Realisasi {$label} perlu ditingkatkan ({$realizationPct}%)",
                recommendations: [
                    "Tingkatkan progres {$label} sesuai jadwal",
                    'Monitor capaian mingguan dan laporkan ke manajemen',
                ],
                title: "{$label} Perlu Ditingkatkan",
                insightType: $insightType,
            );
        }

        return InsightResult::low(
            message: "Realisasi {$label} baik ({$realizationPct}%)",
            recommendations: [
                'Pertahankan progres yang sudah dicapai',
                'Dokumentasikan efektivitas metode yang digunakan',
            ],
            title: "{$label} On Track",
            insightType: $insightType,
        );
    }

    // --------------------------------------------------------
    // RULE: Pemetikan
    // --------------------------------------------------------
    private function evaluatePicking(array $data): ?InsightResult
    {
        $kandasRisk = (bool)($data['kandas_risk'] ?? false);
        $realizationPct = (float)($data['realization_percent'] ?? 0);

        if ($kandasRisk) {
            return InsightResult::high(
                message: 'Risiko pemetikan kandas terdeteksi',
                recommendations: [
                    'Segera lakukan evaluasi gilir petik dan rotasi pemetikan',
                    'Pastikan bantal petik (cushion) terjaga agar tidak terlalu tipis',
                    'Kurangi intensitas pemetikan sementara untuk pemulihan tanaman',
                    'Konsultasikan dengan agronomis untuk penyesuaian sistem petik',
                ],
                title: 'Risiko Kandas Pemetikan',
                insightType: 'strategic_picking',
            );
        }

        if ($realizationPct > 0 && $realizationPct < 80) {
            return InsightResult::medium(
                message: "Realisasi pemetikan perlu dimonitor ({$realizationPct}%)",
                recommendations: [
                    'Pastikan gilir petik sesuai dengan rotasi yang ditetapkan',
                    'Evaluasi ketersediaan tenaga petik di setiap afdeling',
                ],
                title: 'Progres Pemetikan Perlu Dimonitor',
                insightType: 'strategic_picking',
            );
        }

        return InsightResult::low(
            message: 'Sistem pemetikan berjalan normal',
            recommendations: [
                'Pertahankan konsistensi gilir petik dan standar pemetikan',
                'Terus monitor bantal petik secara berkala',
            ],
            title: 'Pemetikan Berjalan Normal',
            insightType: 'strategic_picking',
        );
    }

    // --------------------------------------------------------
    // RULE: Mesin Petik
    // --------------------------------------------------------
    private function evaluateMachine(array $data): ?InsightResult
    {
        $avgAge = (float)($data['avg_machine_age'] ?? 0);

        if ($avgAge <= 0) {
            return null;
        }

        $ageAlertHigh   = InsightConfig::getValue('strategic_machine', 'age_alert_high',   8);
        $ageAlertMedium = InsightConfig::getValue('strategic_machine', 'age_alert_medium', 5);

        if ($avgAge >= $ageAlertHigh) {
            return InsightResult::high(
                message: "Rata-rata umur mesin petik sangat tua ({$avgAge} tahun)",
                recommendations: [
                    'Prioritaskan program peremajaan mesin secara mendesak',
                    'Ajukan anggaran pengadaan mesin baru dalam RKAP berikutnya',
                    'Lakukan inspeksi menyeluruh kondisi mesin yang ada',
                    'Pertimbangkan sewa/pinjam mesin sementara untuk menjaga kapasitas petik',
                ],
                title: 'Peremajaan Mesin Mendesak',
                insightType: 'strategic_machine',
            );
        }

        if ($avgAge >= $ageAlertMedium) {
            return InsightResult::medium(
                message: "Rata-rata umur mesin petik mulai tua ({$avgAge} tahun)",
                recommendations: [
                    'Rencanakan program peremajaan mesin secara bertahap',
                    'Tingkatkan frekuensi perawatan dan servis berkala mesin',
                    'Evaluasi mesin-mesin dengan kerusakan sering sebagai prioritas penggantian',
                ],
                title: 'Peremajaan Mesin Perlu Direncanakan',
                insightType: 'strategic_machine',
            );
        }

        return InsightResult::low(
            message: "Kondisi umur mesin petik masih baik ({$avgAge} tahun)",
            recommendations: [
                'Pertahankan jadwal perawatan dan servis berkala',
                'Dokumentasikan riwayat perawatan setiap unit mesin',
            ],
            title: 'Kondisi Mesin Baik',
            insightType: 'strategic_machine',
        );
    }

    // --------------------------------------------------------
    // RULE: Pengendalian OPT (Organisme Pengganggu Tanaman)
    // --------------------------------------------------------
    private function evaluateOpt(array $data): ?InsightResult
    {
        $optStatus    = strtolower($data['opt_status'] ?? '');
        $tpNormalized = (bool)($data['tp_normalization'] ?? true);

        $isUncontrolled = str_contains($optStatus, 'tidak terkendali') ||
                          str_contains($optStatus, 'berat') ||
                          str_contains($optStatus, 'parah');

        $isModerate = str_contains($optStatus, 'sedang') ||
                      str_contains($optStatus, 'mulai') ||
                      !$tpNormalized;

        if ($isUncontrolled) {
            return InsightResult::high(
                message: 'OPT tidak terkendali — diperlukan tindakan segera',
                recommendations: [
                    'Lakukan aplikasi pestisida sesuai ambang ekonomi (AE) segera',
                    'Koordinasi dengan tim proteksi tanaman (TP) pusat',
                    'Identifikasi jenis OPT dominan (Helopeltis, Empoasca, dsb.) untuk penanganan spesifik',
                    'Pastikan normalisasi TP dilakukan sesuai standar',
                    'Monitor perkembangan serangan setiap 3–5 hari',
                ],
                title: 'OPT Tidak Terkendali',
                insightType: 'strategic_opt',
            );
        }

        if ($isModerate) {
            return InsightResult::medium(
                message: 'Serangan OPT dalam level sedang — perlu pemantauan ketat',
                recommendations: [
                    'Tingkatkan frekuensi monitoring serangan OPT di lapangan',
                    'Persiapkan tindakan pengendalian jika melewati ambang ekonomi',
                    !$tpNormalized ? 'Segera lakukan normalisasi areal TP (tanaman pelindung)' : 'Pertahankan kondisi TP yang sudah dinormalisasi',
                ],
                title: 'OPT Perlu Pemantauan',
                insightType: 'strategic_opt',
            );
        }

        return InsightResult::low(
            message: 'OPT terkendali dengan baik',
            recommendations: [
                'Pertahankan program pengendalian OPT yang sudah berjalan',
                'Lanjutkan monitoring berkala untuk deteksi dini',
            ],
            title: 'OPT Terkendali',
            insightType: 'strategic_opt',
        );
    }
}
