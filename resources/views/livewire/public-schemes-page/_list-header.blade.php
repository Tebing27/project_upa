            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-700">Daftar Skema</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Pilihan skema yang tersedia</h2>
                </div>

                <div class="flex gap-1 rounded-2xl bg-slate-100 p-1">
                    <button wire:click="$set('sortOption', 'terbaru')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'terbaru',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'terbaru',
                    ])>
                        Terbaru
                    </button>
                    <button wire:click="$set('sortOption', 'semua')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'semua',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'semua',
                    ])>
                        A-Z
                    </button>
                    <button wire:click="$set('sortOption', 'populer')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'populer',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'populer',
                    ])>
                        Populer
                    </button>
                </div>
            </div>
