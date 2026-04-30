    <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Link WhatsApp Global</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola satu link WhatsApp universal yang akan dipakai untuk semua peserta terjadwal.
                    </p>
                </div>
                @if ($whatsappSettings->isEmpty())
                    <button type="button" wire:click="openWhatsappLinkModal"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-bold text-black shadow-lg shadow-emerald-500/20 transition-all hover:bg-emerald-500">
                        Tambah Link
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama
                            Pengaturan</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($whatsappSettings as $setting)
                        <tr wire:key="whatsapp-setting-{{ $setting->id }}">
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-900">Link WhatsApp Universal</p>
                                <p class="mt-1 text-xs text-gray-500">Dipakai untuk semua peserta terjadwal</p>
                            </td>
                            <td class="px-6 py-5">
                                <a href="{{ $setting->value }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                    Buka Link
                                </a>
                                <p class="mt-2 break-all text-xs text-gray-500">{{ $setting->value }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="openWhatsappLinkModal({{ $setting->id }})"
                                        class="text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteWhatsappLink({{ $setting->id }})"
                                        class="text-sm font-semibold text-red-600 transition-colors hover:text-red-700">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0H7" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Belum ada link WhatsApp</h3>
                                <p class="mt-1 text-sm text-gray-500">Simpan link universal agar peserta terjadwal
                                    bisa langsung mengaksesnya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

