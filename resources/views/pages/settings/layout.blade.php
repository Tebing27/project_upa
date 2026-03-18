<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="flex flex-col space-y-1" aria-label="{{ __('Settings') }}">
            @php
                $navItems = [
                    ['route' => 'profile.edit', 'label' => __('Profile')],
                    ['route' => 'security.edit', 'label' => __('Security')],
                    ['route' => 'appearance.edit', 'label' => __('Appearance')],
                ];
            @endphp

            @foreach($navItems as $item)
                <a 
                    href="{{ route($item['route']) }}" 
                    wire:navigate 
                    @class([
                        'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                        'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' => request()->routeIs($item['route']),
                        'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800' => !request()->routeIs($item['route']),
                    ])
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="md:hidden w-full border-b border-zinc-200 dark:border-zinc-800 mb-6"></div>

    <div class="flex-1 self-stretch max-md:pt-6">
        @if(isset($heading))
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $heading }}</h2>
        @endif
        @if(isset($subheading))
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
