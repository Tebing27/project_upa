<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas dan permohonan sertifikasi terbaru.</p>
    </div>

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

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Tabel Permohonan Terbaru --}}
        <div class="lg:col-span-2">
            <div class="h-full rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Permohonan Terbaru</h2>
                    <a href="{{ route('admin.verifikasi') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-100">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Peserta</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Prodi / Skema</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @forelse($recentRegistrations as $reg)
                                <tr class="group transition-colors hover:bg-gray-50/30">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $reg->user->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $reg->user->isGeneralUser() ? ($reg->user->no_ktp ?: '-') : ($reg->user->nim ?: '-') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-700">
                                            {{ $reg->user->program_studi }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[200px]">
                                            {{ optional($reg->scheme)->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $colorMap = [
                                                'draft' => 'bg-slate-50 text-slate-700 border-slate-100',
                                                'menunggu_verifikasi' => 'bg-teal-50 text-teal-700 border-teal-100',
                                                'dokumen_ok' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'paid' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'terjadwal' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'kompeten' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'tidak_kompeten' => 'bg-red-50 text-red-700 border-red-100',
                                            ];
                                            $colorClass = $colorMap[$reg->status] ?? $colorMap['draft'];
                                            $label = $reg->statusLabel();
                                        @endphp
                                        <span
                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $colorClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ in_array($reg->status, ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true) ? route('admin.payment.detail', $reg) : route('admin.verifikasi.detail', $reg) }}" wire:navigate
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 transition-all hover:border-gray-300 hover:bg-gray-50">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-sm font-medium text-gray-500">Belum ada permohonan terbaru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Jadwal Uji Mendatang --}}
        <div class="lg:col-span-1">
            <div class="h-full rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Jadwal Mendatang</h2>
                </div>

                <div class="space-y-4">
                    @forelse($upcomingSchedules as $schedule)
                        <div
                            class="rounded-2xl border border-gray-100 bg-gray-50/50 p-5 transition-all hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->exam_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="mt-1 text-xs font-medium text-gray-500">Jam
                                        {{ \Carbon\Carbon::parse($schedule->exam_date)->format('H:i') }}</p>
                                </div>
                                <span
                                    class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-100">
                                    {{ $schedule->participant_count }} Peserta
                                </span>
                            </div>

                            <div class="mt-4 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $schedule->exam_location ?? 'Belum ditentukan' }}</span>
                            </div>

                            <div class="mt-1.5 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ optional($schedule->scheme)->name }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 flex flex-col items-center text-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-medium text-gray-500">Tidak ada jadwal mendatang.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
