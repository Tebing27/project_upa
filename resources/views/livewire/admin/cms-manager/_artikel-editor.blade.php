<form wire:submit="saveFullArticle" class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start relative">
    <div class="xl:col-span-3 mb-[-1rem]">
       <button type="button" wire:click="closeEditor" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition">
           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
           </svg>
           Kembali ke List Artikel
       </button>
    </div>
    <!-- Left Column: Title & Editor -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-[1.5rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-6">
            <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500 mb-6">Artikel & Konten</h2>
            
            <div class="space-y-6">
                <!-- Judul Artikel -->
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Judul Artikel
                    </label>
                    <input type="text" wire:model.live="pageForm.title"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Contoh: Polri Buka Layanan Pengaduan..." />
                    @error('pageForm.title')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL Publik -->
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                        URL Publik
                    </label>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-600">
                        {{ filled($pageForm['title']) ? '/artikel/' . \Illuminate\Support\Str::slug($pageForm['title']) . ($articleId ? '-' . $articleId : '-{id}') : '/artikel/{judul}-{id}' }}
                    </div>
                </div>

                @if ($articleId)
                <!-- Quill Editor -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Isi Artikel</label>
                        <p class="text-[11px] text-slate-400">Ringkasan otomatis di-generate tanpa heading.</p>
                    </div>
                    <div wire:ignore x-data="quillArticleEditor(@entangle('articleForm.body'), { placeholder: 'Tulis isi artikel di sini...' })"
                        class="article-quill relative rounded-[1.5rem] border border-slate-200 bg-white">
                        
                        <!-- Custom Caption Modal -->
                        <div x-show="captionModalOpen" x-transition.opacity x-cloak class="absolute z-50 p-5 bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.15)] border border-slate-100" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 340px; max-width: 90%;">
                            <p class="font-bold text-sm text-slate-800 mb-3">Atur Caption Gambar</p>
                            <input type="text" x-model="captionText" @keydown.enter.prevent="saveCaption()" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition" placeholder="Teks caption untuk gambar..." x-ref="captionInput">
                            <div class="mt-4 flex justify-end gap-3">
                                <button type="button" @click="closeCaptionModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                                <button type="button" @click="saveCaption()" class="px-4 py-2 text-xs font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl transition shadow-sm">Simpan</button>
                            </div>
                        </div>

                        <div x-ref="editor"></div>
                    </div>
                    @error('articleForm.body')
                        <p class="text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @else
                <div class="rounded-[1.25rem] border border-dashed border-orange-200 bg-orange-50 px-5 py-6 text-sm text-orange-700">
                    Simpan judul artikel terlebih dahulu untuk membuka editor selengkapnya.
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 pt-6 border-t border-slate-100">
                @if ($articleId)
                    <button type="button" wire:click="deletePage({{ $articleId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Artikel
                    </button>
                @else
                    <span class="text-sm text-slate-500">Artikel baru</span>
                @endif

                <div class="flex gap-3">
                    <button type="button" wire:click="closeEditor"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-orange-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-orange-500">
                        <span wire:loading.remove wire:target="saveFullArticle">{{ $isCreatingPage || !$articleId ? 'Simpan Artikel' : 'Update Artikel' }}</span>
                        <span wire:loading wire:target="saveFullArticle">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Metadata & Baca Juga -->
    <div class="space-y-6">
        <div class="bg-white rounded-[1.5rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-6 space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-orange-700">Metadata</h3>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Author</label>
                <input type="text" wire:model="pageForm.editor_name"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    placeholder="Contoh: Tim Humas UPA-LUK" />
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Publish</label>
                <input type="date" wire:model="pageForm.published_at"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" />
                @error('pageForm.published_at')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tags</label>
                <div x-data="{
                        tags: [],
                        newTag: '',
                        init() {
                            let initial = $wire.get('pageForm.tags');
                            this.tags = initial ? initial.split(',').map(t => t.trim()).filter(Boolean) : [];
                            $watch('$wire.pageForm.tags', value => {
                                if (value !== this.tags.join(', ')) {
                                    this.tags = value ? value.split(',').map(t => t.trim()).filter(Boolean) : [];
                                }
                            });
                        },
                        addTag() {
                            let tag = this.newTag.trim();
                            if (tag !== '' && !this.tags.includes(tag)) {
                                this.tags.push(tag);
                                this.updateWire();
                            }
                            this.newTag = '';
                        },
                        removeTag(index) {
                            this.tags.splice(index, 1);
                            this.updateWire();
                        },
                        updateWire() {
                            $wire.set('pageForm.tags', this.tags.join(', '));
                        }
                    }">
                    <div class="flex flex-wrap gap-2 mb-3" x-show="tags.length > 0" x-cloak>
                        <template x-for="(tag, index) in tags" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span x-text="tag"></span>
                                <button type="button" @click="removeTag(index)" class="text-emerald-400 hover:text-emerald-600 focus:outline-none transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                    <input type="text" x-model="newTag" @keydown.enter.prevent="addTag"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        placeholder="Ketik tag lalu tekan enter" />
                </div>
            </div>

            <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/50 px-5 py-4 cursor-pointer hover:bg-slate-50 transition">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Status Publish</p>
                    <p class="mt-1 text-xs text-slate-500">Tampil di publik</p>
                </div>
                <input type="checkbox" wire:model="pageForm.is_published"
                    class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30">
            </label>
        </div>

        @if ($articleId)
        <div class="bg-white rounded-[1.5rem] shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-6"
            x-data="{
                open: true,
                search: '',
                selected: $wire.entangle('articleForm.related_article_ids').live,
                toggle(id) {
                    const numericId = Number(id);
                    this.selected = this.selected.includes(numericId) ?
                        this.selected.filter((value) => value !== numericId) :
                        [...this.selected, numericId];
                },
                matches(title) {
                    return title.toLowerCase().includes(this.search.toLowerCase());
                },
            }">
            <div class="mb-4">
                <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Baca Juga</h3>
                <p class="mt-1 text-xs text-slate-500">Pilih artikel terkait.</p>
            </div>

            <button type="button" x-on:click="open = !open"
                class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm font-semibold text-slate-900 overflow-hidden">
                <span class="truncate pr-2" x-text="selected.length ? `${selected.length} artikel dipilih` : 'Pilih artikel terkait'"></span>
                <svg class="h-4 w-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-cloak class="mt-3">
                <input type="text" x-model="search"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                    placeholder="Cari judul artikel..." />

                <div class="mt-3 max-h-64 space-y-2 overflow-y-auto pr-1">
                    @forelse ($this->availableRecommendedArticles as $articleOption)
                        <label x-show="matches(@js($articleOption->title))" x-cloak
                            class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 px-3 py-3 transition hover:bg-slate-50">
                            <input type="checkbox"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30"
                                :checked="selected.includes({{ $articleOption->id }})"
                                x-on:change="toggle({{ $articleOption->id }})">
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-slate-900 line-clamp-2">{{ $articleOption->title }}</span>
                                <span class="mt-1 block text-xs text-slate-500">{{ optional($articleOption->published_at)->format('d M Y') ?? 'Belum publish' }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="rounded-xl bg-slate-50 px-4 py-4 text-sm text-slate-500">Belum ada artikel lain.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</form>
