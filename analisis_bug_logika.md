# Analisis Mendalam: Bug, Logika & Anomali Sistem PPTK

## Ringkasan Eksekutif
Ditemukan **8 temuan nyata** — 3 kritis (data salah), 3 medium, 2 anomali/smell.

---

## BUG KRITIS

### BUG #1 — StrategicAction Multi-Entry Overwrite Insight
**File:** InsightService.php (persistInsight, generateAllInsightsForGarden)

`persistInsight()` pakai `updateOrCreate(garden_id, insight_type)`. Jika satu kebun punya 2 StrategicAction dengan `action_type` yang sama (misal 2x `fertilizer_root` di tahun yang sama), loop di `generateAllInsightsForGarden` akan:

1. Overwrite key array `$results["strategic_fertilizer_root"]` → hanya insight terakhir yang dikembalikan
2. Di DB, `updateOrCreate` pada key `(garden_id, 'strategic_fertilizer_root')` → aksi dengan realisasi 30% (High Alert) bisa ter-overwrite oleh aksi 90% (Low Alert) tergantung urutan iterasi

**Dampak:** Insight kritis tersembunyi oleh data yang kurang kritis.

---

### BUG #2 — Quality Score 0 Tidak Terdeteksi sebagai High Alert
**File:** QualityRule.php:28

```php
if ($qualityScore <= 0) { return null; }
```

Skor kualitas `0` adalah kondisi **paling buruk** yang valid secara domain. Logika ini membuat kebun dengan mutu pucuk 0 tidak mendapat insight apapun — padahal semestinya **High Alert**.

**Fix:** Ubah ke `if ($qualityScore < 0)` atau gunakan `null` check terpisah dari zero-check.

---

### BUG #3 — Delete Data Tidak Refresh Insight
**File:** AdminProductionRealizationController.php:145-149

```php
public function destroy(ProductionRealization $productionRealization)
{
    $productionRealization->delete();
    // Tidak ada regenerasi insight!
}
```

Insight dihitung dari **agregat tahunan** semua bulan. Saat satu bulan dihapus (misal bulan produksi tertinggi), insight di tabel `insights` tetap stale. Badge Low Alert bisa muncul meski data sudah berubah drastis.

Masalah yang sama di `AdminStrategicActionController.php:132-136`.

---

## BUG MEDIUM

### BUG #4 — Skala Protas Kering di View vs Rule Engine Berbeda
**File:** index.blade.php vs InsightService.php

| Komponen | Skala Kalkulasi | Threshold yang Dipakai |
|----------|----------------|------------------------|
| View (badge per baris) | Per bulan: `dry_kg_bulan / area_bulan` | 220 / 286 kg/ha |
| Rule Engine (insight DB) | Tahunan: `total_dry_12_bulan / avg_area` | 220 / 286 kg/ha |

Threshold yang sama dipakai untuk dua skala yang berbeda. Produksi kering bulan Januari 300 kg / 15 ha = **20 kg/ha** → badge **High Alert** di view. Tapi rule engine akumulasikan 12 bulan → mungkin **Low Alert** di DB. **Badge view dan insight DB bisa kontradiktif.**

---

### BUG #5 — Null Safety Missing → Potential Fatal Error
**File:** DashboardStatisticsService.php:52

```php
// BERBAHAYA — jika tidak ada target untuk tahun ini:
$targetProtas = $garden->performanceTargets->first()->target_protas_min ?? 0;
```

`->first()` mengembalikan `null` jika koleksi kosong. Akses `->target_protas_min` pada `null` = **fatal error PHP**.

**Fix:** `$garden->performanceTargets->first()?->target_protas_min ?? 0`

Baris yang sama juga ada di `getGardenDetails()` baris 260.

---

### BUG #6 — Default `tp_normalization = true` Menyembunyikan Risiko OPT
**File:** StrategicActionRule.php:260

```php
$tpNormalized = (bool)($data['tp_normalization'] ?? true); // default: AMAN
```

Jika field null (belum diisi), sistem asumsi TP sudah dinormalisasi. Kondisi `!$tpNormalized` = `false`, sehingga tidak trigger Medium Alert dari sisi TP. Kebun yang **belum pernah mengisi data TP** tidak terdeteksi.

**Fix:** Default seharusnya `false` atau penanganan `null` eksplisit.

---

## ANOMALI & CODE SMELL

### ANOMALI #7 — Rules Diinstansiasi Manual, Tidak Testable
**File:** InsightService.php:31-37

```php
public function __construct()
{
    $this->productivityRule    = new ProductivityRule();   // hard-coded
    $this->productivityDryRule = new ProductivityDryRule();
    $this->qualityRule         = new QualityRule();
    $this->strategicRule       = new StrategicActionRule();
}
```

Rules tidak bisa di-mock. Unit testing `InsightService` tidak bisa isolasi behavior rules. Seharusnya diinjeksi via constructor atau service container.

---

### ANOMALI #8 — Double JSON Encode pada `recommendations`
**File:** AdminInsightController.php:53-55 & Insight.php:22

```php
// Controller (store & update):
$payload['recommendations'] = json_encode(array_values($payload['recommendations']));

// Model cast:
'recommendations' => 'array',  // Eloquent sudah handle encode/decode otomatis
```

Eloquent dengan cast `array` otomatis melakukan `json_encode` saat `save()`. Controller sudah encode duluan → data di DB menjadi **double-encoded** (`"[\"item\"]"`). Saat dibaca kembali, Eloquent decode satu layer → hasilnya adalah **string**, bukan array. View yang render `$insight->recommendations` akan error atau tampil salah.

---

## Prioritas Perbaikan

| # | Severity | File | Fix |
|---|----------|------|-----|
| 8 | KRITIS UI | `AdminInsightController.php:53` | Hapus `json_encode()` manual |
| 5 | FATAL | `DashboardStatisticsService.php:52,260` | Tambah `?->` null-safe operator |
| 2 | Kritis Logic | `QualityRule.php:28` | Ubah `<= 0` menjadi `< 0` |
| 3 | Kritis Stale | Controller `destroy()` | Regenerasi atau hapus insight terkait |
| 1 | Kritis Data | `InsightService.php` | Tambah `year` ke key `updateOrCreate` |
| 6 | Medium | `StrategicActionRule.php:260` | Default `false` untuk tp_normalization |
| 4 | Medium Design | View vs Service | Pisahkan threshold bulanan vs tahunan |
| 7 | Smell | `InsightService.php` | Inject rules via constructor |
