<?php

use App\Concerns\ProfileValidationRules;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public ?string $nim = null;
    public ?string $no_ktp = null;
    public ?string $tempat_lahir = null;
    public ?string $tanggal_lahir = null;
    public ?string $jenis_kelamin = null;
    public ?string $alamat_rumah = null;
    public ?string $domisili_provinsi = null;
    public ?string $domisili_kota = null;
    public ?string $domisili_kecamatan = null;
    public ?string $no_wa = null;
    public ?string $pendidikan_terakhir = null;
    public ?string $nama_institusi = null;
    public ?int $total_sks = null;
    public ?string $status_semester = null;
    public ?string $fakultas = null;
    public ?string $program_studi = null;
    public ?string $pekerjaan = null;
    public ?string $nama_perusahaan = null;
    public ?string $jabatan = null;
    public ?string $alamat_perusahaan = null;
    public ?string $kode_pos_perusahaan = null;
    public ?string $no_telp_perusahaan = null;
    public ?string $email_perusahaan = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->no_ktp = $user->no_ktp;
        $this->tempat_lahir = $user->tempat_lahir;
        $this->tanggal_lahir = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->alamat_rumah = $user->alamat_rumah;
        $this->domisili_provinsi = $user->domisili_provinsi;
        $this->domisili_kota = $user->domisili_kota;
        $this->domisili_kecamatan = $user->domisili_kecamatan;
        $this->no_wa = $user->no_wa;
        $this->pendidikan_terakhir = $user->pendidikan_terakhir;
        $this->nama_institusi = $user->nama_institusi;
        $this->total_sks = $user->total_sks;
        $this->status_semester = $user->status_semester;
        $this->fakultas = $user->fakultas;
        $this->program_studi = $user->program_studi;
        $this->pekerjaan = $user->pekerjaan;
        $this->nama_perusahaan = $user->nama_perusahaan;
        $this->jabatan = $user->jabatan;
        $this->alamat_perusahaan = $user->alamat_perusahaan;
        $this->kode_pos_perusahaan = $user->kode_pos_perusahaan;
        $this->no_telp_perusahaan = $user->no_telp_perusahaan;
        $this->email_perusahaan = $user->email_perusahaan;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        foreach ([
            'nim', 'no_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_rumah',
            'domisili_provinsi', 'domisili_kota', 'domisili_kecamatan', 'no_wa', 'pendidikan_terakhir',
            'nama_institusi', 'status_semester', 'fakultas', 'program_studi', 'pekerjaan',
            'nama_perusahaan', 'jabatan', 'alamat_perusahaan', 'kode_pos_perusahaan',
            'no_telp_perusahaan', 'email_perusahaan',
        ] as $field) {
            $this->{$field} = filled($this->{$field}) ? trim((string) $this->{$field}) : null;
        }

        $this->total_sks = filled($this->total_sks) ? (int) $this->total_sks : null;

        $validated = $this->validate($this->profileRules($user->id, $user->user_type));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->syncProfileCompletionStatus();
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    #[Computed]
    public function isGeneralUser(): bool
    {
        return Auth::user()->isGeneralUser();
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-pages::settings.layout
        :heading="$this->isGeneralUser ? 'Biodata Peserta' : 'Profile'"
        :subheading="$this->isGeneralUser ? 'Lengkapi biodata peserta umum sebelum mendaftar skema sertifikasi.' : 'Update your profile information and academic data.'"
    >
        <form wire:submit="updateProfileInformation" class="my-6 space-y-8">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-zinc-900 dark:text-white">{{ $this->isGeneralUser ? 'Nama Lengkap*' : 'Name' }}</label>
                        <input id="name" type="text" wire:model="name" required class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if ($this->isGeneralUser)
                            <p class="mt-2 text-sm font-medium text-emerald-700">Nama akan tertera di sertifikat jika kompeten.</p>
                        @endif
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-zinc-900 dark:text-white">Email{{ $this->isGeneralUser ? '*' : '' }}</label>
                        <input id="email" type="email" wire:model="email" required class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                        @if ($this->hasUnverifiedEmail)
                            <div class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __('Your email address is unverified.') }}
                                <button type="button" class="text-zinc-900 underline dark:text-white" wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($this->isGeneralUser)
                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">a. Data Pribadi</h3>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="no_ktp" class="block text-sm font-medium text-zinc-900 dark:text-white">NIK*</label>
                            <input id="no_ktp" type="text" wire:model="no_ktp" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_ktp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <span class="block text-sm font-medium text-zinc-900 dark:text-white">Jenis Kelamin*</span>
                            <div class="mt-3 flex gap-6">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" wire:model="jenis_kelamin" value="L"> Laki-Laki</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" wire:model="jenis_kelamin" value="P"> Perempuan</label>
                            </div>
                            @error('jenis_kelamin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-zinc-900 dark:text-white">Tempat Lahir*</label>
                            <input id="tempat_lahir" type="text" wire:model="tempat_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('tempat_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-zinc-900 dark:text-white">Tanggal Lahir*</label>
                            <input id="tanggal_lahir" type="date" wire:model="tanggal_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('tanggal_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_rumah" class="block text-sm font-medium text-zinc-900 dark:text-white">Alamat Domisili / Sesuai KTP*</label>
                            <textarea id="alamat_rumah" wire:model="alamat_rumah" rows="4" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"></textarea>
                            @error('alamat_rumah') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="domisili_provinsi" class="block text-sm font-medium text-zinc-900 dark:text-white">Provinsi*</label>
                            <input id="domisili_provinsi" type="text" wire:model="domisili_provinsi" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('domisili_provinsi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="domisili_kota" class="block text-sm font-medium text-zinc-900 dark:text-white">Kota / Kabupaten*</label>
                            <input id="domisili_kota" type="text" wire:model="domisili_kota" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('domisili_kota') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="domisili_kecamatan" class="block text-sm font-medium text-zinc-900 dark:text-white">Kecamatan*</label>
                            <input id="domisili_kecamatan" type="text" wire:model="domisili_kecamatan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('domisili_kecamatan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="no_wa" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telp / No WhatsApp Aktif*</label>
                            <input id="no_wa" type="text" wire:model="no_wa" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_wa') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="pendidikan_terakhir" class="block text-sm font-medium text-zinc-900 dark:text-white">Pendidikan Terakhir*</label>
                            <input id="pendidikan_terakhir" type="text" wire:model="pendidikan_terakhir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('pendidikan_terakhir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-zinc-900 dark:text-white">Fakultas</label>
                            <input id="fakultas" type="text" wire:model="fakultas" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('fakultas') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="nama_institusi" class="block text-sm font-medium text-zinc-900 dark:text-white">Nama Sekolah / Perguruan Tinggi*</label>
                            <input id="nama_institusi" type="text" wire:model="nama_institusi" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('nama_institusi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="program_studi" class="block text-sm font-medium text-zinc-900 dark:text-white">Jurusan / Program Studi*</label>
                            <input id="program_studi" type="text" wire:model="program_studi" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('program_studi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">b. Data Pekerjaan Sekarang</h3>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="pekerjaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Pekerjaan*</label>
                            <input id="pekerjaan" type="text" wire:model="pekerjaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('pekerjaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="nama_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Nama Perusahaan</label>
                            <input id="nama_perusahaan" type="text" wire:model="nama_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('nama_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="jabatan" class="block text-sm font-medium text-zinc-900 dark:text-white">Jabatan</label>
                            <input id="jabatan" type="text" wire:model="jabatan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('jabatan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="alamat_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Alamat Lembaga / Perusahaan</label>
                            <textarea id="alamat_perusahaan" wire:model="alamat_perusahaan" rows="3" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"></textarea>
                            @error('alamat_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="kode_pos_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Kode POS Perusahaan</label>
                            <input id="kode_pos_perusahaan" type="text" wire:model="kode_pos_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('kode_pos_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="no_telp_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telp Perusahaan</label>
                            <input id="no_telp_perusahaan" type="text" wire:model="no_telp_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_telp_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Email Perusahaan</label>
                            <input id="email_perusahaan" type="email" wire:model="email_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('email_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Data Mahasiswa UPNVJ</h3>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div><label for="nim" class="block text-sm font-medium">NIM</label><input id="nim" type="text" wire:model="nim" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('nim') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="no_wa" class="block text-sm font-medium">No. WhatsApp</label><input id="no_wa" type="text" wire:model="no_wa" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('no_wa') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="fakultas" class="block text-sm font-medium">Fakultas</label><input id="fakultas" type="text" wire:model="fakultas" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('fakultas') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="program_studi" class="block text-sm font-medium">Program Studi</label><input id="program_studi" type="text" wire:model="program_studi" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('program_studi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="pendidikan_terakhir" class="block text-sm font-medium">Pendidikan Terakhir</label><input id="pendidikan_terakhir" type="text" wire:model="pendidikan_terakhir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('pendidikan_terakhir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="total_sks" class="block text-sm font-medium">Total SKS</label><input id="total_sks" type="number" wire:model="total_sks" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('total_sks') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="status_semester" class="block text-sm font-medium">Status Semester</label><input id="status_semester" type="text" wire:model="status_semester" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('status_semester') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="no_ktp" class="block text-sm font-medium">No KTP</label><input id="no_ktp" type="text" wire:model="no_ktp" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('no_ktp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="tempat_lahir" class="block text-sm font-medium">Tempat Lahir</label><input id="tempat_lahir" type="text" wire:model="tempat_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('tempat_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="tanggal_lahir" class="block text-sm font-medium">Tanggal Lahir</label><input id="tanggal_lahir" type="date" wire:model="tanggal_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">@error('tanggal_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div><label for="jenis_kelamin" class="block text-sm font-medium">Jenis Kelamin</label><select id="jenis_kelamin" wire:model="jenis_kelamin" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"><option value="">Pilih Jenis Kelamin</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>@error('jenis_kelamin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                        <div class="md:col-span-2"><label for="alamat_rumah" class="block text-sm font-medium">Alamat Rumah</label><textarea id="alamat_rumah" wire:model="alamat_rumah" rows="3" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"></textarea>@error('alamat_rumah') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-xl bg-zinc-900 px-4 py-3 text-sm font-semibold text-white dark:bg-white dark:text-zinc-900" data-test="update-profile-button">Save</button>
                <x-action-message class="text-sm text-zinc-600 dark:text-zinc-400" on="profile-updated">Saved.</x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
