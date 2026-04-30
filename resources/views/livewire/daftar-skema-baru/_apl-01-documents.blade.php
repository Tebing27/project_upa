<div class="mb-4 border-b border-gray-100 pb-4">
    <h3 class="text-xl font-bold italic text-gray-900">Bagian 3 : Bukti Kelengkapan Pemohon</h3>
</div>
<div class="overflow-x-auto rounded-xl border border-gray-300">
    <table class="w-full min-w-[700px] table-fixed text-xs">
        <thead class="border-b border-gray-300 bg-gray-50 font-bold">
            <tr>
                <td class="w-12 border-r border-gray-300 p-3 text-center">No.</td>
                <td class="w-72 border-r border-gray-300 p-3">Bukti Persyaratan Dasar</td>
                <td class="p-3">Upload / Status</td>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @php
                $supportingDocuments = [
                    [
                        'label' => \App\Models\Registration::apl01RequirementLabels()['ktm_path'],
                        'property' => 'ktm',
                        'accept' => '.jpg,.jpeg,.png',
                        'hint' => 'JPG, JPEG, PNG maksimal 2MB.',
                        'empty' => 'Belum upload',
                    ],
                    [
                        'label' => \App\Models\Registration::apl01RequirementLabels()['khs_path'],
                        'property' => 'khs',
                        'accept' => '.pdf',
                        'hint' => 'PDF maksimal 2MB.',
                        'empty' => 'Belum upload',
                    ],
                    [
                        'label' => \App\Models\Registration::apl01RequirementLabels()[
                            'internship_certificate_path'
                        ],
                        'property' => 'internshipCertificate',
                        'accept' => '.jpg,.jpeg,.png,.pdf',
                        'hint' => 'JPG, JPEG, PNG, PDF maksimal 2MB.',
                        'empty' => 'Opsional',
                    ],
                    [
                        'label' => \App\Models\Registration::apl01RequirementLabels()['ktp_path'],
                        'property' => 'ktp',
                        'accept' => '.jpg,.jpeg,.png',
                        'hint' => 'JPG, JPEG, PNG maksimal 2MB.',
                        'empty' => 'Belum upload',
                    ],
                    [
                        'label' => \App\Models\Registration::apl01RequirementLabels()['passport_photo_path'],
                        'property' => 'passportPhoto',
                        'accept' => '.jpg,.jpeg,.png',
                        'hint' => 'JPG, JPEG, PNG maksimal 2MB. Preview mengikuti rasio 3x4.',
                        'empty' => 'Belum upload',
                        'photo' => true,
                    ],
                ];
            @endphp

            @foreach ($supportingDocuments as $index => $document)
                @php
                    $property = $document['property'];
                    $uploadedFile = $this->{$property};
                    $extension = $uploadedFile ? strtolower($uploadedFile->getClientOriginalExtension()) : null;
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                @endphp
                <tr wire:key="apl01-document-{{ $property }}">
                    <td class="border-r border-gray-300 p-4 text-center">{{ $index + 1 }}.</td>
                    <td class="border-r border-gray-300 p-4 italic">{{ $document['label'] }}</td>
                    <td class="space-y-3 p-4">
                        @if (($document['photo'] ?? false) === true)
                            <div wire:ignore.self x-data="{
                                image: null,
                                cropper: null,
                                showModal: false,
                                init() {
                                    this.$watch('showModal', value => {
                                        if (value) {
                                            const initLogic = () => {
                                                setTimeout(() => {
                                                    const img = this.$refs.cropImage;
                                                    const initCropper = () => {
                                                        if (this.cropper) this.cropper.destroy();
                                                        this.cropper = new Cropper(img, {
                                                            aspectRatio: 3 / 4,
                                                            viewMode: 1,
                                                            autoCropArea: 1,
                                                            dragMode: 'move',
                                                            background: false,
                                                            ready: () => {
                                                                const cropperBox = this.cropper.cropper.querySelector('.cropper-view-box');
                                                                if (cropperBox && !cropperBox.querySelector('.passport-guide')) {
                                                                    const guide = document.createElement('div');
                                                                    guide.className = 'passport-guide absolute inset-0 pointer-events-none z-10';
                                                                    const svgUrl = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 400'%3E%3Cg stroke='rgba(0,0,0,0.3)' stroke-width='6'%3E%3Cellipse cx='150' cy='160' rx='80' ry='105' fill='none'/%3E%3Cpath d='M 30 400 C 30 280, 270 280, 270 400' fill='none'/%3E%3C/g%3E%3Cg stroke='white' stroke-width='3' stroke-dasharray='8,8'%3E%3Cellipse cx='150' cy='160' rx='80' ry='105' fill='none'/%3E%3Cpath d='M 30 400 C 30 280, 270 280, 270 400' fill='none'/%3E%3C/g%3E%3C/svg%3E`;
                                                                    guide.style.backgroundImage = `url('${svgUrl}')`;
                                                                    guide.style.backgroundSize = '100% 100%';
                                                                    cropperBox.appendChild(guide);
                                                                }
                                                            }
                                                        });
                                                    };
                            
                                                    if (img.complete && img.naturalWidth > 0) {
                                                        initCropper();
                                                    } else {
                                                        img.onload = initCropper;
                                                    }
                                                }, 200);
                                            };
                            
                                            if (typeof window.Cropper === 'undefined') {
                                                const link = document.createElement('link');
                                                link.rel = 'stylesheet';
                                                link.href = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css';
                                                document.head.appendChild(link);
                            
                                                const script = document.createElement('script');
                                                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js';
                                                script.onload = initLogic;
                                                document.head.appendChild(script);
                                            } else {
                                                initLogic();
                                            }
                                        } else if (this.cropper) {
                                            this.cropper.destroy();
                                            this.cropper = null;
                                        }
                                    });
                                },
                                onFileChange(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.image = ''; // Reset image
                                        const reader = new FileReader();
                                        reader.onload = (event) => {
                                            this.image = event.target.result;
                                            this.showModal = true;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                },
                                saveCrop() {
                                    const canvas = this.cropper.getCroppedCanvas({
                                        width: 600,
                                        height: 800,
                                    });
                                    const base64 = canvas.toDataURL('image/jpeg');
                                    $wire.set('passportPhotoBase64', base64);
                                    this.showModal = false;
                                }
                            }">
                                <div class="flex flex-col md:flex-row items-start gap-4">
                                    <div
                                        class="relative shrink-0 flex aspect-[3/4] w-28 items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 group">
                                        @if ($passportPhotoBase64)
                                            <img src="{{ $passportPhotoBase64 }}" alt="Preview pasfoto 3x4"
                                                class="h-full w-full object-cover">
                                            <div
                                                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                                                <button type="button"
                                                    @click="$refs.passportPhotoInput.click()"
                                                    class="rounded-full bg-white p-2 text-zinc-900 shadow-lg">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif ($uploadedFile)
                                            <img src="{{ $uploadedFile->temporaryUrl() }}"
                                                alt="Preview pasfoto" class="h-full w-full object-cover">
                                        @else
                                            <div class="p-4 text-center">
                                                <svg class="mx-auto mb-2 h-8 w-8 text-slate-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-[10px] font-medium text-slate-400">Kosong</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="relative group max-w-sm">
                                            <input type="file" @change="onFileChange"
                                                wire:model="{{ $property }}" x-ref="passportPhotoInput"
                                                accept="{{ $document['accept'] }}"
                                                class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
                                            <div @class([
                                                'flex items-center gap-3 rounded-xl border border-dashed px-3 py-2.5 transition-all',
                                                'border-emerald-500 bg-emerald-50/50' =>
                                                    $uploadedFile || $passportPhotoBase64,
                                                'border-slate-300 bg-white group-hover:border-slate-400' =>
                                                    !$uploadedFile && !$passportPhotoBase64,
                                            ])>
                                                <div @class([
                                                    'rounded-lg p-1.5',
                                                    'bg-emerald-500 text-white' => $uploadedFile || $passportPhotoBase64,
                                                    'bg-slate-100 text-slate-500' => !$uploadedFile && !$passportPhotoBase64,
                                                ])>
                                                    <div wire:loading wire:target="{{ $property }}">
                                                        <svg class="h-4 w-4 animate-spin" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12"
                                                                cy="12" r="10" stroke="currentColor"
                                                                stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div wire:loading.remove
                                                        wire:target="{{ $property }}">
                                                        <svg class="h-4 w-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-[11px] font-bold text-gray-900 truncate">
                                                        <span wire:loading
                                                            wire:target="{{ $property }}">Uploading...</span>
                                                        <span wire:loading.remove
                                                            wire:target="{{ $property }}">
                                                            {{ $passportPhotoBase64 ? 'Foto Telah Diatur' : ($uploadedFile ? $uploadedFile->getClientOriginalName() : 'Pilih Pas Foto Formal') }}
                                                        </span>
                                                    </p>
                                                    <p class="mt-0.5 text-[10px] leading-snug text-slate-500">
                                                        JPG, JPEG, PNG maksimal 2MB. Rasio 3:4 akan diterapkan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($passportPhotoBase64 || $uploadedFile)
                                            <button type="button"
                                                @click="if (image) showModal = true; else $refs.passportPhotoInput.click()"
                                                class="mt-2.5 flex items-center gap-1.5 text-xs font-bold text-emerald-600 transition-colors hover:text-emerald-700">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                Atur Ulang Crop
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div x-show="showModal"
                                    class="fixed inset-0 z-[100] flex items-center justify-center bg-zinc-950/80 p-4"
                                    style="display: none;">
                                    <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl"
                                        wire:ignore>
                                        <div
                                            class="flex items-center justify-between border-b border-slate-100 p-6">
                                            <h4 class="text-lg font-bold">Sesuaikan Pas Foto</h4>
                                            <button type="button" @click="showModal = false"
                                                class="text-slate-400 transition-colors hover:text-zinc-900">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-6">
                                            <div id="passport-cropper-container"
                                                class="max-h-[60vh] overflow-hidden rounded-2xl bg-zinc-100 relative">
                                                <img x-ref="cropImage" :src="image"
                                                    class="block max-w-full">
                                            </div>
                                            <p class="mt-4 text-center text-sm text-slate-500">Geser dan atur
                                                kotak agar wajah dan bahu sesuai dengan garis bantu putus-putus
                                                (Rasio 3:4)
                                                .</p>
                                        </div>
                                        <div class="flex items-center justify-end gap-3 bg-slate-50 p-6">
                                            <button type="button" @click="showModal = false"
                                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
                                            <button type="button" @click="saveCrop" :disabled="!cropper"
                                                :class="!cropper ?
                                                    'opacity-50 cursor-not-allowed bg-slate-300 shadow-none text-slate-500' :
                                                    'bg-emerald-400 hover:bg-emerald-500 shadow-emerald-200 shadow-lg text-black'"
                                                class="rounded-xl px-8 py-2.5 text-sm font-bold transition-all">Terapkan
                                                Crop</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="max-w-sm">
                                <div class="relative group mt-1">
                                    <input type="file" wire:model="{{ $property }}"
                                        accept="{{ $document['accept'] }}"
                                        class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                    <div @class([
                                        'flex min-h-[44px] items-center gap-2.5 rounded-lg border-2 border-dashed px-3 py-1.5 transition',
                                        'border-gray-200 bg-gray-50/50 group-hover:border-emerald-400 group-hover:bg-emerald-50/30' => !$uploadedFile,
                                        'border-emerald-200 bg-emerald-50/30' => $uploadedFile,
                                    ])>
                                        <div
                                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-white shadow-sm ring-1 ring-gray-200">
                                            <svg class="h-3.5 w-3.5 text-gray-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 truncate text-left">
                                            <p class="truncate text-[11px] font-semibold text-gray-700">
                                                {{ $uploadedFile ? $uploadedFile->getClientOriginalName() : 'Pilih File...' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div wire:loading wire:target="{{ $property }}"
                                class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-semibold text-amber-700">
                                Uploading...
                            </div>

                            <div wire:loading.remove wire:target="{{ $property }}" class="space-y-2">
                                @if ($uploadedFile)
                                    <div class="w-fit max-w-full rounded-lg border border-emerald-200 bg-emerald-50/70 p-2">
                                        @if ($isImage)
                                            <img src="{{ $uploadedFile->temporaryUrl() }}"
                                                alt="Preview {{ $document['label'] }}"
                                                class="max-h-32 w-auto max-w-full rounded-md object-contain">
                                        @else
                                            <div
                                                class="flex items-center gap-2 rounded-md bg-white px-3 py-2 text-[11px] text-gray-700">
                                                <span
                                                    class="rounded bg-red-100 px-2 py-1 font-bold text-red-700">PDF</span>
                                                <span
                                                    class="truncate font-medium">{{ $uploadedFile->getClientOriginalName() }}</span>
                                            </div>
                                        @endif
                                        <p class="mt-2 truncate text-[10px] font-semibold text-emerald-700">
                                            {{ $uploadedFile->getClientOriginalName() }}</p>
                                    </div>
                                @else
                                    <p class="text-[11px] text-gray-500">{{ $document['empty'] }}</p>
                                @endif
                                <p class="text-[10px] text-gray-400">{{ $document['hint'] }}</p>
                            </div>
                        @endif

                        @error($property)
                            <p class="text-[10px] text-red-500">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
            @endforeach
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
    <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
        class="inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 w-full sm:w-auto">
        <span wire:loading.remove wire:target="nextStep">Lanjut ke Bagian 4</span>
        <span wire:loading wire:target="nextStep">Memeriksa...</span>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>
