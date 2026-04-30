<div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Sertifikat Aktif</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activeCertificatesCount ?? '0' }}</p>
                <p class="mt-2 text-[13px] text-gray-500">
                    {{ $activeCertificate?->scheme_name ?? 'Tidak ada sertifikat' }}
                </p>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <x-svg.bookmark />
            </div>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Status Pendaftaran</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $registrationStatusLabel }}</p>
                <p class="mt-2 text-[13px] text-gray-500">Tahap {{ $currentStep }} dari 5</p>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <x-svg.document />
            </div>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Kode Referensi</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">
                    {{ $latestRegistration?->payment_reference ?? '-' }}
                </p>
                <p class="mt-2 text-[13px] text-gray-500">Menyesuaikan data pendaftaran terbaru.</p>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <span class="text-xl font-semibold">#</span>
            </div>
        </div>
    </div>
</div>
