        <div x-show="mobileMenuOpen" class="fixed inset-0 z-100 lg:hidden" style="display: none;">

            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-500"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in-out duration-400" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white"> </div>

            <div x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-400 cubic-bezier(0.7, 0, 0.84, 0)"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 flex w-full flex-col bg-white overflow-y-auto">

                <div class="px-8 pt-10 pb-4 flex justify-start">
                    <button @click="mobileMenuOpen = false"
                        class="bg-[#15803d] text-white rounded-full p-2 h-10 w-10 flex items-center justify-center hover:bg-green-800 transition-transform active:scale-95">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col px-8">
                    @foreach ($navLinks as $key => $link)
                        @if (isset($link['children']))
                            <div x-data="{ subOpen: false }" class="flex flex-col">
                                <div class="flex items-center justify-between border-b border-gray-200 py-4">
                                    @if (isset($link['route']) || isset($link['url']))
                                        <a href="{{ $resolveLink($link) }}"
                                            class="text-base font-semibold text-gray-900">{{ $link['label'] }}</a>
                                    @else
                                        <span
                                            class="text-base font-semibold text-gray-900">{{ $link['label'] }}</span>
                                    @endif

                                    <button @click.prevent="subOpen = !subOpen"
                                        class="flex h-8 w-8 items-center justify-center rounded bg-[#15803d] text-lg font-medium text-white">
                                        <span x-show="!subOpen">+</span>
                                        <span x-show="subOpen" style="display: none;">-</span>
                                    </button>
                                </div>

                                <div x-show="subOpen" class="flex flex-col pl-4 pb-2 pt-2 gap-3"
                                    style="display:none;" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0">
                                    @foreach ($link['children'] as $child)
                                        <a href="{{ $resolveLink($child) }}"
                                            class="border-b border-gray-100 pb-2 text-sm font-medium text-gray-600 hover:text-[#15803d]">{{ $child['label'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $resolveLink($link) }}"
                                class="border-b border-gray-200 py-4 text-base font-semibold text-gray-900">
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @endforeach

                    <div class="border-b border-gray-200 py-4">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English"
                            class="h-4 w-6 object-cover shadow-sm">
                    </div>
                    <div class="border-b border-gray-200 py-4">
                        <img src="https://flagcdn.com/w20/id.png" alt="Indonesia"
                            class="h-4 w-6 object-cover shadow-sm">
                    </div>
                </div>

                <div class="px-8 py-8 mt-4 mb-8 flex flex-col gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full rounded-full bg-[#15803d] px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-[#166534]">
                            KE DASHBOARD
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="w-full rounded-full bg-[#15803d] px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-[#166534]">
                                Daftar
                            </a>
                        @endif
                        <a href="{{ route('login') }}"
                            class="w-full rounded-full border-2 border-[#15803d] px-6 py-3 text-center font-bold text-[#15803d] transition-all hover:bg-[#15803d] hover:text-white">
                            Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </div>
