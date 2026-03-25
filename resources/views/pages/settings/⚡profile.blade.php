<?php

use App\Concerns\ProfileValidationRules;
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
    public ?string $pendidikan_terakhir = null;
    public ?int $total_sks = null;
    public ?string $status_semester = null;
    public ?string $fakultas = null;
    public ?string $program_studi = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->no_ktp = $user->no_ktp;
        $this->tempat_lahir = $user->tempat_lahir;
        $this->tanggal_lahir = $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->alamat_rumah = $user->alamat_rumah;
        $this->no_wa = $user->no_wa;
        $this->pendidikan_terakhir = $user->pendidikan_terakhir;
        $this->total_sks = $user->total_sks;
        $this->status_semester = $user->status_semester;
        $this->fakultas = $user->fakultas;
        $this->program_studi = $user->program_studi;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        // Convert empty strings to null for optional fields
        $this->nim = $this->nim ?: null;
        $this->no_ktp = $this->no_ktp ?: null;
        $this->tempat_lahir = $this->tempat_lahir ?: null;
        $this->tanggal_lahir = $this->tanggal_lahir ?: null;
        $this->jenis_kelamin = $this->jenis_kelamin ?: null;
        $this->alamat_rumah = $this->alamat_rumah ?: null;
        $this->no_wa = $this->no_wa ?: null;
        $this->pendidikan_terakhir = $this->pendidikan_terakhir ?: null;
        $this->status_semester = $this->status_semester ?: null;
        $this->fakultas = $this->fakultas ?: null;
        $this->program_studi = $this->program_studi ?: null;

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
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
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Profile settings') }}</h2>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Name') }}</label>
                    <div class="mt-2">
                        <input type="text" wire:model="name" id="name" required autofocus autocomplete="name" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Email') }}</label>
                    <div class="mt-2">
                        <input type="email" wire:model="email" id="email" required autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                    @if ($this->hasUnverifiedEmail)
                        <div class="mt-4">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ __('Your email address is unverified.') }}

                                <button type="button" class="text-zinc-900 dark:text-white underline hover:no-underline" wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <label for="nim" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">NIM / NIK</label>
                    <div class="mt-2">
                        <input type="text" wire:model="nim" id="nim" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('nim') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_ktp" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">No KTP</label>
                    <div class="mt-2">
                        <input type="text" wire:model="no_ktp" id="no_ktp" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('no_ktp') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Tempat Lahir</label>
                    <div class="mt-2">
                        <input type="text" wire:model="tempat_lahir" id="tempat_lahir" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('tempat_lahir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Tanggal Lahir</label>
                    <div class="mt-2">
                        <input type="date" wire:model="tanggal_lahir" id="tanggal_lahir" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('tanggal_lahir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Jenis Kelamin</label>
                    <div class="mt-2">
                        <select wire:model="jenis_kelamin" id="jenis_kelamin" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    @error('jenis_kelamin') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_wa" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">No. WhatsApp</label>
                    <div class="mt-2">
                        <input type="text" wire:model="no_wa" id="no_wa" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('no_wa') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat_rumah" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Alamat Rumah</label>
                    <div class="mt-2">
                        <textarea wire:model="alamat_rumah" id="alamat_rumah" rows="3" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6"></textarea>
                    </div>
                    @error('alamat_rumah') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fakultas" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Fakultas</label>
                    <div class="mt-2">
                        <input type="text" wire:model="fakultas" id="fakultas" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('fakultas') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="program_studi" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Program Studi</label>
                    <div class="mt-2">
                        <input type="text" wire:model="program_studi" id="program_studi" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('program_studi') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pendidikan_terakhir" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Pendidikan Terakhir</label>
                    <div class="mt-2">
                        <input type="text" wire:model="pendidikan_terakhir" id="pendidikan_terakhir" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('pendidikan_terakhir') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="total_sks" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Total SKS</label>
                    <div class="mt-2">
                        <input type="number" wire:model="total_sks" id="total_sks" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('total_sks') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status_semester" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">Status Semester</label>
                    <div class="mt-2">
                        <input type="text" wire:model="status_semester" id="status_semester" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    </div>
                    @error('status_semester') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors" data-test="update-profile-button">
                        {{ __('Save') }}
                    </button>
                </div>

                <x-action-message class="text-sm text-zinc-600 dark:text-zinc-400" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
