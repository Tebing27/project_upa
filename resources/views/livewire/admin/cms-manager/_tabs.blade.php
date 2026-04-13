    <div class="space-y-4 rounded-[1.5rem] bg-white p-4 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-wrap gap-3">
            <button type="button" wire:click="switchCmsTab('landing')" @class([
                'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                'bg-slate-900 text-white shadow-sm' => $cmsTab === 'landing',
                'bg-slate-100 text-slate-600 hover:bg-slate-200' => $cmsTab !== 'landing',
            ])>
                Landing Page
            </button>
            <button type="button" wire:click="switchCmsTab('artikel')" @class([
                'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                'bg-slate-900 text-white shadow-sm' => $cmsTab === 'artikel',
                'bg-slate-100 text-slate-600 hover:bg-slate-200' => $cmsTab !== 'artikel',
            ])>
                Artikel
            </button>
            <button type="button" wire:click="switchCmsTab('gallery')" @class([
                'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                'bg-slate-900 text-white shadow-sm' => $cmsTab === 'gallery',
                'bg-slate-100 text-slate-600 hover:bg-slate-200' => $cmsTab !== 'gallery',
            ])>
                Gallery
            </button>
        </div>

        @if ($cmsTab !== 'artikel')
            <div class="overflow-x-auto">
                <div class="flex min-w-max items-center gap-3">
                    <div class="flex gap-1 rounded-xl bg-slate-100 p-1">
                        @foreach ($this->filteredPages as $page)
                            <button type="button" wire:key="page-tab-{{ $page->id }}"
                                wire:click="selectPage('{{ $page->slug }}')" @class([
                                    'inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-all',
                                    'bg-white text-gray-900 shadow-sm' =>
                                        !$isCreatingPage && $activePageSlug === $page->slug,
                                    'text-gray-500 hover:text-gray-700' =>
                                        $isCreatingPage || $activePageSlug !== $page->slug,
                                ])>
                                <span>{{ $page->title }}</span>
                                <span @class([
                                    'inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-bold',
                                    'bg-emerald-100 text-emerald-700' =>
                                        !$isCreatingPage && $activePageSlug === $page->slug,
                                    'bg-slate-200 text-slate-600' =>
                                        $isCreatingPage || $activePageSlug !== $page->slug,
                                ])>
                                    {{ $page->page_sections_count }}
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
        @endif
    </div>
