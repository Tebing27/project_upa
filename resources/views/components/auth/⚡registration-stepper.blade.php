<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use App\Models\Scheme;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $currentStep = 1;

    // Step 1: Personal Data
    #[Validate('required|string|email|max:255|unique:users,email')]
    public $email = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255|unique:users,nim')]
    public $nim = '';

    #[Validate('required|string|max:255')]
    public $no_ktp = '';

    #[Validate('required|string|max:255')]
    public $tempat_lahir = '';

    #[Validate('required|date')]
    public $tanggal_lahir = '';

    #[Validate('required|in:L,P')]
    public $jenis_kelamin = '';

    #[Validate('required|string')]
    public $alamat_rumah = '';

    #[Validate('required|string|max:20')]
    public $no_wa = '';

    #[Validate('required|string|max:255')]
    public $pendidikan_terakhir = '';

    #[Validate('required|integer|min:0')]
    public $total_sks = '';

    #[Validate('required|string|max:255')]
    public $status_semester = '';

    #[Validate('required|string|max:255')]
    public $fakultas = '';

    #[Validate('required|string|max:255')]
    public $program_studi = '';

    // Step 2: Scheme Type
    #[Validate('required|exists:schemes,id')]
    public $scheme_id = '';

    #[Computed]
    public function faculties()
    {
        return Scheme::where('is_active', true)->distinct()->pluck('faculty')->sort()->values();
    }

    #[Computed]
    public function studyPrograms()
    {
        if (!$this->fakultas) {
            return collect();
        }

        return Scheme::where('is_active', true)->where('faculty', $this->fakultas)->distinct()->pluck('study_program')->sort()->values();
    }

    #[Computed]
    public function availableSchemes()
    {
        if (!$this->fakultas || !$this->program_studi) {
            return collect();
        }

        return Scheme::where('faculty', $this->fakultas)->where('study_program', $this->program_studi)->where('is_active', true)->get();
    }

    // Step 3: Document Uploads
    #[Validate('required|file|mimes:pdf|max:2048')]
    public $fr_apl_01;

    #[Validate('required|file|mimes:pdf|max:2048')]
    public $fr_apl_02;

    #[Validate('required|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public $ktm;

    #[Validate('required|file|mimes:pdf|max:2048')]
    public $khs;

    #[Validate('nullable|file|mimes:pdf|max:2048')]
    public $internship_certificate;

    #[Validate('required|file|mimes:pdf,jpg,jpeg,png|max:2048')]
    public $ktp;

    #[Validate('required|file|mimes:jpg,jpeg,png|max:2048', onUpdate: false)]
    public $passport_photo;

    public $passport_photo_base64;

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'email' => 'required|string|email|max:255|unique:users,email',
                'name' => 'required|string|max:255',
                'nim' => 'required|string|max:255|unique:users,nim',
                'no_ktp' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat_rumah' => 'required|string',
                'no_wa' => 'required|string|max:20',
                'pendidikan_terakhir' => 'required|string|max:255',
                'total_sks' => 'required|integer|min:0',
                'status_semester' => 'required|string|max:255',
                'fakultas' => 'required|string|max:255',
                'program_studi' => 'required|string|max:255',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'scheme_id' => 'required|exists:schemes,id',
            ]);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'fr_apl_01' => 'required|file|mimes:pdf|max:2048',
                'fr_apl_02' => 'required|file|mimes:pdf|max:2048',
                'ktm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'khs' => 'required|file|mimes:pdf|max:2048',
                'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'passport_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
        }

        if ($this->currentStep < 5) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        $this->currentStep--;
    }
    public function submit()
    {
        // One final validation
        $this->validate();

        $paymentReference = '98' . $this->nim;

        // Save files
        $frApl01Path = $this->fr_apl_01->store('documents/fr_apl_01', 'public');
        $frApl02Path = $this->fr_apl_02->store('documents/fr_apl_02', 'public');
        $ktmPath = $this->ktm->store('documents/ktm', 'public');
        $khsPath = $this->khs->store('documents/khs', 'public');
        $internshipPath = $this->internship_certificate ? $this->internship_certificate->store('documents/internship', 'public') : null;
        $ktpPath = $this->ktp->store('documents/ktp', 'public');
        // Handle passport photo (original or cropped)
        if ($this->passport_photo_base64) {
            $image_parts = explode(';base64,', $this->passport_photo_base64);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = 'documents/passport_photo/' . uniqid() . '.' . $image_type;
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
            $passportPhotoPath = $filename;
        } else {
            $passportPhotoPath = $this->passport_photo->store('documents/passport_photo', 'public');
        }

        $password = Str::password(8);

        // Create user
        $user = User::create([
            'name' => $this->name,
            'nim' => $this->nim,
            'email' => $this->email,
            'password' => Hash::make($password),
            'no_ktp' => $this->no_ktp,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat_rumah' => $this->alamat_rumah,
            'no_wa' => $this->no_wa,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'total_sks' => $this->total_sks,
            'status_semester' => $this->status_semester,
            'fakultas' => $this->fakultas,
            'program_studi' => $this->program_studi,
        ]);

        // Create Registration
        $user->registrations()->create([
            'scheme_id' => $this->scheme_id,
            'fr_apl_01_path' => $frApl01Path,
            'fr_apl_02_path' => $frApl02Path,
            'ktm_path' => $ktmPath,
            'khs_path' => $khsPath,
            'internship_certificate_path' => $internshipPath,
            'ktp_path' => $ktpPath,
            'passport_photo_path' => $passportPhotoPath,
            'payment_reference' => $paymentReference,
            'va_number' => null, // Placeholder for external integration
            'status' => 'pending_payment',
        ]);

        // Move to success step
        $this->currentStep = 5;
    }
};
?>

