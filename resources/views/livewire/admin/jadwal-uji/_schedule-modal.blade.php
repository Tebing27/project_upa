    {{-- Schedule Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-jadwal') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative w-full overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-xl">
                    <form wire:submit="saveSchedule" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $selectedScheduleRegistration?->status === 'terjadwal' ? 'Edit Jadwal Uji' : 'Atur Jadwal Uji' }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">
                                        {{ $selectedScheduleRegistration?->user?->name ?: '-' }}
                                    </p>
                                </div>
                                <button type="button" @click="show = false"
                                    class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-6">
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Tanggal
                                            Uji</label>
                                        <input wire:model="examDate" type="date"
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                        @error('examDate')
                                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Waktu
                                            Uji</label>
                                        <input wire:model="examTime" type="time"
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                        @error('examTime')
                                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Lokasi
                                        Tempat Uji</label>
                                    <input wire:model="examLocation" type="text"
                                        placeholder="Gedung Ki Hajar Dewantara Lt. 3"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none" />
                                    @error('examLocation')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Nama
                                        Penguji</label>
                                    <input wire:model="assessorName" type="text"
                                        class="block w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                                        placeholder="Contoh: Dr. John Doe, M.Kom" />
                                    @error('assessorName')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove>Simpan Jadwal</span>
                                <span wire:loading>Menyimpan...</span>
                                <svg wire:loading.remove
                                    class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7M3 12h18" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

