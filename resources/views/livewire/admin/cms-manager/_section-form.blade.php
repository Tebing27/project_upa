            <form wire:submit="saveSection"
                class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Section Manager</h2>
                    <p class="mt-1 text-sm text-slate-500">Buat section untuk halaman yang sedang aktif, lalu isi block
                        di bawahnya.</p>
                </div>

                <div class="space-y-5 p-6">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tab
                            Halaman</label>
                        <select wire:model="sectionForm.page_id"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            @foreach ($this->pages as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                        @error('sectionForm.page_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe
                                Section</label>
                            <select wire:model="sectionForm.section_type_id"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                @foreach ($this->sectionTypes as $sectionType)
                                    <option value="{{ $sectionType->id }}">
                                        {{ \Illuminate\Support\Str::headline($sectionType->name) }}</option>
                                @endforeach
                            </select>
                            @error('sectionForm.section_type_id')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan</label>
                            <input type="number" min="1" wire:model="sectionForm.sort_order"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                            @error('sectionForm.sort_order')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <label
                        class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/50 px-5 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Section Visible</p>
                            <p class="mt-1 text-xs text-slate-500">Matikan jika section ingin disimpan tetapi belum
                                ditampilkan.</p>
                        </div>
                        <input type="checkbox" wire:model="sectionForm.is_visible"
                            class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30">
                    </label>
                </div>

                <div
                    class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                    @if ($sectionId)
                        <button type="button" wire:click="deleteSection({{ $sectionId }})"
                            class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                            Hapus Section
                        </button>
                    @else
                        <span class="text-sm text-slate-500">Gunakan urutan untuk menentukan posisi section di
                            halaman.</span>
                    @endif

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" wire:click="prepareNewSection"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500">
                            <span wire:loading.remove
                                wire:target="saveSection">{{ $sectionId ? 'Update Section' : 'Simpan Section' }}</span>
                            <span wire:loading wire:target="saveSection">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </form>
