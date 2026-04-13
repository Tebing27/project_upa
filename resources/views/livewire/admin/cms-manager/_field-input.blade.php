{{-- Kiri: label + hint --}}
<div class="md:col-span-2">
    <p class="text-sm font-semibold text-slate-800">{{ $field->label }}</p>
    @if ($field->description)
        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $field->description }}
        </p>
    @endif
    <span
        class="mt-2 inline-block rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
        {{ $field->type }}
    </span>
</div>

{{-- Kanan: form field --}}
<div class="md:col-span-3">

    @if ($field->isImage())
        {{-- Image upload with preview --}}
        <div x-data="{
            previewUrl: '{{ $field->value?->imageUrl() ?? '' }}',
            handleFile(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.previewUrl = URL.createObjectURL(file);
            }
        }">
            @if ($field->value?->imageUrl())
                <div class="mb-3">
                    <img :src="previewUrl || '{{ $field->value?->imageUrl() }}'"
                        x-show="previewUrl"
                        class="h-40 w-full rounded-xl object-cover ring-1 ring-slate-200"
                        alt="{{ $field->label }}">
                </div>
            @else
                <div x-show="previewUrl" class="mb-3" style="display: none;">
                    <img :src="previewUrl"
                        class="h-40 w-full rounded-xl object-cover ring-1 ring-slate-200"
                        alt="{{ $field->label }}">
                </div>
            @endif

            <label
                class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 transition hover:border-emerald-400 hover:bg-emerald-50/40">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm text-slate-600">
                    {{ $field->value?->mediaFile ? 'Ganti gambar' : 'Upload gambar' }}
                </span>
                <input type="file" accept="image/*" class="sr-only"
                    wire:model="fieldImages.{{ $field->id }}"
                    @change="handleFile($event)">
            </label>

            @if ($field->value?->mediaFile)
                <p class="mt-1.5 text-[11px] text-slate-400">
                    File saat ini: {{ $field->value->mediaFile->file_name }}
                </p>
            @endif

            <div wire:loading wire:target="fieldImages.{{ $field->id }}"
                class="mt-2 text-xs text-emerald-600">Mengupload...</div>
        </div>

    @elseif ($field->isRichText())
        {{-- Rich text via Quill --}}
        <div
            x-data="quillArticleEditor($wire.entangle('fieldValues.{{ $field->id }}'), { compact: true, placeholder: 'Tulis konten di sini...' })"
            class="article-quill article-quill--compact overflow-hidden rounded-xl border border-slate-200 bg-white focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-400/20">
            <div x-ref="editor"></div>
        </div>

    @elseif ($field->isTextarea())
        {{-- Textarea --}}
        <textarea wire:model="fieldValues.{{ $field->id }}" rows="4"
            placeholder="Tulis teks di sini..."
            class="block w-full resize-none rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"></textarea>

    @else
        {{-- Text / URL --}}
        <div class="relative">
            @if ($field->isUrl())
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </span>
            @endif
            <input type="{{ $field->isUrl() ? 'url' : 'text' }}"
                wire:model="fieldValues.{{ $field->id }}"
                placeholder="{{ $field->isUrl() ? 'https://...' : 'Tulis ' . $field->label . '...' }}"
                class="block w-full rounded-xl border border-slate-200 bg-slate-50/60 py-3 text-sm font-medium text-slate-900 outline-none transition hover:bg-white hover:border-slate-300 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 {{ $field->isUrl() ? 'pl-9 pr-4' : 'px-4' }}">
        </div>
    @endif

</div>
