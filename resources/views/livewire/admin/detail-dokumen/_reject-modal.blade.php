    {{-- Modal Tolak --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-tolak') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-tolak') show = false"
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
                    class="relative overflow-hidden w-full rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-md">

                    <form wire:submit="tolakDokumen">
                        <div class="p-6 md:p-8">
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Tolak Dokumen</h3>
                                <p class="text-sm text-gray-500 mt-1">Sampaikan alasan dokumen tidak valid agar peserta
                                    dapat memperbaiki unggahannya.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Catatan
                                    Penolakan</label>
                                <textarea wire:model="rejectNote" rows="3"
                                    placeholder="Contoh: Dokumen buram, Nama tidak sesuai, atau File tidak valid."
                                    class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-red-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                    required></textarea>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-4 py-2.5 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                                <span>Tolak Dokumen</span>
                                <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
