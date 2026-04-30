    {{-- Modal Delete --}}
    <div x-data="{ show: false }"
        x-on:open-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = true"
        x-on:close-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">

        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative overflow-hidden rounded-[2rem] w-full bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-lg">

                    <div class="p-6 md:p-8">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hapus Skema</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">Apakah Anda yakin ingin menghapus
                                    skema ini? Data skema yang dihapus tidak dapat dikembalikan. Pastikan tidak ada
                                    peserta yang terkait dengan skema ini.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                        <button type="button" @click="show = false"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="delete"
                            class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                            <span wire:loading.remove wire:target="delete">Ya, Hapus Skema</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
