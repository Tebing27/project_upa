<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-zinc-900 antialiased">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-4xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900">
                    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-zinc-900 text-white">
                        <x-app-logo-icon class="size-5 fill-current" />
                    </div>
                    <span class="truncate">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-400 px-5 py-2 text-sm font-semibold text-black transition hover:bg-emerald-500">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium text-gray-600 transition hover:text-gray-900">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 py-10">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-4xl px-6 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </div>
        </footer>
    </body>
</html>
