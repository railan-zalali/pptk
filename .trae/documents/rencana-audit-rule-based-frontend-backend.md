# Rencana Audit Rule-Based dan Integrasi Frontend-Backend PPTK

## Ringkasan

Tujuan pekerjaan ini adalah melakukan audit teknis mendalam sebagai senior engineer untuk menjawab tiga hal:

1. Apakah algoritma rule-based benar-benar sudah diimplementasikan di sistem.
2. Apakah implementasi tersebut benar-benar terhubung ke alur backend operasional.
3. Apakah frontend, backend, dan dashboard memakai kontrak data yang konsisten atau justru ada mismatch yang membuat fitur terlihat ada tetapi tidak bekerja end-to-end.

Deliverable eksekusi setelah plan disetujui:

1. Ringkasan arsitektur fitur terkait insight/rule-based.
2. Putusan tegas: `sudah diimplementasikan`, `sebagian diimplementasikan`, atau `belum efektif dipakai`, lengkap dengan bukti file.
3. Matriks integrasi frontend -> route -> controller -> service/model -> view.
4. Daftar temuan prioritas yang membedakan antara masalah implementasi nyata vs masalah presentasi/UI.
5. Rekomendasi prioritas perbaikan tanpa melakukan perubahan kode kecuali diminta lanjut oleh user.

## Current State Analysis

Berdasarkan eksplorasi repo saat ini:

1. Aplikasi adalah Laravel monolith dengan Blade views. Struktur utama ada di `app/Http/Controllers`, `app/Models`, `app/Services`, `resources/views`, dan `routes/web.php`.
2. Implementasi rule-based insight memang ada secara eksplisit di:
   - `app/Services/InsightService.php`
   - `app/Services/RuleEngine/InsightRuleContract.php`
   - `app/Services/RuleEngine/InsightResult.php`
   - `app/Services/RuleEngine/ProductivityRule.php`
   - `app/Services/RuleEngine/QualityRule.php`
   - `app/Services/RuleEngine/StrategicActionRule.php`
3. Engine tersebut dipanggil langsung dari backend operasional:
   - `app/Http/Controllers/Admin/AdminProductionRealizationController.php`
   - `app/Http/Controllers/Admin/AdminStrategicActionController.php`
   - `app/Console/Commands/RefreshInsights.php`
   - `routes/console.php`
4. Output engine dipersist ke tabel `insights` melalui `Insight::updateOrCreate(...)` di `app/Services/InsightService.php`.
5. Data insight juga bisa dibuat manual melalui `app/Http/Controllers/Admin/AdminInsightController.php`, sehingga tabel `insights` bercampur antara hasil engine dan input manual.
6. Ada konfigurasi threshold rule di:
   - `app/Models/InsightConfig.php`
   - `database/migrations/2026_06_24_000001_buat_tabel_insight_configs.php`
   - `database/seeders/InsightConfigSeeder.php`
   Namun dari eksplorasi awal belum terlihat route/controller/view admin untuk mengelola konfigurasi ini lewat UI.
7. Ada indikasi mismatch penting antara frontend dan backend:
   - Form admin strategic action mengirim `action_type` dengan label manusia seperti `Pemupukan Akar`, `Mesin Petik`, `Pengendalian OPT`.
   - Rule engine dan dashboard publik membaca slug seperti `fertilizer_root`, `machine`, `opt`, `cultivator`.
   - Dampak yang perlu diverifikasi pada tahap eksekusi: data bisa tersimpan tetapi tidak pernah dikenali rule engine atau dashboard agregat.
8. Ada indikasi integrasi frontend-backend yang belum lengkap:
   - `ManajemenInsightController@show` dan `ManajemenStrategicActionController@show` mengarah ke view `show`, tetapi file Blade `resources/views/manajemen/**/show.blade.php` belum ada.
   - Dashboard publik menyiapkan beberapa dataset tambahan yang tampaknya tidak dipakai di Blade.
   - Tombol ekspor PDF di dashboard publik tampak belum terhubung ke route/controller.
9. Test yang ada belum membuktikan rule engine bekerja. `tests/Feature/DataIntegrityTest.php` hanya memastikan data insight seeded tersedia, bukan hasil evaluasi rule.

## Proposed Changes

Pekerjaan eksekusi setelah plan disetujui akan berfokus pada audit, bukan perubahan kode. Langkah-langkahnya:

1. Audit arsitektur backend rule-based.
   - File fokus:
     - `app/Services/InsightService.php`
     - `app/Services/RuleEngine/ProductivityRule.php`
     - `app/Services/RuleEngine/QualityRule.php`
     - `app/Services/RuleEngine/StrategicActionRule.php`
     - `app/Models/Insight.php`
     - `app/Models/InsightConfig.php`
   - Yang diverifikasi:
     - jenis rule yang ada,
     - input yang diharapkan,
     - cara persist hasil,
     - apakah desainnya benar rule-based atau hanya stub.
   - Why:
     - untuk memberi keputusan tegas apakah engine benar-benar ada dan aktif secara kode.

