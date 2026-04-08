<div>
    <x-public.navbar active="profil" />

    <!-- Hero Section -->
    <div class="relative min-h-[50vh] flex items-center justify-center pt-32 pb-20 overflow-hidden bg-gray-900">
        <img src="{{ asset('assets/background.webp') }}" alt="Hero Background"
            class="absolute inset-0 w-full h-full object-cover opacity-55">
        <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.62),rgba(30,41,59,0.72))]"></div>

        <div class="relative z-10 text-center px-6">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-wide">
                <span class="text-gray-300 font-medium">UPA LUK</span> <span class="mx-2 font-normal">/</span> Profil
            </h1>
        </div>
    </div>

    <!-- Deskripsi Lembaga Section -->
    <div class="py-24 px-6 lg:px-16 bg-white">
        <div class="max-w-340 mx-auto w-full text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Unit Penunjang Akademik - Lembaga Uji
                Kompetensi</h2>
            <div class="w-12 h-1 bg-[#1e40af] mx-auto mb-16"></div>

            <div
                class="bg-[#f8f9fa] relative p-8 md:p-12 text-left rounded-xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] mx-auto max-w-5xl">
                <!-- Short blue line on the left -->
                <div class="absolute left-0 top-10 h-12 w-0.75 bg-[#1e40af]"></div>

                <p class="text-gray-600 leading-relaxed text-[1.1rem] md:text-lg font-medium pl-1">
                    Lembaga Sertifikasi Profesi (LSP) adalah lembaga pelaksanaan kegiatan sertifikasi profesi yang
                    memperoleh lisensi dari Badan Nasional Sertifikasi Profesi (BNSP). Lisensi diberikan melalui proses
                    akreditasi oleh BNSP yang menyatakan bahwa LSP bersangkutan telah memenuhi syarat untuk melakukan
                    kegiatan sertifikasi profesi. Sebagai organisasi tingkat nasional yang berkedudukan di wilayah
                    Republik Indonesia, LSP dapat membuka cabang yang berkedudukan di kota lain / suatu tempat.
                </p>
            </div>
        </div>
    </div>

    <!-- Visi Misi Tab Section -->
    <div class="py-16 px-6 lg:px-16 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto w-full">
            <!-- Tabs Header -->
            <div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-12">
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
                        class="flex flex-col items-center justify-center w-32 h-32 md:w-40 md:h-40 rounded-lg transition-all duration-300 transform {{ $activeTab === $key ? 'bg-[#f97316] shadow-lg scale-105' : 'bg-[#fdba74] hover:bg-[#fb923c] opacity-90' }}">
                        <svg class="w-10 h-10 md:w-12 md:h-12 text-white mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="{{ $tab['icon'] }}"></path>
                        </svg>
                        <span class="text-white font-bold text-lg md:text-xl">{{ $tab['title'] }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Tabs Content -->
            <div
                class="bg-white rounded-xl p-8 md:p-14 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-50 min-h-75 text-left mx-auto max-w-5xl">
                @if ($activeTab === 'visi')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="text-2xl md:text-[1.75rem] font-bold text-gray-900 mb-8">Visi UPA LUK – UPNVJ</h3>

                        <div class="bg-[#f8f9fa] rounded-lg p-8 md:p-10 relative">
                            <!-- Short blue line -->
                            <div class="absolute left-0 top-10 h-10 w-0.75 bg-[#1e40af]"></div>

                            <!-- Outline Quote Icon -->
                            <svg class="w-10 h-10 text-blue-500 mb-5 ml-1" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 11h-4a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h2a3 3 0 0 1 3 3v5l-2 4"></path>
                                <path d="M20 11h-4a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h2a3 3 0 0 1 3 3v5l-2 4"></path>
                            </svg>

                            <p class="text-gray-600 italic text-[1.1rem] md:text-lg leading-relaxed pl-1 font-medium">
                                Becoming an Independent and Reliable Professional Certification Organization with State
                                Defense Identity
                            </p>
                        </div>
                    </div>
                @elseif($activeTab === 'misi')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="text-2xl md:text-[1.75rem] font-bold text-gray-900 mb-8">UPA LUK Mission – UPNVJ</h3>
                        <ol
                            class="list-decimal list-inside space-y-3.5 text-gray-800 text-[1.05rem] md:text-base leading-relaxed">
                            <li>Organizing professional human resource certification</li>
                            <li>Developing a trusted state defense-identified study program competency certification
                                scheme</li>
                            <li>Increase the recognition and competitiveness of graduates with national defense identity
                                at home and abroad.</li>
                            <li>Build synergistic cooperation with stakeholders</li>
                        </ol>
                    </div>
                @elseif($activeTab === 'tugas')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="text-2xl md:text-[1.75rem] font-bold text-gray-900 mb-8">Tugas</h3>
                        <ol
                            class="list-decimal list-inside space-y-3.5 text-gray-800 text-[1.05rem] md:text-base leading-relaxed">
                            <li>Creating competency test materials.</li>
                            <li>Provide testing personnel (assessors).</li>
                            <li>Conduct an assessment.</li>
                            <li>Develop qualifications with reference to KKNI.</li>
                            <li>Maintain assessor and TUK performance.</li>
                            <li>Creating competency test materials.</li>
                            <li>Development of certification schemes</li>
                        </ol>
                    </div>
                @elseif($activeTab === 'wewenang')
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
                        x-transition.opacity.duration.500ms>
                        <h3 class="text-2xl md:text-[1.75rem] font-bold text-gray-900 mb-8">Wewenang</h3>
                        <ol
                            class="list-decimal list-inside space-y-3.5 text-gray-800 text-[1.05rem] md:text-base leading-relaxed">
                            <li>Setting competency fees.</li>
                            <li>Issue a certificate of competency.</li>
                            <li>Revoke/cancel competency certification.</li>
                            <li>Establish and verify the TUK.</li>
                            <li>Give sanctions to assessors and TUK if they violate the rules.</li>
                            <li>Propose new competency standards.</li>
                        </ol>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Struktur Organisasi Section -->
    <div class="py-24 px-6 lg:px-16 bg-white border-t border-gray-100">
        <div class="max-w-340 mx-auto w-full text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Struktur Organisasi</h2>
            <div class="w-16 h-1 bg-[#1e40af] mx-auto mb-20 rounded-full"></div>

            <div class="flex flex-col items-center gap-24">
                @foreach ($staff as $person)
                    <div class="flex flex-col items-center">
                        <div class="relative mb-6 group cursor-pointer">
                            <img src="{{ $person['image'] }}" alt="{{ $person['name'] }}"
                                class="w-64 h-80 object-cover rounded-t-3xl rounded-b-xl shadow-md transition-transform duration-500 group-hover:scale-[1.02]">

                            <!-- Overlapping Icon -->
                            <div
                                class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full shadow-lg flex items-center justify-center border-4 border-gray-50 z-10 group-hover:-translate-y-2 transition-transform duration-300">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-10">
                            @if ($person['prefix'])
                                <p class="text-gray-500 text-sm font-medium mb-1 uppercase tracking-wider">
                                    {{ $person['prefix'] }}</p>
                            @endif
                            <h3 class="text-2xl font-bold text-[#1e40af] mb-2">{{ $person['name'] }}</h3>
                            <p class="text-gray-600 font-medium">{{ $person['title'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-public.footer />

    <!-- Scroll to Top -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-8 right-8 bg-[#1e40af] text-white p-3.5 rounded-full shadow-2xl hover:bg-blue-800 transition z-50 hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
</div>
