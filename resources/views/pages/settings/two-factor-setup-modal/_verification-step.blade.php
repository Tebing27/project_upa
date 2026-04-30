        @if ($showVerificationStep)
            <div class="space-y-6">
                <div class="flex flex-col items-center space-y-3 justify-center">
                    <div class="flex gap-2" x-data="{ code: $wire.entangle('code') }">
                        <input 
                            type="text" 
                            maxlength="6" 
                            x-model="code"
                            placeholder="000000"
                            class="block w-48 text-center tracking-[1em] font-mono text-2xl rounded-md border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white"
                        >
                    </div>
                    @error('code') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center space-x-3">
                    <button
                        type="button"
                        class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors"
                        wire:click="resetVerification"
                    >
                        {{ __('Back') }}
                    </button>

                    <button
                        type="button"
                        class="flex-1 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors disabled:opacity-50"
                        wire:click="confirmTwoFactor"
                        x-bind:disabled="$wire.code.length < 6"
                    >
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
