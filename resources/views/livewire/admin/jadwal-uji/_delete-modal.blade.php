    {{-- Delete Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full overflow-hidden rounded-[1.25rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-lg">
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
                                <h3 class="text-xl font-bold text-gray-900">Hapus Jadwal Uji</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                    Jadwal untuk <span
                                        class="font-bold text-gray-800">{{ $selectedDeleteRegistration?->user?->name ?: 'peserta ini' }}</span>
                                    akan dihapus dan status peserta otomatis dikembalikan menjadi Pembayaran Tervalidasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 flex items-center justify-end gap-3 px-6 py-4 md:px-8">
                        <button type="button" @click="show = false"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button wire:click="deleteSchedule" type="button"
                            class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-100 transition-all hover:bg-red-700">
                            Hapus Jadwal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