2. Audit titik pemanggilan backend operasional.
   - File fokus:
     - `app/Http/Controllers/Admin/AdminProductionRealizationController.php`
     - `app/Http/Controllers/Admin/AdminStrategicActionController.php`
     - `app/Console/Commands/RefreshInsights.php`
     - `routes/console.php`
   - Yang diverifikasi:
     - kapan engine dipanggil,
     - apakah dipanggil saat create/update/delete,
     - apakah batch refresh betul-betul menghitung rule,
     - apakah scheduler hanya deklaratif atau usable dari sudut kode.
   - Why:
     - untuk membedakan "engine tersedia" vs "engine benar-benar masuk ke flow bisnis".

3. Audit kontrak frontend-backend untuk fitur strategic action, insight, produksi, dan dashboard.
   - File fokus:
     - `resources/views/admin/strategic_actions/create.blade.php`
     - `resources/views/admin/strategic_actions/edit.blade.php`
     - `resources/views/admin/insights/*.blade.php`
     - `resources/views/manajemen/insights/index.blade.php`
     - `resources/views/manajemen/strategic_actions/index.blade.php`
     - `resources/views/dashboard/garden.blade.php`
     - `routes/web.php`
     - controller terkait dashboard/manajemen/public strategic
   - Yang diverifikasi:
     - value form vs value yang diharapkan service/query,
     - route ke controller dan controller ke view,
     - field model yang dipakai Blade,
     - dataset controller yang benar-benar dikonsumsi frontend.
   - Why:
     - untuk menguji apakah FE dan BE selaras atau ada lapisan yang putus.

4. Audit data model dan relasi yang menopang insight.
   - File fokus:
     - `app/Models/Garden.php`
     - `app/Models/StrategicAction.php`
     - `app/Models/ProductionRealization.php`
     - `app/Models/Insight.php`
     - migrasi terkait
   - Yang diverifikasi:
     - nama field, foreign key, cast, dan kesesuaian dengan pemakaian view/controller.
   - Why:
     - untuk membuktikan apakah mismatch berasal dari frontend, controller, query agregasi, atau skema data.

5. Audit kesiapan kualitas dan bukti operasional.
   - File fokus:
     - `tests/Feature/DataIntegrityTest.php`
     - seeder insight/riset terkait
   - Yang diverifikasi:
     - apakah test saat ini membuktikan engine berjalan,
     - apakah seeder menciptakan ilusi bahwa insight otomatis padahal manual/dummy.
   - Why:
     - agar kesimpulan audit tidak tertipu oleh data seed.

6. Susun hasil akhir dalam format keputusan dan prioritas.
   - Bentuk hasil:
     - status implementasi rule-based,
     - peta integrasi frontend-backend,
     - daftar temuan severity tinggi/sedang/rendah,
     - rekomendasi urutan perbaikan.
   - Why:
     - supaya hasil audit langsung bisa dipakai sebagai dasar keputusan teknis berikutnya.

## Assumptions & Decisions

1. Ruang lingkup utama adalah audit teknis dan analisis implementasi, bukan langsung refactor atau fixing.
2. Fokus audit diprioritaskan pada fitur insight/rule-based, strategic action, production realization, dashboard publik, dan tampilan manajemen/admin yang terhubung.
3. Penilaian "sudah diimplementasikan atau tidak" akan didasarkan pada bukti kode dan integrasi alur, bukan hanya keberadaan folder/service.
4. Saya tidak akan mengasumsikan scheduler OS benar-benar berjalan di server; dari repo hanya bisa dibuktikan bahwa scheduling telah dideklarasikan.
5. Data di tabel `insights` tidak akan dianggap bukti engine aktif tanpa menelusuri jalur pembentukan datanya, karena ada CRUD manual dan seeder.
6. Jika pada eksekusi ditemukan inkonsistensi tambahan di luar area ini tetapi masih memengaruhi kesimpulan rule-based dan integrasi FE-BE, temuan tersebut tetap akan dimasukkan sebagai temuan pendukung.

## Verification Steps

Verifikasi pada tahap eksekusi akan dilakukan dengan urutan berikut:

1. Telusuri semua referensi `InsightService`, class rule engine, dan `action_type` untuk membangun dependency map aktual.
2. Baca controller, route, model, dan Blade terkait untuk memastikan jalur data end-to-end.
3. Bandingkan kontrak value frontend terhadap query/controller/service yang mengonsumsi data tersebut.
4. Konfirmasi view/route yang hilang atau tidak sinkron dengan membaca file yang dirujuk langsung.
5. Validasi apakah test dan seeder mendukung atau justru menutupi kondisi sebenarnya.
6. Sajikan kesimpulan akhir dengan bukti file spesifik dan rekomendasi prioritas.
