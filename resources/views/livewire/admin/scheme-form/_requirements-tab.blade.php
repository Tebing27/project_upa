        {{-- Tab: Persyaratan --}}
        <div x-show="$wire.activeTab === 'persyaratan'" class="space-y-5">
            {{-- Persyaratan Dasar --}}
            <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Persyaratan Dasar</h2>
                    <button type="button" wire:click="addPersyaratanDasar"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-6 space-y-3">
                    @forelse ($persyaratanDasars as $index => $pd)
                        <div wire:key="pd-{{ $index }}" class="flex items-start gap-2">
                            <span class="mt-2.5 text-xs font-semibold text-gray-400 w-5 shrink-0">{{ $index + 1 }}.</span>
                            <div class="flex-1">
                                <input type="text" wire:model="persyaratanDasars.{{ $index }}.deskripsi"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Deskripsi persyaratan dasar" />
                                @error("persyaratanDasars.{$index}.deskripsi")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" wire:click="removePersyaratanDasar({{ $index }})"
                                class="mt-2 text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-gray-200 py-8 text-center text-sm text-gray-400">
                            Belum ada persyaratan dasar.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Persyaratan Administrasi --}}
            <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Persyaratan Administrasi</h2>
                    <button type="button" wire:click="addPersyaratanAdministrasi"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-6 space-y-3">
                    @forelse ($persyaratanAdministrasis as $index => $pa)
                        <div wire:key="pa-{{ $index }}" class="flex items-start gap-2">
                            <span class="mt-2.5 text-xs font-semibold text-gray-400 w-5 shrink-0">{{ $index + 1 }}.</span>
                            <div class="flex-1">
                                <input type="text" wire:model="persyaratanAdministrasis.{{ $index }}.deskripsi"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama dokumen (misal: Kartu Tanda Penduduk)" />
                                @error("persyaratanAdministrasis.{$index}.deskripsi")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" wire:click="removePersyaratanAdministrasi({{ $index }})"
                                class="mt-2 text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-gray-200 py-8 text-center text-sm text-gray-400">
                            Belum ada persyaratan administrasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

