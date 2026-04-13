        <form wire:submit="saveArticleContent"
            class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Editor Artikel</h2>
                <p class="mt-1 text-sm text-slate-500">Pakai Quill editor. Drag, drop, paste, atau pilih gambar
                    langsung dari toolbar.</p>
            </div>

            <div class="space-y-6 p-6">
                @if (!$articleId)
                    <div
                        class="rounded-[1.25rem] border border-dashed border-orange-200 bg-orange-50 px-5 py-6 text-sm text-orange-700">
                        Simpan artikel dulu, lalu editor penuh akan aktif.
                    </div>
                @else
                    <div>
                        <label
                            class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Ringkasan</label>
                        <textarea wire:model="articleForm.excerpt" rows="3"
                            class="block w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="Ringkasan singkat untuk kartu berita..."></textarea>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Body
                                Artikel</label>
                            <p class="text-xs text-slate-400">Quill aktif dengan dukungan drag/drop, paste, dan
                                upload gambar lokal.</p>
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

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/60 p-5"
                        x-data="{
                            open: false,
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
                            <p class="mt-1 text-sm text-slate-500">Pilih artikel yang muncul di tengah isi berita.
                            </p>
                        </div>

                        <button type="button" x-on:click="open = !open"
                            class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-900">
                            <span
                                x-text="selected.length ? `${selected.length} artikel dipilih` : 'Pilih artikel terkait'"></span>
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak class="mt-3 rounded-2xl border border-slate-200 bg-white p-3">
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
                                            <span
                                                class="block text-sm font-semibold text-slate-900">{{ $articleOption->title }}</span>
                                            <span
                                                class="mt-1 block text-xs text-slate-500">{{ optional($articleOption->published_at)->format('d M Y') ?? 'Belum publish' }}</span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="rounded-xl bg-slate-50 px-4 py-4 text-sm text-slate-500">Belum ada
                                        artikel lain untuk dipilih.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div
                class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                <button type="submit" @disabled(!$articleId)
                    class="inline-flex items-center gap-2 rounded-xl bg-orange-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-orange-500 disabled:cursor-not-allowed disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveArticleContent">Simpan Artikel</span>
                    <span wire:loading wire:target="saveArticleContent">Menyimpan...</span>
                </button>
            </div>
        </form>
