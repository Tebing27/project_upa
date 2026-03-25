<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @stack('styles')
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-zinc-950 antialiased">
        <div class="min-h-svh flex flex-col pt-12 pb-24">
            <div class="w-full">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium mb-12" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-slate-100 dark:border-zinc-800">
                        <x-app-logo-icon class="size-8 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">LSP UPN Veteran Jakarta</h1>
                </a>
                
                <div class="w-full px-4 overflow-x-hidden">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-auto text-center px-4">
                <p class="text-sm text-slate-400 dark:text-zinc-600">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} — Lembaga Sertifikasi Profesi.
                </p>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
