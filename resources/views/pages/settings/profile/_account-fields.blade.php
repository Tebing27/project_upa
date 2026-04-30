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
