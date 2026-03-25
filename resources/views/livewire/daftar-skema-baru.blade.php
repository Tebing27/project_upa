<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Skema Sertifikasi</h1>
            <p class="mt-1 text-sm text-gray-500">Daftarkan diri Anda untuk skema sertifikasi baru atau perpanjangan sertifikat.</p>
        </div>

        {{-- Error: has in-progress registration --}}
        @if ($errorMessage)
            <div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex flex-col items-center text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50">
                        <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak Dapat Mendaftar</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-md">{{ $errorMessage }}</p>
                    <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @else
            {{-- Stepper Indicator --}}
            <div class="mb-8">
                <div class="relative flex w-full px-2 md:px-8">
                    @php
                        $steps = [1 => 'Pilih Tipe & Skema', 2 => 'Upload Dokumen', 3 => 'Review & Bayar'];
                        $progressWidth = match($currentStep) {
                            2 => 50,
                            3 => 100,
                            4 => 100,
                            default => 0,
                        };
                    @endphp

                    <div class="absolute left-0 right-0 top-[19px] h-[2px] bg-slate-200 z-0"></div>
                    <div class="absolute left-0 top-[19px] h-[2px] bg-[#10b981] transition-all duration-500 z-0" style="width: {{ $progressWidth }}%;"></div>

                    <div class="relative z-10 flex w-full justify-between">
                        @foreach ($steps as $stepNumber => $stepLabel)
                            @php
                                $isCompleted = $stepNumber < $currentStep;
                                $isCurrent = $stepNumber === $currentStep;
                            @endphp
                            <div class="relative flex shrink-0 flex-col items-center">
                                <div @class([
                                    'relative z-10 flex shrink-0 h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                                    'bg-[#10b981] text-white' => $isCompleted || $isCurrent,
                                    'bg-white border-[2px] border-slate-200 text-slate-400' => !$isCompleted && !$isCurrent,
                                ])>
                                    @if ($isCompleted)
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $stepNumber }}
                                    @endif
                                </div>
                                <div class="absolute top-[52px] left-1/2 w-[120px] -translate-x-1/2 text-center md:w-[150px]">
                                    <p @class([
                                        'whitespace-pre-line leading-[1.3] text-[11px] md:text-[13px]',
                                        'text-slate-600 font-medium' => $isCompleted || $isCurrent,
                                        'text-slate-400' => !$isCompleted && !$isCurrent,
                                    ])>
                                        {{ $stepLabel }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-16">
                {{-- STEP 1: Pilih Tipe & Skema --}}
                @if ($currentStep === 1)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Pilih Tipe Pendaftaran</h2>
                        <p class="mt-1 text-sm text-gray-500">Tentukan apakah Anda ingin mendaftar skema baru atau memperpanjang sertifikat yang sudah ada.</p>

                        {{-- Registration Type Radio --}}
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label @class([
                                'relative flex cursor-pointer rounded-xl border-2 p-5 transition-all',
                                'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' => $registrationType === 'baru',
                                'border-gray-200 hover:border-gray-300' => $registrationType !== 'baru',
                            ])>
                                <input type="radio" wire:model.live="registrationType" value="baru" class="sr-only">
                                <div class="flex items-start gap-3">
                                    <div @class([
                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                                        'bg-emerald-100 text-emerald-600' => $registrationType === 'baru',
                                        'bg-gray-100 text-gray-400' => $registrationType !== 'baru',
                                    ])>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Skema Baru</p>
                                        <p class="mt-1 text-[13px] text-gray-500">Daftar skema sertifikasi yang belum pernah Anda ambil.</p>
                                    </div>
                                </div>
                            </label>

                            <label @class([
                                'relative flex cursor-pointer rounded-xl border-2 p-5 transition-all',
                                'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' => $registrationType === 'perpanjangan',
                                'border-gray-200 hover:border-gray-300' => $registrationType !== 'perpanjangan',
                            ])>
                                <input type="radio" wire:model.live="registrationType" value="perpanjangan" class="sr-only">
                                <div class="flex items-start gap-3">
                                    <div @class([
                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                                        'bg-blue-100 text-blue-600' => $registrationType === 'perpanjangan',
                                        'bg-gray-100 text-gray-400' => $registrationType !== 'perpanjangan',
                                    ])>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Perpanjangan</p>
                                        <p class="mt-1 text-[13px] text-gray-500">Perpanjang sertifikat Anda yang statusnya sudah tidak aktif atau kedaluwarsa.</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('registrationType')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Scheme Selection --}}
                        @if ($registrationType)
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-900">Pilih Skema Sertifikasi</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if ($registrationType === 'baru')
                                        Silakan pilih Fakultas dan Program Studi untuk melihat skema yang tersedia.
                                    @else
                                        Skema dengan sertifikat kedaluwarsa atau tidak aktif yang bisa diperpanjang.
                                    @endif
                                </p>

                                @if ($registrationType === 'baru')
                                    <div class="mt-4 mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 rounded-xl bg-gray-50 p-4 border border-gray-100">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                                            <select wire:model.live="faculty" class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">-- Pilih Fakultas --</option>
                                                @foreach ($faculties as $fac)
                                                    <option value="{{ $fac }}">{{ $fac }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                                            <select wire:model.live="studyProgram" class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed" @disabled(!$faculty)>
                                                <option value="">-- Pilih Program Studi --</option>
                                                @foreach ($studyPrograms as $sp)
                                                    <option value="{{ $sp }}">{{ $sp }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @php
                                    $schemes = $registrationType === 'baru' ? $newSchemes : $renewalSchemes;
                                @endphp

                                @if ($schemes->isEmpty())
                                    <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50/50 p-5">
                                        <div class="flex items-start gap-3">
                                            <svg class="h-5 w-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm text-amber-700">
                                                @if ($registrationType === 'baru')
                                                    Tidak ada skema baru yang tersedia. Silakan pilih Fakultas dan Program Studi lain, atau periksa riwayat pendaftaran Anda.
                                                @else
                                                    Tidak ada sertifikat kedaluwarsa atau tidak aktif yang bisa diperpanjang saat ini.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-4 space-y-3">
                                        @foreach ($schemes as $scheme)
                                            <label wire:key="scheme-{{ $scheme->id }}" @class([
                                                'flex cursor-pointer items-start gap-4 rounded-xl border-2 p-5 transition-all',
                                                'border-emerald-500 bg-emerald-50/30' => (int) $schemeId === $scheme->id,
                                                'border-gray-200 hover:border-gray-300' => (int) $schemeId !== $scheme->id,
                                            ])>
                                                <input type="radio" wire:model.live="schemeId" value="{{ $scheme->id }}" class="mt-1 h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $scheme->name }}</p>
                                                    <p class="mt-0.5 text-[13px] text-gray-500">{{ $scheme->faculty }} - {{ $scheme->study_program }}</p>
                                                    @if ($scheme->description)
                                                        <p class="mt-1 text-[13px] text-gray-400">{{ $scheme->description }}</p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                                @error('schemeId')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="mt-8 flex justify-end">
                            <button type="button" wire:click="nextStep" @disabled(!$registrationType || $schemes->isEmpty())
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50">
                                Selanjutnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- STEP 2: Upload Dokumen --}}
                @if ($currentStep === 2)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Upload Dokumen</h2>
                        <p class="mt-1 text-sm text-gray-500">Upload dokumen yang diperlukan untuk pendaftaran sertifikasi. Format: PDF, JPG, PNG (Maks 2MB).</p>

                        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">FR APL 01 (PDF) <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="frApl01" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('frApl01') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">FR APL 02 (PDF) <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="frApl02" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('frApl02') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">KTM / Kartu Mahasiswa <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="ktm" accept=".pdf,.jpg,.jpeg,.png" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('ktm') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">KHS / Transkrip (PDF) <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="khs" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('khs') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sertifikat Magang (Opsional, PDF)</label>
                                <input type="file" wire:model="internshipCertificate" accept=".pdf" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('internshipCertificate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">KTP / Kartu Identitas <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="ktp" accept=".pdf,.jpg,.jpeg,.png" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('ktp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Pas Foto 3x4 (JPG/PNG) <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="passportPhoto" accept=".jpg,.jpeg,.png" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                                @error('passportPhoto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" wire:click="previousStep" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                                Selanjutnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- STEP 3: Review & Bayar --}}
                @if ($currentStep === 3)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Review & Instruksi Pembayaran</h2>
                        <p class="mt-1 text-sm text-gray-500">Pastikan data sudah benar sebelum menyelesaikan pendaftaran.</p>

                        {{-- Data Summary --}}
                        <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50/50 p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Data</h3>
                            <div class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                                <div>
                                    <span class="font-semibold text-gray-900">Nama:</span>
                                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">NIM:</span>
                                    <span class="text-gray-600">{{ auth()->user()->nim }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Program Studi:</span>
                                    <span class="text-gray-600">{{ auth()->user()->program_studi }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Tipe Pendaftaran:</span>
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                        'bg-emerald-50 text-emerald-700' => $registrationType === 'baru',
                                        'bg-blue-50 text-blue-700' => $registrationType === 'perpanjangan',
                                    ])>
                                        {{ $registrationType === 'baru' ? 'Skema Baru' : 'Perpanjangan' }}
                                    </span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="font-semibold text-gray-900">Skema Sertifikasi:</span>
                                    <span class="text-gray-600">{{ $selectedScheme?->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Instructions --}}
                        <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50/50 p-6">
                            <h3 class="text-lg font-semibold text-blue-900">Instruksi Pembayaran</h3>
                            <p class="mt-2 text-sm text-blue-800">Silakan lakukan pembayaran menggunakan nomor referensi berikut.</p>
                            <div class="mt-3 text-2xl font-mono font-bold text-blue-900">
                                98{{ auth()->user()->nim }}
                            </div>
                            <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-blue-800">
                                <li>Transfer ke Virtual Account Bank X.</li>
                                <li>Masukkan nomor Virtual Account di atas.</li>
                                <li>Verifikasi jumlah pembayaran sebelum konfirmasi.</li>
                            </ul>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" wire:click="previousStep" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" wire:click="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black shadow-sm transition-all hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                                <svg class="h-4 w-4" wire:loading.class="animate-spin" wire:target="submit" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Selesaikan Pendaftaran
                            </button>
                        </div>
                    </div>
                @endif

                {{-- STEP 4: Success --}}
                @if ($currentStep === 4)
                    <div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col items-center text-center py-6">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-emerald-700">Pendaftaran Berhasil!</h2>
                            <p class="mt-2 text-sm text-gray-500 max-w-md">
                                @if ($registrationType === 'perpanjangan')
                                    Pendaftaran perpanjangan sertifikat berhasil dikirim. Silakan lakukan pembayaran sesuai instruksi dan tunggu verifikasi dari admin.
                                @else
                                    Pendaftaran skema baru berhasil dikirim. Silakan lakukan pembayaran sesuai instruksi dan tunggu verifikasi dari admin.
                                @endif
                            </p>
                            <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
