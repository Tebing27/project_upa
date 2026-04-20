<div class="mb-8">
    <a href="{{ route('dashboard') }}"
        class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 transition-colors hover:text-gray-700">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Dashboard
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Daftar Skema Sertifikasi</h1>
    <p class="mt-1 text-sm text-gray-500">
        @if ($useCondensedDocumentFlow)
            Pilih skema, selesaikan APL 01 dan upload APL 02, lalu review pendaftaran Anda.
        @else
            Pilih skema, lengkapi APL 01, lalu upload APL 02 Anda.
        @endif
    </p>
</div>
