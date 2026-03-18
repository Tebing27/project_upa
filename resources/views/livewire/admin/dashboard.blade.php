<div class="p-6">
    <h1 class="mb-6 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Dashboard Admin</h1>

    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
        <!-- Card 1 -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Permohonan Bulan Ini</div>
                    <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $monthlyRegistrations }}</div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-full dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Antri Jadwal Uji</div>
                    <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $waitingReview }}</div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full dark:bg-green-900/50 text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sertifikat Bulan Ini</div>
                    <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $monthlyCertificates }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Tabel Permohonan Terbaru -->
        <div class="lg:col-span-2">
            <div class="h-full rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Permohonan Terbaru</h2>
                    <a href="{{ route('admin.verifikasi') }}" wire:navigate class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-medium text-zinc-900 hover:bg-zinc-100 dark:text-white dark:hover:bg-zinc-800 transition-colors">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-400">Mahasiswa</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-400">Prodi / Skema</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-zinc-500 uppercase dark:text-zinc-400">Status</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-right text-zinc-500 uppercase dark:text-zinc-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white dark:bg-zinc-900 dark:divide-zinc-800">
                            @forelse($recentRegistrations as $reg)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-medium text-zinc-900 dark:text-white">{{ $reg->user->name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $reg->user->nim }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-zinc-900 dark:text-zinc-300">{{ $reg->user->program_studi }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ optional($reg->scheme)->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $colorMap = [
                                                'draft' => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                                                'menunggu_verifikasi' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                                'dokumen_ok' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                'terjadwal' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                                'kompeten' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'tidak_kompeten' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            ];
                                            $colorClass = $colorMap[$reg->status] ?? $colorMap['draft'];
                                            $label = str_replace('_', ' ', Str::title($reg->status));
                                            if ($reg->status === 'dokumen_ok') $label = 'Dokumen OK';
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-black/10 dark:ring-white/10 {{ $colorClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('admin.verifikasi.detail', $reg) }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-semibold text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                        Belum ada permohonan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jadwal Uji Mendatang -->
        <div class="lg:col-span-1">
            <div class="h-full rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Jadwal Mendatang</h2>
                </div>
                
                <div class="space-y-4">
                    @forelse($upcomingSchedules as $schedule)
                        <div class="p-4 rounded-xl border border-zinc-200 bg-zinc-50/50 dark:border-zinc-700 dark:bg-zinc-800/50">
                            <div class="flex items-start justify-between mb-2">
                                <div class="font-medium text-sm text-zinc-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($schedule->exam_date)->translatedFormat('l, d F Y H:i') }}
                                </div>
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 dark:bg-indigo-900/30 dark:text-indigo-400 dark:ring-indigo-700/30">
                                    {{ $schedule->participant_count }} Peserta
                                </span>
                            </div>
                            
                            <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400 mt-2">
                                <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="truncate">{{ $schedule->exam_location ?? 'Belum ditentukan' }}</span>
                            </div>
                            
                            <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <span class="truncate">{{ optional($schedule->scheme)->name }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 flex flex-col items-center text-center text-sm text-zinc-500 dark:text-zinc-400">
                            <svg class="w-8 h-8 mb-3 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p>Tidak ada jadwal mendatang</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
