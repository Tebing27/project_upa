    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Prodi / Skema
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Status / Jadwal</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Lokasi /
                            Asesor</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                            WhatsApp</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($registrations as $registration)
                        <tr wire:key="registration-{{ $registration->id }}" @class([
                            'bg-emerald-50/20' =>
                                $highlight === $registration->id &&
                                $registration->status === 'paid',
                            'bg-blue-50/20' =>
                                $highlight === $registration->id &&
                                $registration->status === 'terjadwal',
                            'hover:bg-gray-50/30 transition-colors' => $highlight !== $registration->id,
                        ])>
                            <td class="px-6 py-5">
                                <div class="text-sm font-semibold text-gray-900 leading-none">
                                    {{ $registration->user->name }}</div>
                                <p class="mt-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-600 leading-none">
                                    {{ $registration->user->program_studi ?: '-' }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ $registration->scheme?->name ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span @class([
                                    'inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-700 border-emerald-100' =>
                                        empty($registration->exam_date),
                                    'bg-blue-50 text-blue-700 border-blue-100' =>
                                        !empty($registration->exam_date),
                                ])>
                                    {{ !empty($registration->exam_date) ? 'Terjadwal' : 'Pembayaran Valid' }}
                                </span>
                                <div class="mt-2.5 flex flex-col items-center gap-1.5">
                                    <span
                                        class="text-xs font-semibold text-gray-900">{{ $registration->exam_date?->translatedFormat('d M Y') ?: '-' }}</span>
                                    @if ($registration->exam_date)
                                        <span
                                            class="text-xs text-gray-500 font-medium tracking-tight">{{ $registration->exam_date->format('H:i') }}
                                            WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-600 leading-none">
                                    {{ $registration->exam_location ?: '-' }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ $registration->assessor_name ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                @if ($whatsappSettings->isNotEmpty())
                                    <a href="{{ $globalWhatsappLink }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Buka Link
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    @if (!empty($registration->exam_date))
                                        <button wire:click="openScheduleModal({{ $registration->id }})"
                                            class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})"
                                            class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="openScheduleModal({{ $registration->id }})"
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500">
                                            Jadwalkan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Tidak ada peserta</h3>
                                <p class="mt-1 text-sm text-gray-500">Gunakan filter berbeda atau tunggu pendaftaran
                                    baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

