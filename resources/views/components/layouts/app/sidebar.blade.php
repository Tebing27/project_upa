<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 font-sans text-zinc-900 dark:text-zinc-100 antialiased"
    x-data="{ sidebarOpen: false }">
    @php
        $isAdmin = auth()->user()?->can('admin') ?? false;
        
        $dashboardRouteName = 'student.dashboard';
        if (auth()->check()) {
            if (auth()->user()->role === 'admin_lsp') {
                $dashboardRouteName = 'admin.dashboard';
            } elseif (auth()->user()->role === 'asesor') {
                $dashboardRouteName = 'asesor.dashboard';
            }
        }
        $homeRoute = route($dashboardRouteName);
    @endphp

    <div
        class="fixed inset-y-0 left-0 z-50 hidden w-64 flex-col border-r border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 lg:flex">
        <div class="flex h-14 items-center border-b border-zinc-200 px-4 dark:border-zinc-800">
            <x-app-logo :sidebar="true" href="{{ $homeRoute }}" wire:navigate />
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-6">
            @unless ($isAdmin)
                <div>
                    <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Platform') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route($dashboardRouteName) }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                $dashboardRouteName),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                $dashboardRouteName),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('dashboard.status') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'dashboard.status'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'dashboard.status'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Status Pendaftaran') }}
                        </a>
                        <a href="{{ route('dashboard.certificates') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'dashboard.certificates'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'dashboard.certificates'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            {{ __('Sertifikat Saya') }}
                        </a>
                    </div>
                </div>
            @endunless

            @can('admin')
                <div>
                    <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Admin Panel') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route($dashboardRouteName) }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                $dashboardRouteName),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                $dashboardRouteName),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            {{ __('Dashboard Admin') }}
                        </a>
                        <a href="{{ route('admin.schemes') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.schemes'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.schemes'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ __('Manajemen Skema') }}
                        </a>
                        <a href="{{ route('admin.verifikasi') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.verifikasi*'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.verifikasi*'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Verifikasi Dokumen') }}
                        </a>
                        <a href="{{ route('admin.jadwal') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.jadwal'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.jadwal'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ __('Jadwal Uji') }}
                        </a>
                        <a href="{{ route('admin.hasil-uji') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.hasil-uji'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.hasil-uji'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            {{ __('Upload Hasil Uji') }}
                        </a>
                    </div>
                </div>
            @endcan
        </nav>

        <div class="mt-auto border-t border-zinc-200 p-4 dark:border-zinc-800">
            <x-desktop-user-menu />
        </div>
    </div>

    <div
        class="flex h-14 items-center justify-between border-b border-zinc-200 bg-white px-4 dark:border-zinc-800 dark:bg-zinc-900 lg:hidden">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true"
                class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <x-app-logo href="{{ $homeRoute }}" wire:navigate />
        </div>

        <x-desktop-user-menu />
    </div>

    <div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        @click="sidebarOpen = false" x-transition.opacity></div>

    <div x-show="sidebarOpen" style="display: none;"
        class="fixed inset-y-0 left-0 z-50 w-64 transform flex-col bg-white dark:bg-zinc-900 lg:hidden"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
        <div class="flex h-14 items-center justify-between border-b border-zinc-200 px-4 dark:border-zinc-800">
            <x-app-logo href="{{ $homeRoute }}" wire:navigate />
            <button @click="sidebarOpen = false"
                class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-6">
            @unless ($isAdmin)
                <div>
                    <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Platform') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route($dashboardRouteName) }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                $dashboardRouteName),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                $dashboardRouteName),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('dashboard.status') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'dashboard.status'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'dashboard.status'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Status Pendaftaran') }}
                        </a>
                        <a href="{{ route('dashboard.certificates') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'dashboard.certificates'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'dashboard.certificates'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            {{ __('Sertifikat Saya') }}
                        </a>
                    </div>
                </div>
            @endunless

            @can('admin')
                <div>
                    <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        {{ __('Admin Panel') }}</div>
                    <div class="space-y-1">
                        <a href="{{ route($dashboardRouteName) }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                $dashboardRouteName),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                $dashboardRouteName),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            {{ __('Dashboard Admin') }}
                        </a>
                        <a href="{{ route('admin.schemes') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.schemes'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.schemes'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ __('Manajemen Skema') }}
                        </a>
                        <a href="{{ route('admin.verifikasi') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.verifikasi*'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.verifikasi*'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Verifikasi Dokumen') }}
                        </a>
                        <a href="{{ route('admin.jadwal') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.jadwal'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.jadwal'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ __('Jadwal Uji') }}
                        </a>
                        <a href="{{ route('admin.hasil-uji') }}" wire:navigate @class([
                            'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => request()->routeIs(
                                'admin.hasil-uji'),
                            'text-zinc-600 hover:bg-emerald-50/50 hover:text-emerald-600 dark:text-zinc-400 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300' => !request()->routeIs(
                                'admin.hasil-uji'),
                        ])>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            {{ __('Upload Hasil Uji') }}
                        </a>
                    </div>
                </div>
            @endcan
        </nav>
    </div>

    <div class="lg:pl-64">
        <main class="min-h-screen">
            {{ $slot }}
        </main>
    </div>

</body>

</html>
