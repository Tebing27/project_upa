<div class="space-y-12">
    @forelse ($this->sections as $section)
        <div wire:key="cms-section-{{ $section->id }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Info Column -->
            <div class="lg:col-span-4 xl:col-span-3 lg:col-start-1">
                <div class="sticky top-6">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ \Illuminate\Support\Str::headline($section->sectionType?->name ?? 'Section') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Urutan ke-{{ $section->sort_order }} pada halaman ini.
                        <br><span @class([ 'mt-1 inline-block font-semibold', 'text-emerald-600' => $section->is_visible, 'text-amber-500' => !$section->is_visible ])>{{ $section->is_visible ? 'Terlihat (Visible)' : 'Disembunyikan (Hidden)' }}</span>
                    </p>
                    
                    <div class="mt-5 flex flex-col gap-3 items-start">
                        <button type="button" wire:click="editSection({{ $section->id }})" class="text-[13px] font-semibold text-emerald-600 hover:text-emerald-700 transition text-left">
                            Edit Properties Section
                        </button>
                        <button type="button" wire:click="prepareNewBlock({{ $section->id }})" class="mt-2 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            + ADD NEW BLOCK
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Blocks Card -->
            <div class="lg:col-span-8 xl:col-span-9">
                <div class="overflow-hidden rounded-[1.5rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)] ring-1 ring-slate-100/50">
                    <div class="flex flex-wrap items-center justify-between border-b border-slate-50 pb-4 mb-5">
                       <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Daftar Konten / Block</h4>
                       <span class="text-xs font-medium text-slate-500">{{ $section->contentBlocks->count() }} item</span>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        @forelse ($section->contentBlocks as $block)
                            <div wire:key="cms-block-{{ $block->id }}"
                                class="relative group rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:border-emerald-100 hover:bg-emerald-50/30">
                                
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <span class="rounded-md bg-white px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 shadow-sm ring-1 ring-slate-200/50">
                                        {{ \Illuminate\Support\Str::headline($block->blockType?->name ?? 'Block') }} ({{ $block->sort_order }})
                                    </span>
                                    
                                    <div class="flex items-center gap-3">
                                        <button type="button" wire:click="editBlock({{ $block->id }})"
                                            class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 transition hover:text-emerald-700">
                                            Edit
                                        </button>
                                        <button type="button" wire:click="deleteBlock({{ $block->id }})"
                                            class="text-[11px] font-bold uppercase tracking-wider text-red-500 transition hover:text-red-700">
                                            Hapus
                                        </button>
                                    </div>
                                </div>

                                @if ($block->isText())
                                    <div class="prose prose-sm prose-slate max-w-none text-slate-600">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($block->textContent?->value ?? '-'), 180) !!}
                                    </div>
                                @elseif ($block->imageContent?->mediaFile)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($block->imageContent->mediaFile->file_path) }}"
                                        alt="{{ $block->imageContent->alt_text ?: 'Gambar' }}"
                                        class="h-40 w-full rounded-xl object-cover ring-1 ring-slate-100 shadow-sm">
                                    @if ($block->imageContent->alt_text)
                                        <p class="mt-3 text-xs font-semibold text-slate-800">{{ $block->imageContent->alt_text }}</p>
                                    @endif
                                    @if ($block->imageContent->caption)
                                        <p class="mt-1 text-xs text-slate-500">{{ $block->imageContent->caption }}</p>
                                    @endif
                                @endif
                                
                                <div class="mt-4 pt-4 border-t border-slate-100/60">
                                     <span class="text-[10px] font-semibold text-slate-400">ID: #{{ $block->id }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="md:col-span-2 text-center py-10 px-6 rounded-2xl border-2 border-dashed border-slate-100 bg-slate-50/50">
                                <p class="text-sm font-semibold text-slate-700">Belum ada konten di section ini.</p>
                                <p class="mt-1 text-xs text-slate-500">Isi data blocks (text/image) agar section ini dapat ditampilkan optimal.</p>
                                <button type="button" wire:click="prepareNewBlock({{ $section->id }})" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                                    Mulai Tambah Block
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        @if (!$loop->last)
            <hr class="border-slate-100 mb-2">
        @endif
    @empty
        <div class="rounded-[1.5rem] border border-slate-100 bg-white px-6 py-16 text-center shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)]">
            <h3 class="text-lg font-bold text-slate-900">Belum ada section</h3>
            <p class="mt-2 text-sm text-slate-500">Tambahkan section pertama untuk mengatur struktur halaman ini.</p>
            <button type="button" wire:click="prepareNewSection" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                + Buat Section
            </button>
        </div>
    @endforelse
</div>
