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
