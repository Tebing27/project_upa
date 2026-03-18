<x-layouts.auth :title="__('Two-factor authentication')">
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Authentication code')"
                    :description="__('Enter the authentication code provided by your authenticator application.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Recovery code')"
                    :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-5">
                            <input
                                type="text"
                                name="code"
                                id="code"
                                autofocus
                                x-model="code"
                                class="block w-full text-center tracking-[1em] font-mono rounded-md border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-xl sm:leading-6"
                                placeholder="000000"
                                maxlength="6"
                            >
                        </div>
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-5">
                            <input
                                type="text"
                                name="recovery_code"
                                id="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                                placeholder="Recovery Code"
                                class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6"
                            />
                        </div>

                        @error('recovery_code')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full justify-center inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors"
                    >
                        {{ __('Continue') }}
                    </button>
                </div>

                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center">
                    <span class="text-zinc-500 dark:text-zinc-400 opacity-50">{{ __('or you can') }}</span>
                    <div class="inline font-medium underline cursor-pointer opacity-80 text-zinc-900 dark:text-white">
                        <span x-show="!showRecoveryInput" @click="toggleInput()">{{ __('login using a recovery code') }}</span>
                        <span x-show="showRecoveryInput" @click="toggleInput()">{{ __('login using an authentication code') }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.auth>
