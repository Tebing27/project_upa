<div>
    <x-public.navbar active="profil" />

    <div class="relative flex min-h-[50vh] items-center justify-center overflow-hidden bg-gray-900 pt-32 pb-20">
        <img src="{{ asset('assets/background.webp') }}" alt="Hero Background"
            class="absolute inset-0 h-full w-full object-cover opacity-55">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.62),rgba(30,41,59,0.72))]"></div>

        <div class="relative z-10 px-6 text-center">
            <h1 class="text-3xl font-bold tracking-wide text-white md:text-5xl">
                <span class="font-medium text-gray-300">UPA LUK</span> <span class="mx-2 font-normal">/</span> Profil
            </h1>
        </div>
    </div>

    <div id="tentang-kami" class="scroll-mt-28 bg-white px-6 py-24 lg:px-16">
        <div class="mx-auto w-full max-w-340 text-center">
            <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">{{ $profileHeading }}</h2>
            <div class="mx-auto mb-16 h-1 w-12 bg-[#1e40af]"></div>

            <div class="relative mx-auto max-w-5xl rounded-xl bg-[#f8f9fa] p-8 text-left shadow-[0_4px_20px_rgb(0,0,0,0.03)] md:p-12">
                <div class="absolute top-10 left-0 h-12 w-0.75 bg-[#1e40af]"></div>

                <div class="prose prose-slate max-w-none pl-1 text-[1.1rem] font-medium leading-relaxed text-gray-600 md:text-lg">
                    {!! $profileText !!}
                </div>
            </div>
        </div>
    </div>

    <div id="visi-misi" class="scroll-mt-28 border-t border-gray-100 bg-white px-6 py-16 lg:px-16">
        <div class="mx-auto w-full max-w-6xl">
            <div class="mb-12 flex flex-wrap justify-center gap-4 md:gap-8">
                @php
                    $tabs = [
                        'visi' => [
                            'title' => 'Visi',
                            'icon' =>
                                'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                        ],
                        'misi' => [
                            'title' => 'Misi',
                            'icon' =>
                                'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222',
                        ],
                        'tugas' => [
                            'title' => 'Tugas',
                            'icon' =>
                                'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                        ],
                        'wewenang' => [
                            'title' => 'Wewenang',
                            'icon' =>
                                'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                        ],
                    ];
                @endphp

                @foreach ($tabs as $key => $tab)
                    <button wire:click="setTab('{{ $key }}')"
                        class="flex h-32 w-32 transform flex-col items-center justify-center rounded-lg transition-all duration-300 md:h-40 md:w-40 {{ $activeTab === $key ? 'scale-105 bg-[#f97316] shadow-lg' : 'bg-[#fdba74] opacity-90 hover:bg-[#fb923c]' }}">
                        <svg class="mb-3 h-10 w-10 text-white md:h-12 md:w-12" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="{{ $tab['icon'] }}"></path>
                        </svg>
                        <span class="text-lg font-bold text-white md:text-xl">{{ $tab['title'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mx-auto min-h-75 max-w-5xl rounded-xl border border-gray-50 bg-white p-8 text-left shadow-[0_4px_20px_rgb(0,0,0,0.03)] md:p-14">
                @if ($activeTab === 'visi')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="mb-8 text-2xl font-bold text-gray-900 md:text-[1.75rem]">{{ $tabContent['visi']['title'] }}</h3>

                        <div class="relative rounded-lg bg-[#f8f9fa] p-8 md:p-10">
                            <div class="absolute top-10 left-0 h-10 w-0.75 bg-[#1e40af]"></div>

                            <svg class="mb-5 ml-1 h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 11h-4a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h2a3 3 0 0 1 3 3v5l-2 4"></path>
                                <path d="M20 11h-4a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h2a3 3 0 0 1 3 3v5l-2 4"></path>
                            </svg>

                            <p class="pl-1 text-[1.1rem] font-medium leading-relaxed text-gray-600 italic md:text-lg">
                                {{ $tabContent['visi']['quote'] }}
                            </p>
                        </div>
                    </div>
                @elseif($activeTab === 'misi')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="mb-8 text-2xl font-bold text-gray-900 md:text-[1.75rem]">{{ $tabContent['misi']['title'] }}</h3>
                        <ol class="list-decimal list-inside space-y-3.5 text-[1.05rem] leading-relaxed text-gray-800 md:text-base">
                            @foreach ($tabContent['misi']['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    </div>
                @elseif($activeTab === 'tugas')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="mb-8 text-2xl font-bold text-gray-900 md:text-[1.75rem]">{{ $tabContent['tugas']['title'] }}</h3>
                        <ol class="list-decimal list-inside space-y-3.5 text-[1.05rem] leading-relaxed text-gray-800 md:text-base">
                            @foreach ($tabContent['tugas']['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    </div>
                @elseif($activeTab === 'wewenang')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="mb-8 text-2xl font-bold text-gray-900 md:text-[1.75rem]">{{ $tabContent['wewenang']['title'] }}</h3>
                        <ol class="list-decimal list-inside space-y-3.5 text-[1.05rem] leading-relaxed text-gray-800 md:text-base">
                            @foreach ($tabContent['wewenang']['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="struktur-organisasi" class="scroll-mt-28 border-t border-gray-100 bg-white px-6 py-24 lg:px-16"
        x-data="{ modalOpen: false, modalImage: '' }">
        <div class="mx-auto w-full max-w-6xl text-center">
            <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">Struktur Organisasi</h2>
            <div class="mx-auto mb-20 h-1 w-16 rounded-full bg-[#1e40af]"></div>

            <div class="flex flex-col items-center gap-16">
                @foreach ($staff as $person)
                    <div class="flex flex-col items-center">
                        <div class="group relative mb-6 cursor-pointer">
                            <img src="{{ $person['image'] }}" alt="{{ $person['name'] }}"
                                class="h-80 w-64 rounded-t-3xl rounded-b-xl object-cover shadow-md transition-transform duration-500 group-hover:scale-[1.02]">

                            <div
                                class="absolute -bottom-8 left-1/2 flex h-16 w-16 -translate-x-1/2 items-center justify-center rounded-full border-4 border-gray-50 bg-white shadow-lg transition-transform duration-300 group-hover:-translate-y-2">
                                <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-10">
                            @if ($person['prefix'])
                                <p class="mb-1 text-sm font-medium tracking-wider text-gray-500 uppercase">
                                    {{ $person['prefix'] }}
                                </p>
                            @endif
                            <h3 class="mb-2 text-2xl font-bold text-[#1e40af]">{{ $person['name'] }}</h3>
                            <p class="font-medium text-gray-600">{{ $person['title'] }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4 w-full">
                    <h3 class="mb-8 text-xl font-bold text-gray-800">{{ $structureHeading }}</h3>
                    <img @click="modalOpen = true; modalImage = '{{ $structureImage }}'" src="{{ $structureImage }}"
                        alt="Struktur Organisasi UPA LUK"
                        class="mx-auto w-full max-w-5xl cursor-pointer rounded-xl border border-gray-100 bg-gray-50 object-contain shadow-sm transition-all duration-300 hover:scale-[1.01] hover:shadow-lg">
                    <p class="mt-3 text-sm text-gray-400 italic">*Klik gambar untuk memperbesar</p>
                </div>
            </div>
        </div>

        <div x-show="modalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-auto bg-black/90 p-4 backdrop-blur-sm sm:p-8"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"
            @keydown.escape.window="modalOpen = false">
            <div class="absolute inset-0 cursor-pointer" @click="modalOpen = false"></div>

            <div class="relative mx-auto flex w-full max-w-7xl flex-col items-center justify-center pointer-events-none"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <button @click="modalOpen = false"
                    class="pointer-events-auto absolute -top-12 right-0 rounded-full bg-black/40 p-2 text-white transition hover:bg-black/80 hover:text-gray-300 md:-top-16">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <img :src="modalImage" alt="Zoom Struktur Organisasi"
                    class="pointer-events-auto mx-auto max-h-[85vh] max-w-full rounded-lg border border-gray-700 bg-white shadow-2xl">
            </div>
        </div>
    </div>

    <x-public.footer />
</div>
