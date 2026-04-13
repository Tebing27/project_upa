<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    {{ $slot }}

    @livewireScripts
    <script>
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('scroll'));
        });
    </script>
</body>

</html>
