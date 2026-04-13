        <form wire:submit="savePage"
            class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-slate-500">Pengaturan Halaman</h2>
                    <p class="mt-1 text-sm text-slate-500">Tab di atas diambil dari tabel `pages` dan bisa dikelola
                        langsung dari sini.</p>
                </div>
                @if ($this->activePage && !$isCreatingPage)
                    <button type="button" wire:click="editPage({{ $this->activePage->id }})"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Edit Halaman Aktif
                    </button>
                @elseif ($isCreatingPage)
                    <span
                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Mode Tab Baru
                    </span>
                @endif
            </div>

            <div class="space-y-5 p-6">
                @if ($pageType === 'artikel')
                    <div class="grid gap-5 md:grid-cols-2">
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

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                URL Publik
                            </label>
                            <div
                                class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-600">
                                {{ filled($pageForm['title']) ? '/artikel/' . \Illuminate\Support\Str::slug($pageForm['title']) . ($articleId ? '-' . $articleId : '-{id}') : '/artikel/{judul}-{id}' }}
                            </div>
                            <p class="mt-1.5 text-[11px] text-slate-400">Format URL publik selalu
                                `judul-idartikelunik`.</p>
                        </div>
                    @else
                        <div class="grid gap-5 md:grid-cols-3">
                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Tipe Halaman
                                </label>
                                <div
                                    class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900">
                                    {{ match ($pageType) {
                                        'artikel' => 'Artikel / Berita',
                                        'galeri' => 'Gallery / Kegiatan',
                                        default => 'Landing Page / Halaman Statis',
                                    } }}
                                </div>
                                <p class="mt-1.5 text-[11px] text-slate-400">Tipe mengikuti tab CMS yang sedang aktif.
                                </p>
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Judul Halaman
                                </label>
                                <input type="text" wire:model.live="pageForm.title"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Contoh: Home" />
                                @error('pageForm.title')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Slug
                                </label>
                                <input type="text" wire:model="pageForm.slug"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="home" />
                                @error('pageForm.slug')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-[11px] text-slate-400">
                                    @if ($pageType === 'artikel')
                                        Slug internal CMS otomatis pakai prefix `artikel-`. URL publik pakai format
                                        `judul-id`.
                                    @elseif ($pageType === 'galeri')
                                        Slug galeri otomatis pakai prefix `galeri-`.
                                    @else
                                        Slug bisa diubah manual kapan saja.
                                    @endif
                                </p>
                            </div>
                        </div>
                @endif

                @if ($pageType === 'artikel')
                    <div class="rounded-[1.5rem] border border-orange-100 bg-orange-50/60 p-5">
                        <div class="mb-5">
                            <h3 class="text-sm font-bold uppercase tracking-[0.22em] text-orange-700">Metadata Artikel
                            </h3>
                            <p class="mt-1 text-sm text-slate-600">Field ini dipakai untuk list artikel dan halaman
                                detail berita.</p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Nama Author
                                </label>
                                <input type="text" wire:model="pageForm.editor_name"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Contoh: Tim Humas UPA-LUK" />
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Tanggal Publish
                                </label>
                                <input type="date" wire:model="pageForm.published_at"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" />
                                @error('pageForm.published_at')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Tags
                            </label>
                            <input type="text" wire:model="pageForm.tags"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="Misal: Pengumuman, Akademik, Sertifikasi" />
                            <p class="mt-1.5 text-[11px] text-slate-400">Pisahkan dengan koma.</p>
                        </div>
                    </div>
                @endif

                <label
                    class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/50 px-5 py-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Status Publish</p>
                        <p class="mt-1 text-xs text-slate-500">Jika nonaktif, halaman tetap ada di CMS tetapi bisa
                            dianggap belum siap tampil.</p>
                    </div>
                    <input type="checkbox" wire:model="pageForm.is_published"
                        class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/30">
                </label>
            </div>

            <div
                class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                @if ($pageId)
                    <button type="button" wire:click="deletePage({{ $pageId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Tab Ini
                    </button>
                @elseif ($articleId)
                    <button type="button" wire:click="deletePage({{ $articleId }})"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                        Hapus Artikel
                    </button>
                @else
                    <span class="text-sm text-slate-500">Siap membuat tab halaman baru.</span>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    @if ($isCreatingPage)
                        @if ($pageType === 'artikel' && $this->articleEntries->isNotEmpty())
                            <button type="button"
                                wire:click="selectArticle({{ $this->articleEntries->first()->id }})"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Batal
                            </button>
                        @else
                            <button type="button" wire:click="selectPage('{{ $activePageSlug ?: 'home' }}')"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Batal
                            </button>
                        @endif
                    @else
                        <button type="button" wire:click="startCreatingPage"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Tab Baru
                        </button>
                    @endif
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500">
                        <span wire:loading.remove
                            wire:target="savePage">{{ $pageType === 'artikel' ? ($isCreatingPage ? 'Simpan Artikel' : 'Update Artikel') : ($isCreatingPage ? 'Simpan Tab Baru' : 'Update Halaman') }}</span>
                        <span wire:loading wire:target="savePage">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
