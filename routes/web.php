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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return auth()->user()?->can('admin')
            ? view('admin-dashboard')
            : view('dashboard');
    })->name('dashboard');

    Route::get('dashboard/status-pendaftaran/{registration?}', UserRegistrationStatus::class)->name('dashboard.status');
    Route::get('dashboard/sertifikat-saya', UserCertificatesPage::class)->name('dashboard.certificates');
    Route::get('dashboard/daftar-skema', DaftarSkemaBaru::class)->name('dashboard.daftar-skema');

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('schemes', SchemeManager::class)->name('schemes');
        Route::get('verifikasi-dokumen', VerifikasiDokumen::class)->name('verifikasi');
        Route::get('verifikasi-dokumen/{registration}', DetailDokumen::class)->name('verifikasi.detail');
        Route::get('jadwal-uji', JadwalUji::class)->name('jadwal');
        Route::get('upload-hasil-uji', UploadHasilUji::class)->name('hasil-uji');
    });
});

require __DIR__.'/settings.php';
