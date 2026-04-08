<div class="min-h-screen space-y-6 bg-slate-50/50 p-6">
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('dashboard') }}"
                class="mb-4 inline-flex items-center gap-1 text-sm text-gray-500 transition-colors hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Skema Sertifikasi</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if ($useCondensedDocumentFlow)
                    Pilih skema, upload FR APL 01 dan FR APL 02, lalu review pendaftaran Anda.
                @else
                    Pilih skema, lengkapi biodata bila diperlukan, lalu upload dokumen pendaftaran Anda.
                @endif
            </p>
        </div>

        @if ($errorMessage)
            <div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex flex-col items-center text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50">
                        <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak Dapat Mendaftar</h3>
                    <p class="mt-2 max-w-md text-sm text-gray-500">{{ $errorMessage }}</p>
                    <a href="{{ route('dashboard') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @else
            @php
                $visibleStepKeys = array_keys($steps);
                $currentVisibleIndex = array_search($currentStep, $visibleStepKeys, true);
                $currentVisibleIndex = $currentVisibleIndex === false
                    ? count($visibleStepKeys)
                    : $currentVisibleIndex + 1;
                $progressWidth =
                    count($visibleStepKeys) > 1
                        ? (($currentVisibleIndex - 1) / (count($visibleStepKeys) - 1)) * 100
                        : 0;
            @endphp

            <div class="mb-8">
                <div class="relative flex w-full px-2 md:px-8">
                    <div class="absolute left-0 right-0 top-[19px] z-0 h-[2px] bg-slate-200"></div>
                    <div class="absolute left-0 top-[19px] z-0 h-[2px] bg-[#10b981] transition-all duration-500"
                        style="width: {{ $progressWidth }}%;"></div>

                    <div class="relative z-10 flex w-full justify-between">
                        @foreach ($steps as $stepLabel)
                            @php
                                $isCompleted = $loop->iteration < $currentVisibleIndex;
                                $isCurrent = $loop->iteration === $currentVisibleIndex;
                            @endphp
                            <div class="relative flex shrink-0 flex-col items-center">
                                <div @class([
                                    'relative z-10 flex h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                                    'bg-[#10b981] text-white' => $isCompleted || $isCurrent,
                                    'border-[2px] border-slate-200 bg-white text-slate-400' =>
                                        !$isCompleted && !$isCurrent,
                                ])>
                                    @if ($isCompleted)
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <div
                                    class="absolute left-1/2 top-[52px] w-[120px] -translate-x-1/2 text-center md:w-[150px]">
                                    <p
                                        class="text-[11px] leading-[1.3] md:text-[13px] {{ $isCompleted || $isCurrent ? 'font-medium text-slate-600' : 'text-slate-400' }}">
                                        {{ $stepLabel }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-16">
                @if ($currentStep === 1)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Pilih Tipe Pendaftaran</h2>
                        <p class="mt-1 text-sm text-gray-500">Tentukan apakah Anda ingin mendaftar skema baru atau
                            memperpanjang sertifikat yang sudah ada.</p>

                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label @class([
                                'relative flex cursor-pointer rounded-xl border-2 p-5 transition-all',
                                'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' =>
                                    $registrationType === 'baru',
                                'border-gray-200 hover:border-gray-300' => $registrationType !== 'baru',
                            ])>
                                <input type="radio" wire:model.live="registrationType" value="baru" class="sr-only">
                                <div>
                                    <p class="font-semibold text-gray-900">Skema Baru</p>
                                    <p class="mt-1 text-[13px] text-gray-500">Daftar skema sertifikasi yang belum pernah
                                        Anda ambil.</p>
                                </div>
                            </label>

                            <label @class([
                                'relative flex cursor-pointer rounded-xl border-2 p-5 transition-all',
                                'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' =>
                                    $registrationType === 'perpanjangan',
                                'border-gray-200 hover:border-gray-300' =>
                                    $registrationType !== 'perpanjangan',
                            ])>
                                <input type="radio" wire:model.live="registrationType" value="perpanjangan"
                                    class="sr-only">
                                <div>
                                    <p class="font-semibold text-gray-900">Perpanjangan</p>
                                    <p class="mt-1 text-[13px] text-gray-500">Perpanjang sertifikat yang sudah tidak
                                        aktif atau kedaluwarsa.</p>
                                </div>
                            </label>
                        </div>
                        @error('registrationType')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($registrationType)
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-900">Pilih Skema Sertifikasi</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if ($showFacultyFilters)
                                        Gunakan filter fakultas dan program studi untuk mempersempit pilihan skema. Jika
                                        biodata sudah terisi, filter akan otomatis menyesuaikan.
                                    @elseif ($registrationType === 'baru')
                                        Pilih skema yang sesuai dengan tujuan sertifikasi Anda.
                                    @else
                                        Skema dengan sertifikat kedaluwarsa atau tidak aktif yang bisa diperpanjang.
                                    @endif
                                </p>

                                @if ($showFacultyFilters)
                                    <div
                                        class="mt-4 mb-6 grid grid-cols-1 gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                                            <select wire:model.live="faculty"
                                                class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm">
                                                <option value="">-- Semua Fakultas --</option>
                                                @foreach ($faculties as $fac)
                                                    <option value="{{ $fac->id }}">{{ $fac->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                                            <select wire:model.live="studyProgram"
                                                class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm disabled:cursor-not-allowed disabled:bg-gray-100"
                                                @disabled(!$faculty)>
                                                <option value="">-- Semua Program Studi --</option>
                                                @foreach ($studyPrograms as $sp)
                                                    <option value="{{ $sp->id }}">{{ $sp->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @php
                                    $schemes = $registrationType === 'baru' ? $newSchemes : $renewalSchemes;
                                @endphp

                                @if ($schemes->isEmpty())
                                    <div
                                        class="mt-4 rounded-xl border border-amber-100 bg-amber-50/50 p-5 text-sm text-amber-700">
                                        @if ($registrationType === 'baru')
                                            Tidak ada skema baru yang tersedia untuk pilihan Anda saat ini.
                                            @if ($hasMatchingCertifiedSchemeForNewRegistration)
                                                <p class="mt-2 text-amber-800">
                                                    Anda sudah memiliki sertifikasi skema ini, mohon untuk diperpanjang jika sudah kadaluarsa.
                                                </p>
                                            @endif
                                        @else
                                            Tidak ada sertifikat kedaluwarsa atau tidak aktif yang bisa diperpanjang
                                            saat ini.
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-4 space-y-3">
                                        @foreach ($schemes as $scheme)
                                            <label wire:key="scheme-{{ $scheme->id }}" @class([
                                                'flex cursor-pointer items-start gap-4 rounded-xl border-2 p-5 transition-all',
                                                'border-emerald-500 bg-emerald-50/30' => (int) $schemeId === $scheme->id,
                                                'border-gray-200 hover:border-gray-300' => (int) $schemeId !== $scheme->id,
                                            ])>
                                                <input type="radio" wire:model.live="schemeId"
                                                    value="{{ $scheme->id }}"
                                                    class="mt-1 h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $scheme->name }}</p>
                                                    <p class="mt-0.5 text-[13px] text-gray-500">
                                                        {{ $scheme->faculty ?: 'Umum' }}{{ $scheme->study_program ? ' - ' . $scheme->study_program : '' }}
                                                    </p>
                                                    @if ($scheme->description)
                                                        <p class="mt-1 text-[13px] text-gray-400">
                                                            {{ $scheme->description }}</p>
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
                            <button type="button" wire:click="nextStep" @disabled(!$registrationType || !$schemeId)
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50">
                                Selanjutnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if ($currentStep === 2)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4">
                            <p class="text-sm font-medium text-emerald-800">
                                @if ($shouldShowProfileStep)
                                    @if ($requiresProfileCompletion)
                                        Tahap kedua adalah lengkapi biodata. Data akan dicek dulu di tahap review, lalu baru dikirim saat pendaftaran disubmit.
                                    @else
                                        Tahap kedua adalah pengecekan biodata. Pastikan data Anda sudah benar sebelum lanjut ke tahap upload dokumen dan review.
                                    @endif
                                @else
                                    Tahap ini hanya berlaku untuk peserta umum.
                                @endif
                            </p>
                        </div>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Nama
                                    Lengkap*</label><input type="text" wire:model="name"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label
                                    class="block text-sm font-medium text-gray-700">Email*</label><input
                                    type="email" wire:model="email"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label
                                    class="block text-sm font-medium text-gray-700">NIK*</label><input type="text"
                                    wire:model="no_ktp"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('no_ktp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><span class="block text-sm font-medium text-gray-700">Jenis
                                    Kelamin*</span>
                                <div class="mt-3 flex gap-6"><label
                                        class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            wire:model="jenis_kelamin" value="L"> Laki-Laki</label><label
                                        class="inline-flex items-center gap-2 text-sm"><input type="radio"
                                            wire:model="jenis_kelamin" value="P"> Perempuan</label></div>
                                @error('jenis_kelamin')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Tempat Lahir*</label><input
                                    type="text" wire:model="tempat_lahir"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('tempat_lahir')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Tanggal Lahir*</label><input
                                    type="date" wire:model="tanggal_lahir"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('tanggal_lahir')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Alamat
                                    Domisili / Sesuai KTP*</label>
                                <textarea wire:model="alamat_rumah" rows="4"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></textarea>
                                @error('alamat_rumah')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Provinsi*</label><input
                                    type="text" wire:model="domisili_provinsi"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('domisili_provinsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Kota / Kabupaten*</label><input
                                    type="text" wire:model="domisili_kota"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('domisili_kota')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label
                                    class="block text-sm font-medium text-gray-700">Kecamatan*</label><input
                                    type="text" wire:model="domisili_kecamatan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('domisili_kecamatan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">No. Telp / No WhatsApp
                                    Aktif*</label><input type="text" wire:model="no_wa"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('no_wa')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Pendidikan
                                    Terakhir*</label><input type="text" wire:model="pendidikan_terakhir"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('pendidikan_terakhir')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Fakultas</label><input
                                    type="text" wire:model="fakultas"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('fakultas')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Nama Sekolah / Perguruan
                                    Tinggi*</label><input type="text" wire:model="nama_institusi"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('nama_institusi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Jurusan
                                    / Program Studi*</label><input type="text" wire:model="program_studi"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('program_studi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label
                                    class="block text-sm font-medium text-gray-700">Pekerjaan*</label><input
                                    type="text" wire:model="pekerjaan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('pekerjaan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Nama
                                    Perusahaan</label><input type="text" wire:model="nama_perusahaan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></div>
                            <div class="md:col-span-2"><label
                                    class="block text-sm font-medium text-gray-700">Jabatan</label><input
                                    type="text" wire:model="jabatan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Alamat Lembaga /
                                    Perusahaan</label>
                                <textarea wire:model="alamat_perusahaan" rows="3"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></textarea>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Kode POS
                                    Perusahaan</label><input type="text" wire:model="kode_pos_perusahaan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></div>
                            <div><label class="block text-sm font-medium text-gray-700">No. Telp
                                    Perusahaan</label><input type="text" wire:model="no_telp_perusahaan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3"></div>
                            <div><label class="block text-sm font-medium text-gray-700">Email Perusahaan</label><input
                                    type="email" wire:model="email_perusahaan"
                                    class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-3">
                                @error('email_perusahaan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" wire:click="nextStep"
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                                Selanjutnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if ($currentStep === 3)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Upload Dokumen</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($useCondensedDocumentFlow)
                                Karena Anda sudah memiliki riwayat sertifikat terbit, tahap ini hanya memerlukan FR APL 01 dan FR APL 02. Format PDF maksimal 2MB.
                            @else
                                Upload dokumen yang diperlukan untuk pendaftaran sertifikasi. Format PDF/JPG/PNG maksimal 2MB.
                            @endif
                        </p>

                        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div><label class="block text-sm font-medium text-gray-700">FR APL 01 (PDF) *</label><input
                                    type="file" wire:model="frApl01" accept=".pdf"
                                    class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                @error('frApl01')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">FR APL 02 (PDF) *</label><input
                                    type="file" wire:model="frApl02" accept=".pdf"
                                    class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                @error('frApl02')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @unless ($useCondensedDocumentFlow)
                                <div><label class="block text-sm font-medium text-gray-700">KTM / Kartu Mahasiswa
                                        *</label><input type="file" wire:model="ktm" accept=".pdf,.jpg,.jpeg,.png"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                    @error('ktm')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">KHS / Transkrip (PDF)
                                        *</label><input type="file" wire:model="khs" accept=".pdf"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                    @error('khs')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Sertifikat Magang
                                        (Opsional)</label><input type="file" wire:model="internshipCertificate"
                                        accept=".pdf"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                    @error('internshipCertificate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">KTP / Kartu Identitas
                                        *</label><input type="file" wire:model="ktp" accept=".pdf,.jpg,.jpeg,.png"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                    @error('ktp')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Pas
                                        Foto 3x4 (JPG/PNG) *</label><input type="file" wire:model="passportPhoto"
                                        accept=".jpg,.jpeg,.png"
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
                                    @error('passportPhoto')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endunless
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" wire:click="nextStep"
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                                Selanjutnya
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if ($currentStep === 4)
                    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                        <h2 class="text-xl font-bold text-gray-900">Review Pendaftaran</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($useCondensedDocumentFlow)
                                Pastikan skema dan dua dokumen FR APL sudah sesuai sebelum mengirim pendaftaran ke tahap verifikasi.
                            @else
                                Pastikan biodata dan dokumen sudah sesuai sebelum mengirim pendaftaran ke tahap verifikasi.
                            @endif
                        </p>

                        <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50/50 p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Data</h3>
                            <div class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                                <div><span class="font-semibold text-gray-900">Nama:</span> <span
                                        class="text-gray-600">{{ $name ?: '-' }}</span></div>
                                <div><span
                                        class="font-semibold text-gray-900">{{ auth()->user()->isGeneralUser() ? 'NIK' : 'NIM' }}:</span>
                                    <span
                                        class="text-gray-600">{{ auth()->user()->isGeneralUser() ? ($no_ktp ?: '-') : ($nim ?: '-') }}</span>
                                </div>
                                <div><span
                                        class="font-semibold text-gray-900">{{ auth()->user()->isGeneralUser() ? 'Instansi Pendidikan' : 'Program Studi' }}:</span>
                                    <span
                                        class="text-gray-600">{{ auth()->user()->isGeneralUser() ? ($nama_institusi ?: '-') : ($program_studi ?: '-') }}</span>
                                </div>
                                <div><span class="font-semibold text-gray-900">Tipe Pendaftaran:</span> <span
                                        class="text-gray-600">{{ $registrationType === 'baru' ? 'Skema Baru' : 'Perpanjangan' }}</span>
                                </div>
                                <div class="sm:col-span-2"><span class="font-semibold text-gray-900">Skema
                                        Sertifikasi:</span> <span
                                        class="text-gray-600">{{ $selectedScheme?->name ?? '-' }}</span></div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50/50 p-6">
                            <h3 class="text-lg font-semibold text-blue-900">Tahap Berikutnya</h3>
                            <p class="mt-2 text-sm text-blue-800">Setelah pendaftaran dikirim, admin akan memverifikasi
                                data dan dokumen Anda. Jika lolos verifikasi, Anda akan masuk ke tahap pembayaran untuk
                                upload bukti bayar.</p>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" wire:click="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black shadow-sm transition-all hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                                Kirim Pendaftaran
                            </button>
                        </div>
                    </div>
                @endif

                @if ($currentStep === 5)
                    <div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col items-center py-6 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-emerald-700">Pendaftaran Berhasil!</h2>
                            <p class="mt-2 max-w-md text-sm text-gray-500">Pendaftaran berhasil dikirim. Tahap
                                berikutnya adalah verifikasi data dan dokumen oleh admin.</p>
                            @if ($submittedRegistration)
                                <a href="{{ route('dashboard.status', $submittedRegistration) }}"
                                    class="mt-4 inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 transition-all hover:bg-emerald-100">
                                    Lihat Status Pendaftaran
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
