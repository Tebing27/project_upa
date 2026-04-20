<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadVerifiedApl01PdfController;
use App\Http\Controllers\LandingPageController;
use App\Livewire\Admin\CmsManager;
use App\Livewire\Admin\DetailDokumen;
use App\Livewire\Admin\DetailPembayaran;
use App\Livewire\Admin\JadwalUji;
use App\Livewire\Admin\SchemeForm;
use App\Livewire\Admin\SchemeManager;
use App\Livewire\Admin\UploadHasilUji;
use App\Livewire\Admin\VerifikasiDokumen;
use App\Livewire\Admin\VerifikasiPembayaran;
use App\Livewire\CekSertifikat;
use App\Livewire\ContactPage;
use App\Livewire\DaftarSkemaBaru;
use App\Livewire\FaqPage;
use App\Livewire\ProfilPage;
use App\Livewire\PublicSchemesPage;
use App\Livewire\SchemeDetail;
use App\Livewire\UserCertificatesPage;
use App\Livewire\UserRegistrationStatus;
use App\Livewire\UserSchemesPage;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/artikel', [LandingPageController::class, 'articlesIndex'])->name('article.index');
Route::get('/artikel/{slug}', [LandingPageController::class, 'showArticle'])->name('article.show');
Route::get('/galeri', [LandingPageController::class, 'galleryIndex'])->name('gallery.index');
Route::get('cek-sertifikat', CekSertifikat::class)->name('cek-sertifikat');
Route::get('kontak', ContactPage::class)->name('kontak');
Route::get('faq', FaqPage::class)->name('faq');
Route::get('profil', ProfilPage::class)->name('profil');
Route::get('skema', PublicSchemesPage::class)->name('skema.index');
Route::get('skema/{scheme}', SchemeDetail::class)->name('skema.detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('dashboard/status-pendaftaran/{registration?}', UserRegistrationStatus::class)->name('dashboard.status');
    Route::get('dashboard/status-pendaftaran/{registration}/download-apl-01', DownloadVerifiedApl01PdfController::class)->name('dashboard.status.apl01.download');
    Route::get('dashboard/sertifikat-saya', UserCertificatesPage::class)->name('dashboard.certificates');
    Route::get('dashboard/daftar-skema', DaftarSkemaBaru::class)->name('dashboard.daftar-skema');
    Route::get('dashboard/skema', UserSchemesPage::class)->name('dashboard.skema');

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('cms', CmsManager::class)->name('cms');
        Route::get('schemes', SchemeManager::class)->name('schemes');
        Route::get('schemes/create', SchemeForm::class)->name('schemes.create');
        Route::get('schemes/{scheme}/edit', SchemeForm::class)->name('schemes.edit');
        Route::get('verifikasi-dokumen', VerifikasiDokumen::class)->name('verifikasi');
        Route::get('verifikasi-dokumen/{registration}', DetailDokumen::class)->name('verifikasi.detail');
        Route::get('verifikasi-pembayaran', VerifikasiPembayaran::class)->name('payment');
        Route::get('verifikasi-pembayaran/{registration}', DetailPembayaran::class)->name('payment.detail');
        Route::get('jadwal-uji', JadwalUji::class)->name('jadwal');
        Route::get('upload-hasil-uji', UploadHasilUji::class)->name('hasil-uji');
    });
});

require __DIR__.'/settings.php';
