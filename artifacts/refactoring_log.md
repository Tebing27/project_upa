# Refactoring Log - Arsitektur Database & Relasi Model LSP

Berikut adalah dokumentasi dari semua perubahan yang telah dieksekusi pada codebase untuk menyesuaikan logika bisnis sistem LSP Kampus (RBAC dan Standar BNSP).

## 1. Update Tabel & Model User (RBAC)
- **Tabel `users`:** Menambahkan kolom `role` menggunakan tipe data `ENUM('mahasiswa', 'asesor', 'admin_lsp')` dengan default `'mahasiswa'`. Kolom ini ditambahkan langsung pada migration awal (`0001_01_01_000000_create_users_table.php`) dengan melakukan *squash* (menghapus migration `add_role_to_users_table.php` yang sebelumnya terpisah).
- **Model `User` (`app/Models/User.php`):** Menambahkan `role` ke dalam array `$fillable` untuk mendukung mass assignment.

## 2. Update Tabel & Model Registration (Relasi Asesor)
- **Tabel `registrations`:** Menghapus kolom string `assessor_name` pada file migration (`add_admin_columns_to_registrations_table.php`) dan menggantinya dengan kolom `assessor_id` sebagai `foreignId` yang mereferensikan tabel `users`, beserta konstrain relasional.
- **Model `Registration` (`app/Models/Registration.php`):** Menambahkan relasi `belongsTo` bernama `assessor()` yang mengarah ke model `User`.

## 3. Update Tabel & Model Scheme (Relasi Many-to-Many Prodi)
- **Tabel `schemes`:** Menghapus kolom string statis `faculty` dan `study_program` dari file migration awal (`create_schemes_table.php`).
- **Tabel & Model `StudyProgram`:** Karena Laravel membutuhkan eloquent relationship target untuk relasi belongsToMany yang valid, dilakukan penambahan Model `StudyProgram` (dan tabel pendukungnya) untuk menampung master data program studi dan menghapus redundansi fakultas/prodi.
- **Tabel Pivot `scheme_study_program`:** Membuat file migration baru untuk tabel pivot yang merepresentasikan relasi relasi _many-to-many_ melalui pasangan foreign key `scheme_id` dan `study_program_id`.
- **Model `Scheme` (`app/Models/Scheme.php`):** Menambahkan relasi `belongsToMany` bernama `studyPrograms()` yang mengarah ke model `StudyProgram`.
- **Seeder dan Factory:** Memperbarui `SchemeFactory` (menghapus property string obselete) dan memodifikasi `SchemeSeeder` agar membuat entity relasi many-to-many pada tabel pivot `scheme_study_program` ketika data _dummy_ dibuat.

## 4. Hapus Magic Strings pada Status
- **Tabel `registrations`:** Melakukan *squash* dengan menghapus file patch status dan mendefinisikan skema secara langsung di `create_registrations_table.php` menggunakan string tanpa constraint enum berlebih (mengikuti standarisasi status dinamis Laravel 10/11) dengan default `draft`.
- **Model `Registration`:** Mendefinisikan class constants untuk status:
  - `STATUS_DRAFT = 'draft'`
  - `STATUS_PENDING_VERIFICATION = 'pending_verification'`
  - `STATUS_PENDING_PAYMENT = 'pending_payment'`
  - `STATUS_PAID = 'paid'`
  - `STATUS_COMPLETED = 'completed'`
  - `STATUS_CERTIFICATE_ISSUED = 'sertifikat_terbit'`
  - `STATUS_INCOMPETENT = 'tidak_kompeten'`
- **Model `User`:** Memperbarui hardcoded / magic string `['sertifikat_terbit', 'tidak_kompeten']` di helper method seperti `hasInProgressRegistration()` dan `hasInProgressRegistrationForScheme()` untuk merujuk pada class constants model `Registration`.

## 5. Constraint & Execution
Proses _squashing_ migration telah dilakukan untuk merapikan urutan historikal file database. Perintah:
`php artisan migrate:fresh --seed`
telah dieksekusi di terminal dan berjalan sukses. Skema relasi baru, factory, dan seed data terkini terhubung tanpa masalah _foreign key integrity_.
