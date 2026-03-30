<?php

use App\Livewire\Admin\DetailDokumen;
use App\Livewire\Admin\JadwalUji;
use App\Livewire\Admin\SchemeManager;
use App\Livewire\Admin\UploadHasilUji;
use App\Livewire\Admin\VerifikasiDokumen;
use App\Livewire\DaftarSkemaBaru;
use App\Livewire\UserCertificatesPage;
use App\Livewire\UserRegistrationStatus;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Route Mahasiswa (Default)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('student.dashboard');
    Route::get('dashboard/status-pendaftaran/{registration?}', UserRegistrationStatus::class)->name('dashboard.status');
    Route::get('dashboard/sertifikat-saya', UserCertificatesPage::class)->name('dashboard.certificates');
    Route::get('dashboard/daftar-skema', DaftarSkemaBaru::class)->name('dashboard.daftar-skema');
});

// Route Admin LSP
Route::middleware(['auth', 'verified', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin-dashboard')->name('dashboard'); // ini akan menjadi admin.dashboard
    Route::get('schemes', SchemeManager::class)->name('schemes');
    Route::get('verifikasi-dokumen', VerifikasiDokumen::class)->name('verifikasi');
    Route::get('verifikasi-dokumen/{registration}', DetailDokumen::class)->name('verifikasi.detail');
    Route::get('jadwal-uji', JadwalUji::class)->name('jadwal');
    Route::get('upload-hasil-uji', UploadHasilUji::class)->name('hasil-uji');
});

// Route Asesor (Tambahan Baru)
Route::middleware(['auth', 'verified', 'can:asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::view('dashboard', 'asesor-dashboard')->name('dashboard'); // ini akan menjadi asesor.dashboard
    // Siapkan tempat untuk route Livewire asesor nantinya
});

require __DIR__.'/settings.php';
