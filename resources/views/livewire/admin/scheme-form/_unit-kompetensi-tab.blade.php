        {{-- Tab: Unit Kompetensi --}}
        <div x-show="$wire.activeTab === 'unit'"
            class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Unit Kompetensi</h2>
                <button type="button" wire:click="addUnitKompetensi"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Unit
                </button>
            </div>
            <div class="p-6 space-y-4">
                @forelse ($unitKompetensis as $index => $unit)
                    <div wire:key="unit-{{ $index }}" class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Unit {{ $index + 1 }}</span>
                            <button type="button" wire:click="removeUnitKompetensi({{ $index }})"
                                class="text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.kode_unit"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Kode Unit" />
                                @error("unitKompetensis.{$index}.kode_unit")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.nama_unit"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama Unit (ID)" />
                                @error("unitKompetensis.{$index}.nama_unit")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.nama_unit_en"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama Unit (EN) - opsional" />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border-2 border-dashed border-gray-200 py-10 text-center text-sm text-gray-400">
                        Belum ada unit kompetensi. Klik "Tambah Unit" untuk menambahkan.
                    </div>
                @endforelse
            </div>
        </div>

