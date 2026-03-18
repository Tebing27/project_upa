<div class="space-y-6 p-6">
    @php
        $downloadUrl = $activeCertificate?->file_path ? Storage::url($activeCertificate->file_path) : null;
        $resultDownloadUrl = $activeCertificate?->result_file_path ? Storage::url($activeCertificate->result_file_path) : null;
    @endphp

    <div>
        <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Sertifikat Saya</h1>
        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Lihat sertifikat aktif dan riwayat sertifikat yang sudah diterbitkan.</p>
    </div>

    <div class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="rounded-[1.75rem] bg-linear-to-br from-sky-950 via-blue-950 to-slate-900 p-8 text-white">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-200/80">Sertifikat Aktif</p>

            <div class="mt-6 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm text-blue-100/70">No. Sertifikat</p>
                    <p class="mt-1 font-mono text-lg tracking-wide text-blue-100">{{ $activeCertificate ? 'CERT-' . str_pad((string) $activeCertificate->id, 5, '0', STR_PAD_LEFT) : 'Tidak ada' }}</p>

                    <h2 class="mt-8 text-3xl font-semibold tracking-tight">{{ $activeCertificate?->scheme_name ?? 'Tidak ada sertifikat aktif' }}</h2>
                    <p class="mt-3 text-sm text-blue-100/80">{{ auth()->user()->name }}</p>
                    <p class="mt-5 text-sm text-blue-100/70">
                        @if ($activeCertificate?->expired_date)
                            Berlaku s.d. {{ $activeCertificate->expired_date->translatedFormat('F Y') }}
                        @else
                            Sertifikat belum tersedia.
                        @endif
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    @if ($downloadUrl)
                        <a href="{{ $downloadUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Sertifikat
                        </a>
                    @endif

                    @if ($resultDownloadUrl)
                        <a href="{{ $resultDownloadUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/12 px-5 py-3 text-sm font-semibold text-white ring-1 ring-inset ring-white/10 transition hover:bg-white/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                            </svg>
                            Unduh Hasil Ujian
                        </a>
                    @endif

                    @if (! $downloadUrl && ! $resultDownloadUrl)
                        <span class="inline-flex items-center justify-center rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold text-blue-100/90 ring-1 ring-inset ring-white/10">
                            File belum tersedia
                        </span>
                    @endif

                    <span class="inline-flex items-center justify-center rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold text-blue-100/90 ring-1 ring-inset ring-white/10">
                        {{ $activeCertificate ? 'Status: Aktif' : 'Belum ada sertifikat' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[2rem] border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 px-6 py-5 dark:border-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Semua Sertifikat</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Skema</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">No. Sertifikat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Berlaku</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                    @forelse ($certificates as $certificate)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $certificate->scheme_name }}</p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $certificate->level ?: 'Umum' }}</p>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-zinc-700 dark:text-zinc-300">
                                {{ 'CERT-' . str_pad((string) $certificate->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $certificate->expired_date ? 's.d. ' . $certificate->expired_date->translatedFormat('M Y') : 'Tidak ada' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClasses = match ($certificate->status) {
                                        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70',
                                        default => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                    {{ $certificate->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    @if ($certificate->file_path)
                                        <a href="{{ Storage::url($certificate->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-200">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Unduh Sertifikat
                                        </a>
                                    @endif

                                    @if ($certificate->result_file_path)
                                        <a href="{{ Storage::url($certificate->result_file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-700 transition hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                            </svg>
                                            Unduh Hasil Ujian
                                        </a>
                                    @endif

                                    @if (! $certificate->file_path && ! $certificate->result_file_path)
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Tidak ada</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                Belum ada sertifikat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
