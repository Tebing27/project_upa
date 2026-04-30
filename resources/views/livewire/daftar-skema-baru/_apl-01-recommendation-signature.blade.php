<div class="mb-6 border-b border-gray-100 pb-4">
    <h3 class="text-xl font-bold italic text-gray-900">Bagian 4 : Rekomendasi & Tanda Tangan</h3>
    <p class="mt-1 text-xs italic text-gray-400">Berikan tanda tangan digital Anda sebagai pernyataan bahwa
        data yang dimasukkan adalah benar.</p>
</div>

<div class="overflow-x-auto rounded-xl border border-stone-400 bg-[#fffdf8] shadow-sm">
    <table class="w-full min-w-[600px] table-fixed border-collapse text-sm">
        <tbody>
            <tr>
                <td class="w-[52%] border-r border-b border-stone-400 p-4 align-top">
                    <p class="text-[13px] font-bold text-stone-800">Rekomendasi (diisi oleh LSP):</p>
                    <p class="mt-1 text-[13px] leading-6 text-stone-700">Berdasarkan ketentuan persyaratan
                        dasar, maka pemohon:</p>
                    <p class="mt-2 text-[13px] font-semibold text-stone-800">Diterima / Tidak diterima *)
                        sebagai peserta sertifikasi</p>

                </td>
                <td class="border-b border-stone-400 p-0 align-top">
                    <table class="w-full table-fixed border-collapse text-sm">
                        <tbody>
                            <tr>
                                <td colspan="2"
                                    class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">
                                    Pemohon / Kandidat :</td>
                            </tr>
                            <tr>
                                <td
                                    class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                    Nama</td>
                                <td class="border-b border-stone-400 px-4 py-3 align-top">
                                    <p class="text-[13px] font-medium text-stone-900">
                                        {{ $name ?: 'Belum diisi' }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                    Tanda tangan/
                                    <br>
                                    Tanggal
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <div wire:ignore x-data="{
                                        signaturePad: null,
                                        resizeHandler: null,
                                        currentSignature: @js($applicantSignature),
                                        isDrawing: false,
                                        init() {
                                            const canvas = this.$refs.signatureCanvas;
                                            const SignaturePadLib = window.SignaturePad;
                                    
                                            if (!SignaturePadLib) {
                                                return;
                                            }
                                    
                                            this.resizeHandler = () => {
                                                if (this.isDrawing) {
                                                    return;
                                                }
                                    
                                                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                                const currentValue = this.currentSignature || $wire.applicantSignature;
                                    
                                                canvas.width = canvas.offsetWidth * ratio;
                                                canvas.height = canvas.offsetHeight * ratio;
                                                canvas.getContext('2d').scale(ratio, ratio);
                                    
                                                if (!this.signaturePad) {
                                                    return;
                                                }
                                    
                                                this.signaturePad.clear();
                                    
                                                if (currentValue) {
                                                    this.signaturePad.fromDataURL(currentValue, { ratio });
                                                }
                                            };
                                    
                                            this.signaturePad = new SignaturePadLib(canvas, {
                                                backgroundColor: 'rgba(0, 0, 0, 0)',
                                                penColor: 'rgb(68, 64, 60)',
                                            });
                                    
                                            this.resizeHandler();
                                            window.addEventListener('resize', this.resizeHandler);
                                    
                                            this.signaturePad.addEventListener('beginStroke', () => {
                                                this.isDrawing = true;
                                            });
                                    
                                            this.signaturePad.addEventListener('endStroke', () => {
                                                this.isDrawing = false;
                                                this.currentSignature = this.signaturePad.toDataURL('image/png');
                                                $wire.set('applicantSignature', this.currentSignature);
                                            });
                                        },
                                        clearSignature() {
                                            if (!this.signaturePad) {
                                                return;
                                            }
                                    
                                            this.signaturePad.clear();
                                            this.currentSignature = null;
                                            $wire.set('applicantSignature', null);
                                        },
                                        destroy() {
                                            if (this.resizeHandler) {
                                                window.removeEventListener('resize', this.resizeHandler);
                                            }
                                        }
                                    }" x-init="init()">
                                        <div
                                            class="rounded-lg border border-dashed border-stone-300 bg-stone-50/80 p-2">
                                            <canvas x-ref="signatureCanvas"
                                                class="h-28 w-full cursor-crosshair rounded-md bg-transparent"
                                                style="touch-action: none;"></canvas>
                                        </div>
                                        <div class="mt-2 flex items-center justify-between gap-4">
                                            <p class="text-[11px] italic text-stone-500">Tanda tangan pakai
                                                mouse / touchpad / layar sentuh.</p>
                                            <button type="button" @click="clearSignature()"
                                                class="text-[11px] font-semibold text-red-600 transition hover:text-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                        <p class="mt-2 text-[12px] text-stone-600">
                                            {{ now()->translatedFormat('d F Y') }}</p>
                                        @error('applicantSignature')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="border-r border-stone-400 p-4 align-top">
                    <p class="text-[13px] font-bold text-stone-800">Catatan :</p>
                    <div
                        class="mt-2 min-h-28 rounded-md border border-dashed border-stone-300 bg-white/70 p-3 text-[12px] italic text-stone-500">
                        Menunggu hasil verifikasi admin LSP.
                    </div>
                </td>
                <td class="p-0 align-top">
                    <table class="w-full table-fixed border-collapse text-sm">
                        <tbody>
                            <tr>
                                <td colspan="2"
                                    class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">
                                    Admin LSP :</td>
                            </tr>
                            <tr>
                                <td
                                    class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                    Nama :</td>
                                <td class="border-b border-stone-400 px-4 py-3 align-top">
                                    <p
                                        class="text-[13px] {{ $adminVerificationPreview['state'] === 'verified' ? 'font-medium text-stone-900' : 'italic text-stone-400' }}">
                                        {{ $adminVerificationPreview['state'] === 'verified' ? ($adminVerificationPreview['name'] ?: 'Admin LSP') : 'Menunggu verifikasi admin LSP' }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                    Tanda tangan/
                                    <br>
                                    Tanggal
                                </td>
                                <td class="px-4 py-3 align-top">
                                    @if ($adminVerificationPreview['state'] === 'verified')
                                        <div
                                            class="rounded-md border border-emerald-200 bg-emerald-50/70 px-3 py-4">
                                            <p class="text-[13px] font-semibold text-emerald-800">Terverifikasi
                                                admin LSP</p>
                                            <p class="mt-1 text-[12px] text-emerald-700">
                                                {{ $adminVerificationPreview['date'] ?: 'Tanggal verifikasi belum tersedia' }}
                                            </p>
                                        </div>
                                    @elseif ($adminVerificationPreview['state'] === 'rejected')
                                        <div class="rounded-md border border-red-200 bg-red-50/70 px-3 py-4">
                                            <p class="text-[13px] font-semibold text-red-700">Verifikasi tidak
                                                disetujui</p>
                                            <p class="mt-1 text-[12px] text-red-600">Tanda tangan admin tidak
                                                ditampilkan.</p>
                                        </div>
                                    @else
                                        <div
                                            class="rounded-md border border-dashed border-stone-300 bg-stone-50/80 px-3 py-4">
                                            <p class="text-[13px] italic text-stone-500">Menunggu verifikasi
                                                admin LSP.</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
    <button type="button" wire:click="previousStep"
        class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
    </button>
    <button type="button" wire:click="nextStep"
        class="inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-600 w-full sm:w-auto">
        Selesaikan APL 01
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </button>
</div>
