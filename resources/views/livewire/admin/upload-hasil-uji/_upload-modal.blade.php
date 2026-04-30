    {{-- Upload Modal --}}
    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = false"
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
                    class="relative w-full overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-2xl">
                    <form wire:submit="uploadParticipantFiles" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $selectedUploadRegistration?->active_certificate_id ? 'Perbarui Surat Keterangan / Sertifikat' : ($selectedUploadRegistration?->status === 'kompeten' ? 'Upload Sertifikat Copy' : 'Upload Surat Keterangan') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">
                                        {{ $selectedUploadRegistration?->user?->name ?: '-' }}
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
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Keputusan
                                        Hasil Uji</label>
                                    <select wire:model.live="examResult"
                                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                                        <option value="kompeten">Lulus (Kompeten)</option>
                                        <option value="belum_kompeten">Tidak Lolos (Belum Kompeten)</option>
                                    </select>
                                    @error('examResult')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($examResult === 'kompeten')
                                    <div class="grid gap-5 md:grid-cols-2">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">File
                                                Sertifikat Copy (PDF)</label>
                                            <div class="relative">
                                                <input wire:model="certificateFile" type="file" accept=".pdf"
                                                    class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white file:mr-3.5 file:rounded-lg file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white" />
                                            </div>
                                            @if ($selectedUploadRegistration?->active_certificate_id || $selectedUploadRegistration?->status === 'kompeten')
                                                <p class="mt-2 text-xs text-gray-400 italic">Kosongkan jika tidak
                                                    ada perubahan</p>
                                            @endif
                                            @error('certificateFile')
                                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Masa
                                                Berlaku</label>
                                            <input wire:model="expiredDate" type="date"
                                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                            @error('expiredDate')
                                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">File
                                        Surat Keterangan (PDF)</label>
                                    <input wire:model="resultFile" type="file" accept=".pdf"
                                        class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white file:mr-3.5 file:rounded-lg file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white" />
                                    @if (
                                        ($examResult === 'kompeten' &&
                                            ($selectedUploadRegistration?->active_certificate_id ||
                                                $selectedUploadRegistration?->result_file_url)) ||
                                            ($examResult === 'belum_kompeten' && $selectedUploadRegistration?->exam_result_path))
                                        <p class="mt-2 text-xs text-gray-400 italic">Kosongkan jika tidak ada
                                            perubahan</p>
                                    @endif
                                    @error('resultFile')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                                    <div class="flex gap-3">
                                        <svg class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs leading-relaxed text-blue-700 font-medium">
                                            @if ($selectedUploadRegistration?->active_certificate_id)
                                                <span class="font-bold">Informasi:</span> Anda sedang memperbarui
                                                berkas. Berkas lama tetap aman jika tidak diunggah ulang.
                                            @else
                                                <span class="font-bold">Penting:</span> Peserta akan langsung dapat
                                                melihat status
                                                {{ $examResult === 'kompeten' ? 'Lulus' : 'Tidak Lolos' }} di dashboard
                                                mereka setelah Anda menekan tombol Simpan. Untuk peserta kompeten, surat keterangan wajib tersedia sebelum sertifikat copy diunggah.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500">
                                <span>Simpan Berkas</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

