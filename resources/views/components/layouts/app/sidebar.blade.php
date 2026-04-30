<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 font-sans text-zinc-900 dark:text-zinc-100 antialiased"
    x-data="{ sidebarOpen: false }">
    @php
        $isAdmin = auth()->user()?->can('admin') ?? false;
        $homeRoute = route('dashboard');
    @endphp

    @include('components.layouts.app.sidebar._desktop-sidebar')
    @include('components.layouts.app.sidebar._mobile-header')
    @include('components.layouts.app.sidebar._mobile-sidebar')

    <div class="lg:pl-64">
        <main class="min-h-screen">
            {{ $slot }}
        </main>
    </div>

    <x-toast-notifications />

    @livewireScripts
</body>

</html>