<div>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Stepper Progress --}}
        <div class="mb-10 lg:mb-16">
            @php
                $stepsCount = 4;
                $steps = [
                    1 => ['label' => 'Biodata', 'desc' => 'Informasi Personal'],
                    2 => ['label' => 'Skema', 'desc' => 'Pilih Skema Sertifikasi'],
                    3 => ['label' => 'Dokumen', 'desc' => 'Upload Persyaratan'],
                    4 => ['label' => 'Pembayaran', 'desc' => 'Konfirmasi & Bayar'],
                ];

                $progressWidth = 0;
                if ($currentStep === 2) {
                    $progressWidth = 33.3333;
                } elseif ($currentStep === 3) {
                    $progressWidth = 66.6666;
                } elseif ($currentStep >= 4) {
                    $progressWidth = 100;
                }
            @endphp

            @if ($currentStep <= 4)
                <div class="relative w-full px-2 md:px-8">
                    {{-- Background Line --}}
                    <div class="absolute left-0 right-0 top-[20px] h-[2px] bg-slate-200 dark:bg-zinc-800 z-0 mx-8"></div>

                    {{-- Active Progress Line --}}
                    <div class="absolute left-0 top-[20px] h-[2px] bg-emerald-500 transition-all duration-700 z-0 mx-8"
                        style="width: calc({{ $progressWidth }}% - 2rem);"></div>

                    {{-- Stepper Items --}}
                    <div class="relative z-10 flex w-full justify-between items-start">
                        @foreach ($steps as $stepNumber => $step)
                            @php
                                $isCompleted = $stepNumber < $currentStep;
                                $isCurrent = $stepNumber === $currentStep;
                            @endphp

                            <div class="flex flex-col items-center group">
                                {{-- Circle --}}
                                <div @class([
                                    'relative z-10 flex shrink-0 h-10 w-10 items-center justify-center rounded-full text-sm font-bold ring-[6px] ring-white dark:ring-zinc-950 transition-all duration-300',
                                    'bg-emerald-500 text-white shadow-lg shadow-emerald-200 dark:shadow-none' =>
                                        $isCompleted || $isCurrent,
                                    'bg-white dark:bg-zinc-900 border-2 border-slate-200 dark:border-zinc-800 text-slate-400' =>
                                        !$isCompleted && !$isCurrent,
                                ])>
                                    @if ($isCompleted)
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span>{{ $stepNumber }}</span>
                                    @endif
                                </div>

                                {{-- Label Desktop --}}
                                <div class="mt-4 hidden md:block text-center">
                                    <p @class([
                                        'text-xs font-bold uppercase tracking-wider',
                                        'text-gray-900 dark:text-white' => $isCurrent || $isCompleted,
                                        'text-slate-400' => !$isCurrent && !$isCompleted,
                                    ])>
                                        {{ $step['label'] }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-500 mt-0.5">{{ $step['desc'] }}
                                    </p>
                                </div>

                                {{-- Label Mobile --}}
                                <div class="mt-4 md:hidden text-center max-w-[60px]">
                                    <p @class([
                                        'text-[10px] font-bold uppercase truncate',
                                        'text-gray-900 dark:text-white' => $isCurrent || $isCompleted,
                                        'text-slate-400' => !$isCurrent && !$isCompleted,
                                    ])>
                                        {{ $step['label'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Form Content --}}
        <div class="space-y-16">
            @if ($currentStep === 1)
                <div>
                    {{-- Personal Data Section --}}
                    <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">
                        <div class="lg:col-span-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Informasi Personal</h3>
                            <p class="text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">Lengkapi data diri Anda
                                sesuai dengan identitas resmi (KTP/KTM) untuk keperluan verifikasi sertifikasi.</p>
                        </div>

                        <div class="mt-8 lg:mt-0 lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="md:col-span-2">
                                    <label for="name"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Nama
                                        Lengkap (Sesuai KTP/KTM)</label>
                                    <input type="text" wire:model="name" id="name" required
                                        placeholder="John Doe"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('name')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Email
                                        Aktif</label>
                                    <input type="email" wire:model="email" id="email" required
                                        placeholder="example@university.ac.id"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('email')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_wa"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Nomor
                                        WhatsApp</label>
                                    <input type="text" wire:model="no_wa" id="no_wa" required
                                        placeholder="08XXXXXXXXXX"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('no_wa')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_ktp"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">NIK
                                        (Sesuai KTP)</label>
                                    <input type="text" wire:model="no_ktp" id="no_ktp" required
                                        placeholder="317XXXXXXXXXXXXX"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('no_ktp')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_kelamin"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Jenis
                                        Kelamin</label>
                                    <div class="relative group">
                                        <select wire:model="jenis_kelamin" id="jenis_kelamin" required
                                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki-laki (Male)</option>
                                            <option value="P">Perempuan (Female)</option>
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('jenis_kelamin')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_lahir"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tempat
                                        Lahir</label>
                                    <input type="text" wire:model="tempat_lahir" id="tempat_lahir" required
                                        placeholder="Jakarta"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('tempat_lahir')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lahir"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tanggal
                                        Lahir</label>
                                    <input type="date" wire:model="tanggal_lahir" id="tanggal_lahir" required
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('tanggal_lahir')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat_rumah"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Alamat
                                        Rumah (Sesuai KTP)</label>
                                    <textarea wire:model="alamat_rumah" id="alamat_rumah" rows="3" required
                                        placeholder="Jl. Margonda Raya No. 1, Depok, Jawa Barat"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none resize-none"></textarea>
                                    @error('alamat_rumah')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Academic Data Section --}}
                    <div
                        class="mt-16 pt-16 border-t border-slate-100 dark:border-zinc-800 lg:grid lg:grid-cols-3 lg:gap-x-12">
                        <div class="lg:col-span-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Informasi Akademik</h3>
                            <p class="text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">Berikan detail akademik
                                Anda untuk menentukan skema sertifikasi yang paling relevan.</p>
                        </div>

                        <div class="mt-8 lg:mt-0 lg:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="nim"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">NIM
                                        (Nomor Induk Mahasiswa)</label>
                                    <input type="text" wire:model="nim" id="nim" required
                                        placeholder="2110XXXXXX"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('nim')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pendidikan_terakhir"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Pendidikan
                                        Terakhir</label>
                                    <input type="text" wire:model="pendidikan_terakhir" id="pendidikan_terakhir"
                                        required placeholder="SMK / SMA / D3"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('pendidikan_terakhir')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="fakultas"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Fakultas</label>
                                    <div class="relative group">
                                        <select wire:model.live="fakultas" id="fakultas" required
                                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer">
                                            <option value="">Pilih Fakultas</option>
                                            @foreach ($this->faculties as $fac)
                                                <option value="{{ $fac }}">{{ $fac }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('fakultas')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="program_studi"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Program
                                        Studi</label>
                                    <div class="relative group">
                                        <select wire:model.live="program_studi" id="program_studi" required
                                            @disabled(!$fakultas)
                                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">Pilih Program Studi</option>
                                            @foreach ($this->studyPrograms as $prodi)
                                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('program_studi')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="total_sks"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Total
                                        SKS Tempuh</label>
                                    <input type="number" wire:model="total_sks" id="total_sks" required
                                        placeholder="144"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('total_sks')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status_semester"
                                        class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Status
                                        Semester</label>
                                    <input type="text" wire:model="status_semester" id="status_semester" required
                                        placeholder="Aktif (Ganjil 2024/2025)"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                                    @error('status_semester')
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-16 pt-8 border-t border-slate-100 dark:border-zinc-800">
                        <button type="button" wire:click="nextStep"
                            class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500">
                            Lanjut ke Skema
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7-7 7M3 12h18" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif


            @if ($currentStep === 2)
                <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">
                    <div class="lg:col-span-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Pilih Skema Sertifikasi</h3>
                        <p class="text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">Pilih skema kompetensi yang
                            sesuai dengan Program Studi atau sertifikasi yang ingin Anda ambil.</p>

                        <div
                            class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
                            <p
                                class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-1">
                                Data Akademik</p>
                            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">{{ $fakultas }}
                                — {{ $program_studi }}</p>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-0 lg:col-span-2 space-y-8">
                        @if ($this->availableSchemes->isEmpty())
                            <div
                                class="flex flex-col items-center justify-center p-12 bg-red-50/50 dark:bg-red-900/10 rounded-2xl border border-dashed border-red-200 dark:border-red-800">
                                <div
                                    class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-600 dark:text-red-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-red-900 dark:text-red-300">Skema Tidak Tersedia</h4>
                                <p class="text-sm text-red-700 dark:text-red-400 mt-1">Maaf, saat ini belum ada skema
                                    kompetensi yang dibuka untuk prodi Anda.</p>
                                <button type="button" wire:click="previousStep"
                                    class="mt-6 text-sm font-semibold text-red-600 hover:text-red-800 underline">Kembali
                                    dan cek Data Akademik</button>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($this->availableSchemes as $scheme)
                                    <label for="scheme-{{ $scheme->id }}" @class([
                                        'relative flex items-start gap-4 p-6 rounded-2xl border-2 transition-all cursor-pointer group',
                                        'border-emerald-500 bg-emerald-50/30 dark:bg-emerald-900/10 ring-4 ring-emerald-500/5 shadow-lg shadow-emerald-500/10' =>
                                            $scheme_id == $scheme->id,
                                        'border-slate-100 dark:border-zinc-800 hover:border-slate-300 dark:hover:border-zinc-700 bg-white dark:bg-zinc-950' =>
                                            $scheme_id != $scheme->id,
                                    ])>
                                        <div class="flex h-6 items-center">
                                            <input id="scheme-{{ $scheme->id }}" name="scheme_id" type="radio"
                                                value="{{ $scheme->id }}" wire:model.live="scheme_id"
                                                class="h-5 w-5 border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between">
                                                <p @class([
                                                    'text-lg font-bold',
                                                    'text-emerald-910 dark:text-emerald-300' => $scheme_id == $scheme->id,
                                                    'text-gray-900 dark:text-white' => $scheme_id != $scheme->id,
                                                ])>{{ $scheme->name }}</p>

                                                @if ($scheme_id == $scheme->id)
                                                    <span
                                                        class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Selected</span>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400 leading-relaxed">
                                                {{ $scheme->description }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('scheme_id')
                                <p class="mt-4 text-center text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        @endif

                        <div
                            class="flex items-center justify-between mt-12 pt-12 border-t border-slate-100 dark:border-zinc-800">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center justify-center px-6 py-3 font-bold text-slate-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>

                            <button type="button" wire:click="nextStep" @disabled($this->availableSchemes->isEmpty() || !$scheme_id)
                                class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500">
                                Lanjut ke Berkas
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7M3 12h18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($currentStep === 3)
                <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">
                    <div class="lg:col-span-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Upload Dokumen</h3>
                        <p class="text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">Silakan lampirkan dokumen
                            persyaratan sesuai format yang ditentukan (PDF/Gambar, Maks. 2MB).</p>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-zinc-400">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Format PDF untuk Form APL</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-zinc-400">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>KTM/KTP dalam format Image/PDF</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-0 lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Passport Photo Section (Custom) --}}
                            <div class="md:col-span-2" wire:ignore.self x-data="{
                                image: null,
                                cropper: null,
                                showModal: false,
                                init() {
                                    this.$watch('showModal', value => {
                                        if (value) {
                                            this.$nextTick(() => {
                                                const img = this.$refs.cropImage;
                                                this.cropper = new Cropper(img, {
                                                    aspectRatio: 3 / 4,
                                                    viewMode: 1,
                                                    autoCropArea: 1,
                                                    dragMode: 'move',
                                                    background: false,
                                                    ready: () => {
                                                        // Ensure the crop box is visible
                                                    }
                                                });
                                            });
                                        } else if (this.cropper) {
                                            this.cropper.destroy();
                                            this.cropper = null;
                                        }
                                    });
                                },
                                onFileChange(e) {
                                    const file = e.target.files[0];
                                    if (file) {
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
                                    @this.set('passport_photo_base64', base64);
                                    this.showModal = false;
                                }
                            }">
                                <label class="block text-sm font-bold text-gray-700 dark:text-zinc-300 mb-3">Pas Foto
                                    (Formal 3x4)</label>

                                <div class="flex flex-col sm:flex-row gap-6 items-start">
                                    {{-- Preview Area --}}
                                    <div
                                        class="relative w-32 aspect-3-4 rounded-xl border-2 border-dashed border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 overflow-hidden group flex items-center justify-center">
                                        @if ($passport_photo_base64)
                                            <img src="{{ $passport_photo_base64 }}"
                                                class="w-full h-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click="$refs.fileInput.click()"
                                                    class="p-2 bg-white rounded-full text-zinc-900 shadow-lg">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif($passport_photo)
                                            <img src="{{ $passport_photo->temporaryUrl() }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="text-center p-4">
                                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-[10px] font-medium text-slate-400">Kosong</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <div class="relative group max-w-sm">
                                            <input type="file" @change="onFileChange" wire:model="passport_photo"
                                                x-ref="fileInput" accept=".jpg,.jpeg,.png"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div @class([
                                                'flex items-center gap-4 px-5 py-4 rounded-2xl border-2 border-dashed transition-all',
                                                'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/10' =>
                                                    $passport_photo || $passport_photo_base64,
                                                'border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 group-hover:border-slate-300 dark:group-hover:border-zinc-700' =>
                                                    !$passport_photo && !$passport_photo_base64,
                                            ])>
                                                <div @class([
                                                    'p-2.5 rounded-xl',
                                                    'bg-emerald-500 text-white' => $passport_photo || $passport_photo_base64,
                                                    'bg-slate-100 dark:bg-zinc-800 text-slate-400' =>
                                                        !$passport_photo && !$passport_photo_base64,
                                                ])>
                                                    <div wire:loading wire:target="passport_photo">
                                                        <svg class="w-6 h-6 animate-spin" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div wire:loading.remove wire:target="passport_photo">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-zinc-100">
                                                        <span wire:loading
                                                            wire:target="passport_photo">Uploading...</span>
                                                        <span wire:loading.remove wire:target="passport_photo">
                                                            {{ $passport_photo_base64 ? 'Foto Telah Diatur' : ($passport_photo ? $passport_photo->getClientOriginalName() : 'Pilih Pas Foto Formal') }}
                                                        </span>
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-0.5">JPG, PNG (Maks. 2MB).
                                                        Rasio 3:4 akan diterapkan.</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($passport_photo_base64 || $passport_photo)
                                            <button type="button"
                                                @click="if(image) showModal = true; else $refs.fileInput.click()"
                                                class="mt-3 text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
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

                                {{-- Cropper Modal --}}
                                <div x-show="showModal"
                                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zinc-950/80"
                                    style="display: none;">
                                    <div class="bg-white dark:bg-zinc-900 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl border border-white/10"
                                        wire:ignore>
                                        <div
                                            class="p-6 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                                            <h4 class="text-lg font-bold">Sesuaikan Pas Foto</h4>
                                            <button type="button" @click="showModal = false"
                                                class="text-slate-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-6">
                                            <div
                                                class="max-h-[60vh] overflow-hidden rounded-2xl bg-zinc-100 dark:bg-zinc-950">
                                                <img x-ref="cropImage" :src="image"
                                                    class="max-w-full block">
                                            </div>
                                            <p class="mt-4 text-sm text-slate-500 text-center">Geser dan atur kotak
                                                untuk menyesuaikan komposisi foto (Rasio 3:4).</p>
                                        </div>
                                        <div
                                            class="p-6 bg-slate-50 dark:bg-zinc-950/50 flex items-center justify-end gap-3">
                                            <button type="button" @click="showModal = false"
                                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                                            <button type="button" @click="saveCrop"
                                                class="px-8 py-2.5 bg-emerald-400 hover:bg-emerald-500 text-black font-bold rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all">Terapkan
                                                Crop</button>
                                        </div>
                                    </div>
                                </div>

                                @error('passport_photo')
                                    <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @php
                                $files = [
                                    'fr_apl_01' => ['label' => 'FR APL 01 (PDF)', 'accept' => '.pdf'],
                                    'fr_apl_02' => ['label' => 'FR APL 02 (PDF)', 'accept' => '.pdf'],
                                    'ktm' => ['label' => 'KTM / Student ID', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                                    'khs' => ['label' => 'KHS / Transcript (PDF)', 'accept' => '.pdf'],
                                    'ktp' => ['label' => 'KTP / Identity Card', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                                    'internship_certificate' => [
                                        'label' => 'Sertifikat Magang (Opsional)',
                                        'accept' => '.pdf',
                                        'optional' => true,
                                    ],
                                ];
                            @endphp

                            @foreach ($files as $key => $file)
                                <div @class(['md:col-span-2' => $key === 'passport_photo'])>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">
                                        {{ $file['label'] }}
                                        @if (isset($file['optional']))
                                            <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                                        @endif
                                    </label>
                                    <div class="relative group">
                                        <input type="file" wire:model="{{ $key }}"
                                            accept="{{ $file['accept'] }}"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div @class([
                                            'flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed transition-all',
                                            'border-emerald-500 bg-emerald-50/30 dark:bg-emerald-900/10' => $this->$key,
                                            'border-slate-200 dark:border-zinc-800 group-hover:border-slate-300 dark:group-hover:border-zinc-700 bg-slate-50/50 dark:bg-zinc-950/50' => !$this->$key,
                                        ])>
                                            <div @class([
                                                'p-2 rounded-lg',
                                                'bg-emerald-500 text-white' => $this->$key,
                                                'bg-white dark:bg-zinc-900 text-slate-400 border border-slate-100 dark:border-zinc-800' => !$this->$key,
                                            ])>
                                                @if ($this->$key)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p @class([
                                                    'text-sm font-medium truncate',
                                                    'text-emerald-700 dark:text-emerald-400' => $this->$key,
                                                    'text-slate-500' => !$this->$key,
                                                ])>
                                                    {{ $this->$key ? $this->$key->getClientOriginalName() : 'Pilih file atau drop disini' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @error($key)
                                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="flex items-center justify-between mt-12 pt-12 border-t border-slate-100 dark:border-zinc-800">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center justify-center px-6 py-3 font-bold text-slate-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>

                            <button type="button" wire:click="nextStep"
                                class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500">
                                Lanjut ke Ringkasan
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7M3 12h18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($currentStep === 4)
                <div class="lg:grid lg:grid-cols-3 lg:gap-x-12">
                    <div class="lg:col-span-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Review & Konfirmasi</h3>
                        <p class="text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">Silakan periksa kembali data
                            Anda sebelum menyelesaikan pendaftaran. Pastikan semua informasi sudah benar.</p>

                        <div
                            class="mt-8 p-6 bg-slate-50 dark:bg-zinc-950 rounded-2xl border border-slate-100 dark:border-zinc-800">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Butuh Bantuan?
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-zinc-400">Jika terdapat kesalahan data yang
                                tidak bisa diubah, silakan hubungi admin Sertifikasi LSP.</p>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-0 lg:col-span-2 space-y-8">
                        {{-- Data Summary Card --}}
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-2xl p-6 sm:p-8 border border-slate-100 dark:border-zinc-800 shadow-sm">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6">Ringkasan
                                Pendaftaran</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">
                                <div>
                                    <p class="text-xs font-medium text-slate-400 uppercase">Nama Lengkap</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-400 uppercase">NIM / NIK</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $nim }} / {{ $no_ktp }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-400 uppercase">Fakultas / Prodi</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                        {{ $fakultas }} — {{ $program_studi }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-400 uppercase">Skema Sertifikasi</p>
                                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                        {{ \App\Models\Scheme::find($scheme_id)?->name ?? 'Belum dipilih' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Instruction Card --}}
                        <div
                            class="bg-emerald-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-200 dark:shadow-none relative overflow-hidden">
                            <div class="relative z-10">
                                <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-100 mb-6 font-pj">
                                    Instruksi Pembayaran</h4>
                                <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-emerald-100 uppercase">Virtual Account
                                            Number</p>
                                        <p class="text-3xl font-mono font-bold mt-1 tracking-wider text-white">
                                            98{{ $nim }}</p>
                                    </div>
                                    <div
                                        class="bg-emerald-500/50 backdrop-blur-sm rounded-xl px-4 py-3 border border-emerald-400/30">
                                        <p class="text-xs font-medium text-emerald-100">Bank Tujuan</p>
                                        <p class="font-bold">BANK BNI (Virtual Account)</p>
                                    </div>
                                </div>
                                <div
                                    class="mt-8 pt-8 border-t border-emerald-500/50 text-emerald-50 text-sm leading-relaxed">
                                    <ul class="space-y-3">
                                        <li class="flex items-start gap-3">
                                            <span
                                                class="flex-shrink-0 w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 shadow-sm">1</span>
                                            <span>Salin nomor Virtual Account diatas untuk melakukan pembayaran.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span
                                                class="flex-shrink-0 w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 shadow-sm">2</span>
                                            <span>Pastikan nominal yang muncul sesuai dengan tagihan skema yang
                                                dipilih.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span
                                                class="flex-shrink-0 w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 shadow-sm">3</span>
                                            <span>Akun login akan dikirimkan ke email
                                                <strong>{{ $email }}</strong> setelah pembayaran diverifikasi
                                                sistem.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            {{-- Decorative SVG --}}
                            <svg class="absolute -right-10 -bottom-10 w-64 h-64 text-emerald-500 opacity-20 pointer-events-none transform rotate-12"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                        </div>

                        <div
                            class="flex items-center justify-between mt-12 pt-12 border-t border-slate-100 dark:border-zinc-800">
                            <button type="button" wire:click="previousStep"
                                class="inline-flex items-center justify-center px-6 py-3 font-bold text-slate-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>

                            <button type="button" wire:click="submit" wire:loading.attr="disabled"
                                class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-200 bg-zinc-900 dark:bg-white dark:text-zinc-900 rounded-2xl hover:bg-zinc-800 dark:hover:bg-zinc-100 shadow-xl disabled:opacity-50">
                                <span wire:loading.remove wire:target="submit">Selesaikan Pendaftaran</span>
                                <span wire:loading wire:target="submit">Memproses...</span>
                                <svg wire:loading.remove wire:target="submit"
                                    class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($currentStep === 5)
                <div class="p-12 sm:p-20 text-center">
                    <div class="relative inline-flex mb-10">
                        <div class="absolute inset-0 rounded-full bg-emerald-500 blur-2xl opacity-20 animate-pulse">
                        </div>
                        <div
                            class="relative flex items-center justify-center w-24 h-24 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">Pendaftaran Berhasil!</h2>
                    <p class="text-slate-500 dark:text-zinc-400 max-w-lg mx-auto leading-relaxed mb-12">
                        Terima kasih telah mendaftar di LSP UPNVJ. Tim kami akan melakukan verifikasi setelah pembayaran
                        Anda diterima. Informasi login akan dikirimkan secara otomatis ke email Anda.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('login') }}" wire:navigate
                            class="inline-flex items-center px-8 py-3.5 font-bold text-black bg-emerald-400 rounded-xl hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-200 dark:shadow-none">
                            Ke Halaman Login
                        </a>
                        <button type="button" onclick="window.print()"
                            class="inline-flex items-center px-8 py-3.5 font-bold text-slate-700 dark:text-zinc-300 bg-slate-100 dark:bg-zinc-800 rounded-xl hover:bg-slate-200 dark:hover:bg-zinc-700 transition-all">
                            Cetak Bukti Daftar
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 4px;
        }

        .aspect-3-4 {
            aspect-ratio: 3 / 4;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endpush
```
