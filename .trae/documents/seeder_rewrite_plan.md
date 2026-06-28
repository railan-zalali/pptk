
# Seeder Rewrite Plan

## 1. Analisis Sistem
Sistem adalah aplikasi Laravel untuk manajemen kebun teh (PPTK). Struktur database meliputi:
- Master Data: Regions, Gardens, Afdelings, Blocks
- Produksi: ProductionRealizations, PerformanceTargets
- Strategis: Programs, StrategicActions
- Penelitian: Visits, CommunityServices, ResearchBudgets, ResearchBudgetBalances
- Insights: Insights, InsightConfigs
- Users

## 2. Langkah Implementasi
1. **Hapus seeders lama**: Hapus semua file di database/seeders kecuali DatabaseSeeder.php
2. **Buat seeders baru yang lebih terstruktur**:
   - `UserSeeder.php`: Data pengguna (admin, manajemen)
   - `MasterDataSeeder.php`: Region, Garden, Afdeling, Block
   - `ProductionSeeder.php`: ProductionRealization (data realistis dengan trend musiman)
   - `StrategicSeeder.php`: Program, StrategicAction, PerformanceTarget
   - `ResearchSeeder.php`: Visit, CommunityService
   - `ResearchBudgetSeeder.php`: Data budget penelitian (dari file Excel)
   - `InsightConfigSeeder.php`: Konfigurasi rule insight
3. **Update DatabaseSeeder.php**: Atur urutan eksekusi seeders baru
4. **Buat data dummy yang berkualitas**:
   - Berikan nama yang realistis untuk kebun, region
   - Data produksi dengan trend musiman (teh Indonesia biasanya tinggi di awal/akhir tahun)
   - Data kunjungan dan pengabdian dengan deskripsi detail
   - Data strategis dengan target dan realisasi yang masuk akal

## 3. Files yang Akan Diubah
- Hapus: database/seeders/*.php (semua kecuali DatabaseSeeder.php)
- Edit: database/seeders/DatabaseSeeder.php
- Buat: 7 seeders baru dengan struktur yang lebih baik
