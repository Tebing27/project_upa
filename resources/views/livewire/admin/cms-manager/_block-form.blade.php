        <form wire:submit="saveBlock"
            class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Block Content</h2>
                <p class="mt-1 text-sm text-slate-500">Satu section bisa punya banyak block. Gunakan tipe `text` untuk
                    copy dan `image` untuk visual.</p>
            </div>

            <div class="space-y-5 p-6">
                <div class="grid gap-5 xl:grid-cols-[1fr_1fr_180px]">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Section
                            Tujuan</label>
                        <select wire:model.live="blockForm.section_id"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            <option value="">Pilih section</option>
                            @foreach ($this->availableSectionsForBlock as $sectionOption)
                                <option value="{{ $sectionOption['id'] }}">{{ $sectionOption['label'] }}</option>
                            @endforeach
                        </select>
                        @error('blockForm.section_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe
                            Block</label>
                        <select wire:model.live="blockForm.block_type_id"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            @foreach ($this->blockTypes as $blockType)
                                <option value="{{ $blockType->id }}">
                                    {{ \Illuminate\Support\Str::headline($blockType->name) }}</option>
                            @endforeach
                        </select>
                        @error('blockForm.block_type_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan</label>
                        <input type="number" min="1" wire:model="blockForm.sort_order"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                        @error('blockForm.sort_order')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if ($this->selectedBlockTypeName() === 'text')
                    <div class="grid gap-5 lg:grid-cols-[180px_1fr]">
                        <div>
                            <label
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Format</label>
                            <select wire:model="blockForm.format"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                <option value="plain">Plain</option>
                                <option value="html">HTML</option>
                                <option value="markdown">Markdown</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Isi
                                Konten</label>
                            <textarea wire:model="blockForm.value" rows="5"
                                class="block w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="Masukkan copy untuk section ini..."></textarea>
                            @error('blockForm.value')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @else
                    <div class="grid gap-5 lg:grid-cols-[1fr_1fr]">
                        <div class="space-y-5">
                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Upload
                                    Gambar</label>
                                <input type="file" wire:model="imageUpload" accept="image/*"
                                    x-on:change="setImagePreview($event)"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                                @error('imageUpload')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Alt
                                    Text</label>
                                <input type="text" wire:model="blockForm.alt_text"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Deskripsi singkat gambar" />
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Caption</label>
                                <input type="text" wire:model="blockForm.caption"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Caption opsional untuk gambar" />
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50/60 p-4">
                            @if ($imageUpload)
                                <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700">Preview
                                    Gambar Baru</p>
                                <img src="{{ $imageUpload->temporaryUrl() }}" alt="Preview block gambar"
                                    class="mt-4 h-64 w-full rounded-2xl object-cover">
                            @elseif ($existingImageUrl)
                                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-600">Preview Gambar
                                    Saat Ini</p>
                                <img src="{{ $existingImageUrl }}"
                                    alt="{{ $blockForm['alt_text'] ?: 'Gambar block CMS' }}"
                                    class="mt-4 h-64 w-full rounded-2xl object-cover">
                                @if ($existingImageName)
                                    <p class="mt-3 text-sm font-semibold text-slate-700">{{ $existingImageName }}</p>
                                @endif
                            @else
                                <div
                                    class="flex h-full min-h-64 items-center justify-center rounded-2xl bg-white text-center">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Belum ada gambar dipilih</p>
                                        <p class="mt-1 text-xs text-slate-500">Upload file gambar untuk block visual
                                            pada section aktif.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div
                class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                @if ($blockId)
                    <button type="button" wire:click="deleteBlock({{ $blockId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Block
                    </button>
                @else
                    <span class="text-sm text-slate-500">Setiap block tersimpan ke section yang dipilih di atas.</span>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" wire:click="prepareNewBlock"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500">
                        <span wire:loading.remove
                            wire:target="saveBlock">{{ $blockId ? 'Update Block' : 'Simpan Block' }}</span>
                        <span wire:loading wire:target="saveBlock">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
