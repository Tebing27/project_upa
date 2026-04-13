<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen"
    x-data="{
        imagePreviewUrl: null,
        setImagePreview(event) {
            const [file] = event.target.files ?? [];
            this.imagePreviewUrl = file ? URL.createObjectURL(file) : null;
        },
    }">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">CMS Website</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola tab halaman, section, dan block konten teks atau gambar dalam satu workspace admin.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="rounded-xl bg-white px-4 py-3 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Halaman Aktif</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $isCreatingPage ? 'Membuat Tab Baru' : ($this->activePage?->title ?? 'Belum ada') }}
                </p>
            </div>
            <button type="button" wire:click="startCreatingPage"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tab Baru
            </button>
        </div>
    </div>

    <div class="overflow-x-auto rounded-[1.5rem] bg-white p-3 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex min-w-max items-center gap-3">
            <div class="flex gap-1 rounded-xl bg-slate-100 p-1">
            @foreach ($this->pages as $page)
                <button type="button" wire:key="page-tab-{{ $page->id }}" wire:click="selectPage('{{ $page->slug }}')"
                    @class([
                        'inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => ! $isCreatingPage && $activePageSlug === $page->slug,
                        'text-gray-500 hover:text-gray-700' => $isCreatingPage || $activePageSlug !== $page->slug,
                    ])>
                    <span>{{ $page->title }}</span>
                    <span @class([
                        'inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-bold',
                        'bg-emerald-100 text-emerald-700' => ! $isCreatingPage && $activePageSlug === $page->slug,
                        'bg-slate-200 text-slate-600' => $isCreatingPage || $activePageSlug !== $page->slug,
                    ])>
                        {{ $page->sections_count }}
                    </span>
                </button>
            @endforeach
            </div>
            <button type="button" wire:click="startCreatingPage"
                class="inline-flex items-center gap-2 rounded-xl border border-dashed border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tab Baru
            </button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <form wire:submit="savePage"
            class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Pengaturan Halaman</h2>
                    <p class="mt-1 text-sm text-slate-500">Tab di atas diambil dari tabel `pages` dan bisa dikelola langsung dari sini.</p>
                </div>
                @if ($this->activePage && ! $isCreatingPage)
                    <button type="button" wire:click="editPage({{ $this->activePage->id }})"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Edit Halaman Aktif
                    </button>
                @elseif ($isCreatingPage)
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Mode Tab Baru
                    </span>
                @endif
            </div>

            <div class="space-y-5 p-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Judul Halaman
                        </label>
                        <input type="text" wire:model="pageForm.title"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="Contoh: Home" />
                        @error('pageForm.title')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Slug
                        </label>
                        <input type="text" wire:model="pageForm.slug"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="home" />
                        @error('pageForm.slug')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <label
                    class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/50 px-5 py-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Status Publish</p>
                        <p class="mt-1 text-xs text-slate-500">Jika nonaktif, halaman tetap ada di CMS tetapi bisa dianggap belum siap tampil.</p>
                    </div>
                    <input type="checkbox" wire:model="pageForm.is_published"
                        class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30">
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                @if ($pageId)
                    <button type="button" wire:click="deletePage({{ $pageId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Tab Ini
                    </button>
                @else
                    <span class="text-sm text-slate-500">Siap membuat tab halaman baru.</span>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    @if ($isCreatingPage)
                        <button type="button" wire:click="selectPage('{{ $activePageSlug ?: 'home' }}')"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Batal
                        </button>
                    @else
                        <button type="button" wire:click="startCreatingPage"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Tab Baru
                        </button>
                    @endif
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500">
                        <span wire:loading.remove wire:target="savePage">{{ $isCreatingPage ? 'Simpan Tab Baru' : 'Update Halaman' }}</span>
                        <span wire:loading wire:target="savePage">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>

        <form wire:submit="saveSection"
            class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Section Manager</h2>
                <p class="mt-1 text-sm text-slate-500">Buat section untuk halaman yang sedang aktif, lalu isi block di bawahnya.</p>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tab Halaman</label>
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
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe Section</label>
                        <select wire:model="sectionForm.section_type_id"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            @foreach ($this->sectionTypes as $sectionType)
                                <option value="{{ $sectionType->id }}">{{ \Illuminate\Support\Str::headline($sectionType->name) }}</option>
                            @endforeach
                        </select>
                        @error('sectionForm.section_type_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan</label>
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
                        <p class="mt-1 text-xs text-slate-500">Matikan jika section ingin disimpan tetapi belum ditampilkan.</p>
                    </div>
                    <input type="checkbox" wire:model="sectionForm.is_visible"
                        class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30">
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                @if ($sectionId)
                    <button type="button" wire:click="deleteSection({{ $sectionId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Section
                    </button>
                @else
                    <span class="text-sm text-slate-500">Gunakan urutan untuk menentukan posisi section di halaman.</span>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" wire:click="prepareNewSection"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500">
                        <span wire:loading.remove wire:target="saveSection">{{ $sectionId ? 'Update Section' : 'Simpan Section' }}</span>
                        <span wire:loading wire:target="saveSection">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <form wire:submit="saveBlock"
        class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
            <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Block Content</h2>
            <p class="mt-1 text-sm text-slate-500">Satu section bisa punya banyak block. Gunakan tipe `text` untuk copy dan `image` untuk visual.</p>
        </div>

        <div class="space-y-5 p-6">
            <div class="grid gap-5 xl:grid-cols-[1fr_1fr_180px]">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Section Tujuan</label>
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
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipe Block</label>
                    <select wire:model.live="blockForm.block_type_id"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                        @foreach ($this->blockTypes as $blockType)
                            <option value="{{ $blockType->id }}">{{ \Illuminate\Support\Str::headline($blockType->name) }}</option>
                        @endforeach
                    </select>
                    @error('blockForm.block_type_id')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan</label>
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
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Format</label>
                        <select wire:model="blockForm.format"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            <option value="plain">Plain</option>
                            <option value="html">HTML</option>
                            <option value="markdown">Markdown</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Isi Konten</label>
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
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Upload Gambar</label>
                            <input type="file" wire:model="imageUpload" accept="image/*" x-on:change="setImagePreview($event)"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            @error('imageUpload')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Alt Text</label>
                            <input type="text" wire:model="blockForm.alt_text"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="Deskripsi singkat gambar" />
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Caption</label>
                            <input type="text" wire:model="blockForm.caption"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="Caption opsional untuk gambar" />
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50/60 p-4">
                        @if ($imageUpload)
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700">Preview Gambar Baru</p>
                            <img src="{{ $imageUpload->temporaryUrl() }}" alt="Preview block gambar"
                                class="mt-4 h-64 w-full rounded-2xl object-cover">
                        @elseif ($existingImageUrl)
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-600">Preview Gambar Saat Ini</p>
                            <img src="{{ $existingImageUrl }}" alt="{{ $blockForm['alt_text'] ?: 'Gambar block CMS' }}"
                                class="mt-4 h-64 w-full rounded-2xl object-cover">
                            @if ($existingImageName)
                                <p class="mt-3 text-sm font-semibold text-slate-700">{{ $existingImageName }}</p>
                            @endif
                        @else
                            <div class="flex h-full min-h-64 items-center justify-center rounded-2xl bg-white text-center">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada gambar dipilih</p>
                                    <p class="mt-1 text-xs text-slate-500">Upload file gambar untuk block visual pada section aktif.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
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
                    <span wire:loading.remove wire:target="saveBlock">{{ $blockId ? 'Update Block' : 'Simpan Block' }}</span>
                    <span wire:loading wire:target="saveBlock">Menyimpan...</span>
                </button>
            </div>
        </div>
    </form>

    <div class="space-y-5">
        @forelse ($this->sections as $section)
            <div wire:key="cms-section-{{ $section->id }}"
                class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex flex-col gap-4 border-b border-slate-100 bg-slate-50/70 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                {{ \Illuminate\Support\Str::headline($section->sectionType?->name ?? 'Section') }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-600">
                                Urutan {{ $section->sort_order }}
                            </span>
                            <span @class([
                                'rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider',
                                'bg-emerald-100 text-emerald-700' => $section->is_visible,
                                'bg-amber-100 text-amber-700' => ! $section->is_visible,
                            ])>
                                {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-slate-500">
                            {{ $section->contentBlocks->count() }} block tersimpan di section ini.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" wire:click="prepareNewBlock({{ $section->id }})"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Tambah Block
                        </button>
                        <button type="button" wire:click="editSection({{ $section->id }})"
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                            Edit Section
                        </button>
                    </div>
                </div>

                <div class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($section->contentBlocks as $block)
                        <div wire:key="cms-block-{{ $block->id }}"
                            class="rounded-[1.25rem] border border-slate-100 bg-slate-50/50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="rounded-full bg-slate-900 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-white">
                                        {{ \Illuminate\Support\Str::headline($block->blockType?->name ?? 'Block') }}
                                    </span>
                                    <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Urutan {{ $block->sort_order }}
                                    </p>
                                </div>
                                <button type="button" wire:click="editBlock({{ $block->id }})"
                                    class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                                    Edit
                                </button>
                            </div>

                            @if ($block->isText())
                                <p class="mt-4 text-sm leading-6 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit($block->textContent?->value ?? '-', 180) }}
                                </p>
                            @elseif ($block->imageContent?->mediaFile)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($block->imageContent->mediaFile->file_path) }}"
                                    alt="{{ $block->imageContent->alt_text ?: 'Gambar block CMS' }}"
                                    class="mt-4 h-40 w-full rounded-2xl object-cover">
                                @if ($block->imageContent->alt_text)
                                    <p class="mt-3 text-sm font-semibold text-slate-800">{{ $block->imageContent->alt_text }}</p>
                                @endif
                                @if ($block->imageContent->caption)
                                    <p class="mt-1 text-sm text-slate-500">{{ $block->imageContent->caption }}</p>
                                @endif
                            @endif

                            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    #{{ $block->id }}
                                </span>
                                <button type="button" wire:click="deleteBlock({{ $block->id }})"
                                    class="text-sm font-semibold text-red-600 transition hover:text-red-700">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 xl:col-span-3">
                            <div class="rounded-[1.25rem] border-2 border-dashed border-slate-200 bg-slate-50/70 px-6 py-10 text-center">
                                <p class="text-sm font-semibold text-slate-700">Belum ada block untuk section ini.</p>
                                <p class="mt-1 text-xs text-slate-500">Klik tombol "Tambah Block" untuk mulai mengisi konten.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="rounded-[1.5rem] bg-white px-6 py-16 text-center shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="mt-5 text-lg font-bold text-slate-900">Belum ada section</h3>
                <p class="mt-2 text-sm text-slate-500">Tambahkan section pertama untuk tab halaman yang sedang aktif.</p>
            </div>
        @endforelse
    </div>
</div>
