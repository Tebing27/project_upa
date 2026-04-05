<x-layouts.auth :title="__('Register')">
    <div class="flex w-full flex-col gap-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Register Akun Non-Mahasiswa
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-zinc-400">
                Buat akun dengan email, lalu lanjutkan verifikasi email sebelum mengakses dashboard.
            </p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <div
            class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl shadow-slate-100 dark:border-zinc-800 dark:bg-zinc-900/50 dark:shadow-none">
            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="email"
                        class="mb-2 block text-sm font-bold text-gray-700 dark:text-zinc-300">Email</label>
                    <input id="email" name="email" type="email" placeholder="Masukkan email Anda"
                        value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white" />
                    @error('email')
                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password"
                        class="mb-2 block text-sm font-bold text-gray-700 dark:text-zinc-300">Password</label>
                    <input id="password" name="password" type="password" placeholder="••••••••" required
                        class="block w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white" />
                    @error('password')
                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation"
                        class="mb-2 block text-sm font-bold text-gray-700 dark:text-zinc-300">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="••••••••" required
                        class="block w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition-all focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white" />
                </div>

                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-400 px-6 py-3.5 text-sm font-bold text-black hover:bg-emerald-500">
                    Buat Akun
                </button>
            </form>

            <div
                class="mt-8 border-t border-slate-100 pt-6 text-center text-sm text-slate-500 dark:border-zinc-800 dark:text-zinc-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700" wire:navigate>
                    Masuk
                </a>
            </div>
        </div>
    </div>
</x-layouts.auth>
