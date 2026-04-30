    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        {{-- Card 1 --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Permohonan Bulan Ini</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $monthlyRegistrations }}</p>
                    <p class="mt-2 text-sm text-gray-500">Total pendaftaran masuk</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Perlu Review</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $waitingReview }}</p>
                    <p class="mt-2 text-sm text-gray-500">Dokumen dan pembayaran yang perlu diproses</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Sertifikat Bulan Ini</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $monthlyCertificates }}</p>
                    <p class="mt-2 text-sm text-gray-500">Berhasil diterbitkan</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
