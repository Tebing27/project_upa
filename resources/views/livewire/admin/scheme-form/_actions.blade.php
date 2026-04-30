        {{-- Actions --}}
        <div class="flex items-center justify-between rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] px-6 py-5">
            <a href="{{ route('admin.schemes') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 px-8 py-3 font-bold text-black bg-emerald-400 rounded-xl hover:bg-emerald-500 transition-all">
                <span wire:loading.remove wire:target="save">Simpan Skema</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
                <svg wire:loading.remove wire:target="save"
                    class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7-7 7M3 12h18" />
                </svg>
            </button>
        </div>
