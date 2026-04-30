    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Jadwal</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Status</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Berkas</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($uploadableRegistrations as $registration)
                        <tr wire:key="upload-{{ $registration->id }}" @class([
                            'bg-emerald-50/20' => $highlight === $registration->id,
                            'hover:bg-gray-50/30 transition-colors' => $highlight !== $registration->id,
                        ])>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-900 leading-none">
                                        {{ $registration->user->name }}</span>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }} • {{ $registration->scheme?->name ?: '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span
                                        class="text-xs font-semibold text-gray-900">{{ $registration->exam_date?->translatedFormat('d M Y') ?: '-' }}</span>
                                    @if ($registration->exam_date)
                                        <span
                                            class="text-xs text-gray-500 font-medium tracking-tight">{{ $registration->exam_date->format('H:i') }}
                                            WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                    $statusLabel = 'Belum Upload';

                                    if ($registration->status === 'tidak_kompeten') {
                                        $statusClass = 'bg-red-50 text-red-700 border-red-100';
                                        $statusLabel = 'Tidak Lolos';
                                    } elseif ($registration->status === 'kompeten') {
                                        $statusClass = 'bg-blue-50 text-blue-700 border-blue-100';
                                        $statusLabel = 'Kompeten';
                                    } elseif ($registration->active_certificate_id !== null) {
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                        $statusLabel = 'Sertifikat Terbit';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-2">
                                    @if ($registration->certificate_file_url)
                                        <a href="{{ $registration->certificate_file_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Sertifikat PDF
                                        </a>
                                    @endif

                                    @if ($registration->result_file_url)
                                        <a href="{{ $registration->result_file_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Surat Keterangan PDF
                                        </a>
                                    @endif

                                    @if (!$registration->certificate_file_url && !$registration->result_file_url)
                                        <span
                                            class="text-xs font-medium text-gray-400 italic font-medium tracking-tight">Belum
                                            ada file</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-2">
                                    @if (in_array($registration->status, ['kompeten', 'sertifikat_terbit', 'tidak_kompeten']))
                                        <button wire:click="openUploadModal({{ $registration->id }})"
                                            class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})"
                                            class="text-xs font-semibold text-red-600 hover:text-red-700 transition-colors">
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="openUploadModal({{ $registration->id }})"
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500 transition-colors shadow-sm">
                                            Upload Sekarang
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Belum ada data upload</h3>
                                <p class="mt-1 text-sm text-gray-500 font-medium">Peserta yang terjadwal akan muncul di
                                    sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

