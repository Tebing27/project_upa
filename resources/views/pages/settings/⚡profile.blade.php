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
    public ?string $no_wa = null;
    public ?string $kebangsaan = null;
    public ?string $kode_pos_rumah = null;
    public ?string $telp_rumah = null;
    public ?string $telp_kantor = null;
    public ?string $kualifikasi_pendidikan = null;
    public ?int $total_sks = null;
    public ?string $status_semester = null;
    public ?string $fakultas = null;
    public ?string $program_studi = null;
    public ?string $nama_perusahaan = null;
    public ?string $jabatan = null;
    public ?string $alamat_perusahaan = null;
    public ?string $kode_pos_perusahaan = null;
    public ?string $no_telp_perusahaan = null;
    public ?string $email_perusahaan = null;
    public ?string $fax_perusahaan = null;

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
        $this->no_wa = $user->no_wa;
        $this->kebangsaan = $user->umumProfile?->kebangsaan;
        $this->kode_pos_rumah = $user->profile?->kode_pos_rumah;
        $this->telp_rumah = $user->profile?->telp_rumah;
        $this->telp_kantor = $user->profile?->telp_kantor;
        $this->kualifikasi_pendidikan = $user->kualifikasi_pendidikan;
        $this->total_sks = $user->total_sks;
        $this->status_semester = $user->status_semester;
        $this->fakultas = $user->fakultas;
        $this->program_studi = $user->program_studi;
        $this->nama_perusahaan = $user->nama_perusahaan;
        $this->jabatan = $user->jabatan;
        $this->alamat_perusahaan = $user->alamat_perusahaan;
        $this->kode_pos_perusahaan = $user->kode_pos_perusahaan;
        $this->no_telp_perusahaan = $user->no_telp_perusahaan;
        $this->email_perusahaan = $user->email_perusahaan;
        $this->fax_perusahaan = $user->umumProfile?->fax_perusahaan;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        $rules = $this->profileRules($user->id, $user->user_type);

        $rules['name'] = $rules['nama'];
        unset($rules['nama']);

        foreach ([
            'nim', 'no_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_rumah',
            'no_wa', 'kebangsaan', 'kode_pos_rumah', 'telp_rumah', 'telp_kantor', 'kualifikasi_pendidikan',
            'status_semester', 'fakultas', 'program_studi',
            'nama_perusahaan', 'jabatan', 'alamat_perusahaan', 'kode_pos_perusahaan',
            'no_telp_perusahaan', 'email_perusahaan', 'fax_perusahaan',
        ] as $field) {
            $this->{$field} = filled($this->{$field}) ? trim((string) $this->{$field}) : null;
        }

        $this->total_sks = filled($this->total_sks) ? (int) $this->total_sks : null;

        $validated = $this->validate($rules);

        $user->fill([
            'nama' => $validated['name'] ?? $user->nama,
            'email' => $validated['email'] ?? $user->email,
        ]);

        $emailChanged = $user->isDirty('email');

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();
        $user->profile()->updateOrCreate([], [
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'alamat_rumah' => $validated['alamat_rumah'] ?? null,
            'kode_pos_rumah' => $validated['kode_pos_rumah'] ?? null,
            'telp_rumah' => $validated['telp_rumah'] ?? null,
            'telp_kantor' => $validated['telp_kantor'] ?? null,
            'no_wa' => $validated['no_wa'] ?? null,
        ]);
        if ($user->isUpnvjUser()) {
            $user->mahasiswaProfile()->updateOrCreate([], [
                'nim' => $validated['nim'] ?? null,
                'total_sks' => $validated['total_sks'] ?? null,
                'status_semester' => $validated['status_semester'] ?? null,
                'fakultas' => $validated['fakultas'] ?? null,
                'program_studi' => $validated['program_studi'] ?? null,
            ]);
        }
        $user->umumProfile()->updateOrCreate([], [
            'no_ktp' => $validated['no_ktp'] ?? null,
            'kebangsaan' => $validated['kebangsaan'] ?? null,
            'kualifikasi_pendidikan' => $validated['kualifikasi_pendidikan'] ?? null,
            'nama_perusahaan' => $validated['nama_perusahaan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'alamat_perusahaan' => $validated['alamat_perusahaan'] ?? null,
            'kode_pos_perusahaan' => $validated['kode_pos_perusahaan'] ?? null,
            'no_telp_perusahaan' => $validated['no_telp_perusahaan'] ?? null,
            'email_perusahaan' => $validated['email_perusahaan'] ?? null,
            'fax_perusahaan' => $validated['fax_perusahaan'] ?? null,
        ]);
        $user->refresh();
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
            @include('pages.settings.profile._account-fields')

            @if ($this->isGeneralUser)
                @include('pages.settings.profile._general-personal-data')
                @include('pages.settings.profile._general-institution-data')
            @else
                @include('pages.settings.profile._student-data')
            @endif

            @include('pages.settings.profile._form-actions')
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
