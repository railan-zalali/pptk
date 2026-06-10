# Dokumentasi Sistem PPTK (Pusat Penelitian Teh dan Kina)

## 1. Struktur Sistem
Sistem ini dibangun menggunakan framework Laravel dengan struktur database relasional yang mencakup entitas utama operasional perkebunan.

### Entitas Utama
*   **Region (Wilayah):** Mengelompokkan kebun berdasarkan geografis (misal: Bandung Raya, Garut Selatan).
*   **Garden (Kebun):** Unit operasional utama yang memiliki atribut luas lahan, tipe kebun (Model/Pengembangan), dan data agroklimat.
*   **Afdeling & Block:** Pembagian area kebun menjadi unit yang lebih kecil untuk manajemen teknis (Afdeling A-E, Blok 01-10).
*   **Production Realization:** Pencatatan realisasi produksi bulanan per kebun, termasuk produksi basah, luas petik efektif, kapasitas petik, forecast, dan skor kualitas.
*   **Performance Target:** Target kinerja tahunan (protas min/max).
*   **Strategic Action:** Rencana tindakan perbaikan (pemupukan, mekanisasi, pengendalian gulma).
*   **Visit (Kunjungan):** Jadwal dan laporan kunjungan dinas/monitoring.
*   **Insight:** Temuan otomatis atau manual terkait anomali produksi atau risiko hama.

## 2. Fungsi Komponen
*   **Dashboard Strategis:** Menyajikan ringkasan kinerja makro (Total Produksi, Rata-rata Produktivitas, Sebaran Wilayah).
*   **Manajemen Wilayah & Kebun:** CRUD data master wilayah dan profil kebun lengkap dengan foto dan sejarah.
*   **Pencatatan Produksi:** Input realisasi produksi bulanan dengan validasi periode unik per kebun.
*   **Analisis Insight:** Fitur untuk memberikan peringatan dini (early warning) berdasarkan tren data.
*   **Manajemen Kunjungan:** Penjadwalan, pelaporan, dan dokumentasi foto kegiatan lapangan.
*   **Program & Rencana Kerja:** Monitoring status pelaksanaan program strategis tahunan.

## 3. Alur Pengguna (User Flow) - Contoh: Monitoring Kunjungan
1.  **Login:** Admin atau Manajer login ke sistem.
2.  **Dashboard:** Melihat ringkasan kunjungan bulan ini di dashboard.
3.  **Menu Kunjungan:** Masuk ke menu "Kunjungan" untuk melihat daftar jadwal.
4.  **Buat Jadwal Baru:** Klik "Tambah Kunjungan", pilih Kebun, tentukan tanggal, dan deskripsi tujuan.
5.  **Pelaksanaan:** Saat kunjungan berlangsung/selesai, user mengupdate status menjadi "Completed".
6.  **Upload Laporan:** User mengupload foto dokumentasi dan mengisi kolom "Temuan" serta "Rekomendasi".
7.  **Review:** Data tersimpan dan muncul di laporan rekapitulasi serta profil kebun terkait.

## 4. Visualisasi Data Dummy (Menu UI)
Data dummy yang telah di-generate mencakup seluruh menu berikut:

*   **Dashboard:** Menampilkan data agregat dari 10 Region dan 10 Kebun.
*   **Master Data:**
    *   **Region:** 10 Wilayah (Bandung Raya s.d Cirebon Selatan).
    *   **Kebun:** 10 Kebun (Model Gambung s.d Cirebon Perintis).
    *   **Afdeling/Blok:** Struktur hirarki lengkap di bawah setiap kebun.
*   **Produksi:**
    *   **Realisasi Produksi:** Data bulanan per kebun untuk produksi basah, kapasitas, kualitas, dan forecast.
*   **Strategi:**
    *   **Target Kinerja:** Sasaran protas per tahun.
    *   **Aksi Strategis:** Daftar rencana kerja (pemupukan, mesin, dll).
*   **Operasional:**
    *   **Kunjungan:** 10+ record kunjungan dengan status berbeda (Scheduled, Completed).
    *   **Insight:** 10+ alert/temuan risiko (Hama, Iklim, Kualitas).
*   **CMS (Halaman Publik):** Konten statis untuk halaman About, Program, Kontak, dll.
