    {{-- WhatsApp Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-whatsapp-link') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-whatsapp-link') show = false"
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
                    <form wire:submit="saveWhatsappLink" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $editingWhatsappSettingId ? 'Edit Link WhatsApp' : 'Tambah Link WhatsApp' }}
                                    </h3>
                                    <p class="mt-1 text-sm font-medium text-gray-500">
                                        Link ini akan dipakai bersama untuk semua peserta yang sudah dijadwalkan.
                                    </p>
                                </div>
                                <button type="button" @click="show = false; $wire.resetWhatsappLinkForm()"
                                    class="text-gray-400 transition-colors hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                                    Saluran / Grup WhatsApp</label>
                                <input wire:model="whatsappLink" type="url" placeholder="https://chat.whatsapp.com/..."
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                @error('whatsappLink')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 border-t border-slate-100 bg-slate-50/50 px-6 py-5 md:px-8">
                            <button type="button" wire:click="resetWhatsappLinkForm" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="group relative inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-8 py-3.5 font-bold text-black transition-all shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                                <span wire:loading.remove>{{ $editingWhatsappSettingId ? 'Update Link' : 'Simpan Link' }}</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

