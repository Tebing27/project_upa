{{-- Page Fields Form — layout dua kolom per section --}}
@if ($this->activePageSections->isEmpty())
    <div
        class="flex flex-col items-center justify-center rounded-[1.5rem] bg-white px-6 py-16 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Belum ada field yang dikonfigurasi</h3>
        <p class="mt-1 max-w-xs text-center text-sm text-slate-500">Halaman <strong>{{ $activePageSlug }}</strong>
            belum memiliki struktur field. Jalankan <code
                class="rounded bg-slate-100 px-1 py-0.5 text-xs">php artisan db:seed --class=PageFieldsSeeder</code>
            untuk mengisi struktur.</p>
    </div>
@else
    <div class="space-y-6">
        @foreach ($this->activePageSections as $pageSection)
            <div wire:key="section-{{ $pageSection->id }}"
                class="overflow-hidden rounded-[1.5rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]"
                x-data="{ sectionId: {{ $pageSection->id }} }">

                {{-- Section header --}}
                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-500">
                            {{ $pageSection->label }}
                        </h3>
                        @if (! $pageSection->is_visible)
                            <span
                                class="mt-1 inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-amber-700">
                                Tersembunyi
                            </span>
                        @endif
                    </div>

                    @if (isset($lastSavedAt[$pageSection->id]))
                        <span class="text-xs text-slate-400" wire:key="saved-ts-{{ $pageSection->id }}">
                            ✓ Tersimpan pukul {{ $lastSavedAt[$pageSection->id] }}
                        </span>
                    @endif
                </div>

                {{-- Fields --}}
                <div class="divide-y divide-slate-50">
                    @if ($pageSection->section_key === 'home_testimonials')
                        @php
                            $testiGroups = $pageSection->fields->groupBy(function($item) {
                                if (preg_match('/^(testi_[a-z0-9]+)_/i', $item->field_key, $matches)) {
                                    return $matches[1];
                                }
                                return 'other';
                            });
                        @endphp
                        @foreach ($testiGroups as $prefix => $groupFields)
                            <div wire:key="group-{{ $prefix }}" class="p-6 border-b border-slate-100 last:border-b-0 bg-slate-50/30">
                                <div class="flex justify-between items-center mb-6">
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Testimoni</h4>
                                    <button type="button" wire:click="deleteTestimonialGroup('{{ $prefix }}')" wire:confirm="Yakin ingin menghapus testimoni ini?" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-full inline-flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus Testimoni
                                    </button>
                                </div>
                                <div class="space-y-6">
                                    @foreach ($groupFields as $field)
                                        <div wire:key="field-{{ $field->id }}" class="grid grid-cols-1 gap-6 md:grid-cols-5 border-l-2 border-slate-200 pl-4 py-2 relative group/field">
                                            @include('livewire.admin.cms-manager._field-input')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @elseif ($pageSection->section_key === 'faq_items')
                        @php
                            $faqGroups = $pageSection->fields->groupBy(function($item) {
                                if (preg_match('/^(faq_[a-z0-9]+)_/i', $item->field_key, $matches)) {
                                    return $matches[1];
                                }
                                return 'other';
                            });
                        @endphp
                        @foreach ($faqGroups as $prefix => $groupFields)
                            <div wire:key="faq-group-{{ $prefix }}" class="border-b border-slate-100 bg-slate-50/30 p-6 last:border-b-0">
                                <div class="mb-6 flex items-center justify-between">
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Item FAQ</h4>
                                    <button type="button" wire:click="deleteFaqGroup('{{ $prefix }}')" wire:confirm="Yakin ingin menghapus item FAQ ini?" class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-500 transition hover:text-red-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus FAQ
                                    </button>
                                </div>
                                <div class="space-y-6">
                                    @foreach ($groupFields as $field)
                                        <div wire:key="faq-field-{{ $field->id }}" class="relative grid grid-cols-1 gap-6 border-l-2 border-slate-200 py-2 pl-4 md:grid-cols-5">
                                            @include('livewire.admin.cms-manager._field-input')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach ($pageSection->fields as $field)
                            <div wire:key="field-{{ $field->id }}"
                                class="grid grid-cols-1 gap-6 p-6 md:grid-cols-5 relative group/field">
                                
                                @include('livewire.admin.cms-manager._field-input')

                                @if ($pageSection->section_key === 'hero_slider' && preg_match('/^slide_[a-z0-9]+$/', $field->field_key))
                                    <button type="button" wire:click="deleteField({{ $field->id }})" wire:confirm="Hapus gambar slide ini?" class="absolute top-6 right-6 opacity-0 group-hover/field:opacity-100 bg-white shadow-sm border border-red-100 text-red-500 hover:text-white hover:bg-red-500 flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus
                                    </button>
                                @endif
                                
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Section footer — Save button --}}
                <div
                    class="flex items-center justify-between border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                    <div class="text-xs text-slate-400">
                        {{ $pageSection->fields->count() }} field
                        &middot; key: <code class="text-slate-500">{{ $pageSection->section_key }}</code>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($pageSection->section_key === 'hero_slider')
                            <button type="button" wire:click="addSliderImage({{ $pageSection->id }})" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah Slide
                            </button>
                        @endif

                        @if ($pageSection->section_key === 'home_testimonials')
                            <button type="button" wire:click="addTestimonial({{ $pageSection->id }})" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah Testimoni
                            </button>
                        @endif

                        @if ($pageSection->section_key === 'faq_items')
                            <button type="button" wire:click="addFaqItem({{ $pageSection->id }})" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah FAQ
                            </button>
                        @endif

                        <button type="button"
                            wire:click="saveFieldValues({{ $pageSection->id }})"
                            wire:loading.attr="disabled"
                            wire:target="saveFieldValues({{ $pageSection->id }})"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black transition hover:bg-emerald-500 disabled:opacity-60">
                            <span wire:loading.remove wire:target="saveFieldValues({{ $pageSection->id }})">
                                Simpan Perubahan
                            </span>
                            <span wire:loading wire:target="saveFieldValues({{ $pageSection->id }})">
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endif
