<x-layouts.auth :title="__('Log in')">
    <div class="flex flex-col gap-8 w-full">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-slate-500 dark:text-zinc-400 mt-2 text-sm leading-relaxed">
                Masuk ke akun Anda untuk melanjutkan proses sertifikasi
            </p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <div
            class="bg-white dark:bg-zinc-900/50 p-8 rounded-3xl border border-slate-100 dark:border-zinc-800 shadow-xl shadow-slate-100 dark:shadow-none">
            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="nim"
                        class="block text-sm font-bold text-gray-700 dark:text-zinc-300 mb-2">{{ __('NIM atau Email') }}</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <x-svg.user />
                        </div>
                        <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                            autocomplete="username" placeholder="Masukkan NIM atau email Anda" required autofocus
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none placeholder:text-slate-400">
                    </div>
                    @error('nim')
                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password"
                            class="block text-sm font-bold text-gray-700 dark:text-zinc-300">{{ __('Password') }}</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                                wire:navigate>
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <x-svg.lock />
                        </div>
                        <input type="password" name="password" id="password" autocomplete="current-password"
                            placeholder="••••••••" required
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none placeholder:text-slate-400">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center gap-2.5 group cursor-pointer">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 dark:border-zinc-700 text-emerald-600 focus:ring-emerald-500/30 transition-all cursor-pointer bg-white dark:bg-zinc-950"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember"
                            class="text-sm font-medium text-slate-600 dark:text-zinc-400 cursor-pointer group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ __('Ingat saya') }}</label>
                    </div>
                </div>

                <x-ui.primary-button type="submit" class="w-full mt-2" data-test="login-button">
                    {{ __('Masuk ke Akun') }}
                    <x-svg.arrow-right />
                </x-ui.primary-button>
            </form>

            @if (Route::has('register'))
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-zinc-800 text-center">
                    <p class="text-sm text-slate-500 dark:text-zinc-400">
                        Bukan mahasiswa atau belum punya akun?
                        <a href="{{ route('register') }}"
                            class="font-bold text-emerald-600 hover:text-emerald-700 underline underline-offset-4 decoration-emerald-200 hover:decoration-emerald-500 transition-all"
                            wire:navigate>
                            Register dengan Email
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.auth>
