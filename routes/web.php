<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()?->can('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    Route::get('dashboard/status-pendaftaran', \App\Livewire\UserRegistrationStatus::class)->name('dashboard.status');
    Route::get('dashboard/sertifikat-saya', \App\Livewire\UserCertificatesPage::class)->name('dashboard.certificates');

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('schemes', \App\Livewire\Admin\SchemeManager::class)->name('schemes');
        Route::get('verifikasi-dokumen', \App\Livewire\Admin\VerifikasiDokumen::class)->name('verifikasi');
        Route::get('verifikasi-dokumen/{registration}', \App\Livewire\Admin\DetailDokumen::class)->name('verifikasi.detail');
        Route::get('jadwal-uji', \App\Livewire\Admin\JadwalUji::class)->name('jadwal');
        Route::get('upload-hasil-uji', \App\Livewire\Admin\UploadHasilUji::class)->name('hasil-uji');
    });
});

require __DIR__.'/settings.php';
