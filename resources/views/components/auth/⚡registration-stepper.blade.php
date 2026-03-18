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

new class extends Component
{
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
    public function availableSchemes()
    {
        if (!$this->fakultas || !$this->program_studi) {
            return collect();
        }

        return Scheme::where('faculty', $this->fakultas)
            ->where('study_program', $this->program_studi)
            ->where('is_active', true)
            ->get();
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

    #[Validate('required|file|mimes:jpg,jpeg,png|max:2048')]
    public $passport_photo;

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validateOnly('email');
            $this->validateOnly('name');
            $this->validateOnly('nim');
            $this->validateOnly('no_ktp');
            $this->validateOnly('tempat_lahir');
            $this->validateOnly('tanggal_lahir');
            $this->validateOnly('jenis_kelamin');
            $this->validateOnly('alamat_rumah');
            $this->validateOnly('no_wa');
            $this->validateOnly('pendidikan_terakhir');
            $this->validateOnly('total_sks');
            $this->validateOnly('status_semester');
            $this->validateOnly('fakultas');
            $this->validateOnly('program_studi');
        } elseif ($this->currentStep === 2) {
            $this->validateOnly('scheme_id');
        } elseif ($this->currentStep === 3) {
            $this->validateOnly('fr_apl_01');
            $this->validateOnly('fr_apl_02');
            $this->validateOnly('ktm');
            $this->validateOnly('khs');
            $this->validateOnly('internship_certificate');
            $this->validateOnly('ktp');
            $this->validateOnly('passport_photo');
        }

        $this->currentStep++;
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
        $passportPhotoPath = $this->passport_photo->store('documents/photo', 'public');

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
    <div class="max-w-3xl mx-auto py-10">
        <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white mb-6">Registration - Step {{ $currentStep }} of 4</h2>

        @if($currentStep === 1)
            <div class="space-y-6">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">Personal Data</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Email</label>
                        <div class="mt-2">
                            <input type="email" wire:model="email" id="email" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('email') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Full Name</label>
                        <div class="mt-2">
                            <input type="text" wire:model="name" id="name" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nim" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">NIM</label>
                        <div class="mt-2">
                            <input type="text" wire:model="nim" id="nim" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('nim') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="no_ktp" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">No KTP</label>
                        <div class="mt-2">
                            <input type="text" wire:model="no_ktp" id="no_ktp" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('no_ktp') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Place of Birth</label>
                        <div class="mt-2">
                            <input type="text" wire:model="tempat_lahir" id="tempat_lahir" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('tempat_lahir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Date of Birth</label>
                        <div class="mt-2">
                            <input type="date" wire:model="tanggal_lahir" id="tanggal_lahir" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('tanggal_lahir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Gender</label>
                        <div class="mt-2">
                            <select wire:model="jenis_kelamin" id="jenis_kelamin" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                                <option value="" disabled>Select Gender</option>
                                <option value="L">Laki-laki (Male)</option>
                                <option value="P">Perempuan (Female)</option>
                            </select>
                        </div>
                        @error('jenis_kelamin') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="no_wa" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">WhatsApp Number</label>
                        <div class="mt-2">
                            <input type="text" wire:model="no_wa" id="no_wa" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('no_wa') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pendidikan_terakhir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Last Education</label>
                        <div class="mt-2">
                            <input type="text" wire:model="pendidikan_terakhir" id="pendidikan_terakhir" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('pendidikan_terakhir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="total_sks" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Total SKS</label>
                        <div class="mt-2">
                            <input type="number" wire:model="total_sks" id="total_sks" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('total_sks') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status_semester" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Semester Status</label>
                        <div class="mt-2">
                            <input type="text" wire:model="status_semester" id="status_semester" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('status_semester') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fakultas" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Faculty</label>
                        <div class="mt-2">
                            <input type="text" wire:model="fakultas" id="fakultas" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('fakultas') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="program_studi" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Study Program</label>
                        <div class="mt-2">
                            <input type="text" wire:model="program_studi" id="program_studi" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                        </div>
                        @error('program_studi') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div>
                    <label for="alamat_rumah" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Home Address</label>
                    <div class="mt-2">
                        <textarea wire:model="alamat_rumah" id="alamat_rumah" rows="3" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6"></textarea>
                    </div>
                    @error('alamat_rumah') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors">
                        Next Step
                    </button>
                </div>
            </div>
        @endif

        @if($currentStep === 2)
            <div class="space-y-6">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">Jenis Skema Sertifikasi LSP UPNVJ</h3>
                <div class="space-y-4">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Based on your Faculty ({{ $fakultas }}) and Study Program ({{ $program_studi }}).</p>
                    
                    @if($this->availableSchemes->isEmpty())
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-lg">
                            No schemes are currently available for your Faculty and Study Program. Please go back and check your data.
                        </div>
                    @else
                        <fieldset>
                            <legend class="text-sm font-medium leading-6 text-zinc-900 dark:text-white">Select a Scheme</legend>
                            <div class="mt-4 space-y-4">
                                @foreach($this->availableSchemes as $scheme)
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-6 items-center">
                                            <input id="scheme-{{ $scheme->id }}" name="scheme_id" type="radio" value="{{ $scheme->id }}" wire:model="scheme_id" class="h-4 w-4 border-zinc-300 dark:border-zinc-700 text-zinc-900 focus:ring-zinc-900 dark:bg-zinc-800 dark:checked:bg-white dark:checked:text-zinc-900">
                                        </div>
                                        <div class="text-sm leading-6">
                                            <label for="scheme-{{ $scheme->id }}" class="font-medium text-zinc-900 dark:text-white">{{ $scheme->name }}</label>
                                            <p class="text-zinc-500 dark:text-zinc-400">{{ $scheme->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('scheme_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </fieldset>
                    @endif
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors">
                        Back
                    </button>
                    <button type="button" wire:click="nextStep" @disabled($this->availableSchemes->isEmpty()) class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Next Step
                    </button>
                </div>
            </div>
        @endif

        @if($currentStep === 3)
            <div class="space-y-6">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">Document Uploads</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Please upload the required certification documents. Formats allowed: PDF, JPG, PNG (Max 2MB).</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">FR APL 01 (PDF)</label>
                        <input type="file" wire:model="fr_apl_01" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf" required />
                        @error('fr_apl_01') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">FR APL 02 (PDF)</label>
                        <input type="file" wire:model="fr_apl_02" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf" required />
                        @error('fr_apl_02') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">KTM / Student ID (PDF/Image)</label>
                        <input type="file" wire:model="ktm" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf,.jpg,.jpeg,.png" required />
                        @error('ktm') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">KHS / Transcript (PDF)</label>
                        <input type="file" wire:model="khs" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf" required />
                        @error('khs') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Internship Certificate (Optional, PDF)</label>
                        <input type="file" wire:model="internship_certificate" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf" />
                        @error('internship_certificate') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">KTP / Identity Card (Image/PDF)</label>
                        <input type="file" wire:model="ktp" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".pdf,.jpg,.jpeg,.png" required />
                        @error('ktp') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Passport Photo (Image)</label>
                        <input type="file" wire:model="passport_photo" class="mt-2 block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700" accept=".jpg,.jpeg,.png" required />
                        @error('passport_photo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors">
                        Back
                    </button>
                    <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors">
                        Next Step
                    </button>
                </div>
            </div>
        @endif

        @if($currentStep === 4)
            <div class="space-y-6">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">Review & Payment Instructions</h3>
                
                <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6 space-y-4">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white">Data Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="text-zinc-600 dark:text-zinc-400"><span class="font-semibold text-zinc-900 dark:text-white">NIM:</span> {{ $nim }}</div>
                        <div class="text-zinc-600 dark:text-zinc-400"><span class="font-semibold text-zinc-900 dark:text-white">Name:</span> {{ $name }}</div>
                        <div class="text-zinc-600 dark:text-zinc-400"><span class="font-semibold text-zinc-900 dark:text-white">Email:</span> {{ $email }}</div>
                        <div class="text-zinc-600 dark:text-zinc-400"><span class="font-semibold text-zinc-900 dark:text-white">Faculty:</span> {{ $fakultas }}</div>
                        <div class="text-zinc-600 dark:text-zinc-400"><span class="font-semibold text-zinc-900 dark:text-white">Study Program:</span> {{ $program_studi }}</div>
                        <div class="md:col-span-2 text-zinc-600 dark:text-zinc-400">
                            <span class="font-semibold text-zinc-900 dark:text-white">Selected Scheme:</span> 
                            {{ \App\Models\Scheme::find($scheme_id)?->name }}
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-lg p-6 space-y-4">
                    <h4 class="text-lg font-medium text-blue-900 dark:text-blue-300">Payment Instructions</h4>
                    <p class="text-sm text-blue-800 dark:text-blue-400">
                        Please proceed with your payment using the following reference number. 
                    </p>
                    <div class="text-2xl font-mono font-bold text-blue-900 dark:text-blue-300">
                        98{{ $nim }}
                    </div>
                    <ul class="list-disc list-inside text-sm text-blue-800 dark:text-blue-400 mt-2 space-y-1">
                        <li>Transfer to Bank X Virtual Account.</li>
                        <li>Input the Virtual Account number above.</li>
                        <li>Verify the payment amount before confirming.</li>
                        <li>Your account password will be emailed to you after successful verification.</li>
                    </ul>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors">
                        Back
                    </button>
                    <button type="button" wire:click="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Finish Registration
                    </button>
                </div>
            </div>
        @endif

        @if($currentStep === 5)
            <div class="text-center space-y-6 py-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-600 dark:text-green-400">Registration Successful!</h2>
                <p class="text-zinc-600 dark:text-zinc-400 max-w-md mx-auto text-center">
                    Pendaftaran berhasil. Silakan lakukan pembayaran sesuai instruksi. Kata sandi untuk login akan dikirimkan ke email Anda setelah pembayaran terverifikasi.
                </p>
                <div class="pt-6">
                    <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors">
                        Go to Login Page
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>