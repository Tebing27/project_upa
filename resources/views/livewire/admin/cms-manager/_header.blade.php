    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">CMS Website</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola landing page, artikel, dan gallery dalam satu workspace admin.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="rounded-xl bg-white px-4 py-3 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Mode Aktif</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ match ($cmsTab) {
                        'artikel' => 'Artikel Editor',
                        'gallery' => 'Gallery Manager',
                        default => 'Landing Page Manager',
                    } }}
                </p>
            </div>
            <button type="button" wire:click="startCreatingPage"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $cmsTab === 'artikel' ? 'Artikel Baru' : ($cmsTab === 'gallery' ? 'Gallery Baru' : 'Halaman Baru') }}
            </button>
        </div>
    </div>
