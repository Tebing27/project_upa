<div>
    <x-public.navbar active="informasi" />

    <main class="container mx-auto flex-grow px-6 pt-32 pb-24 lg:px-16"
        x-data="faqPageData(@js($tabs), @js($faqs))">
        <div class="mx-auto w-full max-w-4xl">
            <div class="mb-12 text-center">
                @php
                    $highlightedPageTitle = preg_replace('/FAQ$/', '<span class="text-[#ea580c]">FAQ</span>', e($pageTitle)) ?? e($pageTitle);
                @endphp
                <h1 class="mb-4 text-4xl font-extrabold leading-tight tracking-tight text-gray-900 lg:text-5xl">
                    {!! $highlightedPageTitle !!}
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600">{{ $pageSubtitle }}</p>

                <div class="relative mx-auto mt-8 max-w-2xl">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery"
                        class="block w-full rounded-full border-gray-200 bg-white py-4 pr-4 pl-12 text-gray-800 shadow-md transition-shadow placeholder-gray-400 focus:border-[#ea580c] focus:bg-white focus:ring-2 focus:ring-[#ea580c]"
                        placeholder="{{ $searchPlaceholder }}">
                </div>
            </div>

            <div class="mb-8" x-show="searchQuery.trim() === ''">
                <p class="mb-2 text-center text-xs text-gray-500 italic md:hidden">Geser untuk melihat kategori lain</p>

                <div class="hide-scrollbar flex justify-start gap-2 overflow-x-auto border-b border-gray-200 px-2 pb-px whitespace-nowrap md:justify-center md:gap-8 md:px-0">
                    <template x-for="tab in tabs" :key="tab">
                        <button @click="activeTab = tab"
                            :class="{
                                'border-[#ea580c] text-[#ea580c] font-bold': activeTab === tab,
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== tab
                            }"
                            class="border-b-2 px-3 py-3 text-sm transition-colors duration-200 focus:outline-none md:px-1 md:text-base"
                            x-text="tab">
                        </button>
                    </template>
                </div>
            </div>

            <div x-show="searchQuery.trim() !== ''" style="display: none;"
                class="mb-8 text-center text-gray-600">
                <div class="rounded-lg border border-orange-100 bg-orange-50 py-3">
                    <p>Menampilkan hasil pencarian untuk:
                        <span class="font-bold text-[#ea580c]" x-text="`&quot;${searchQuery}&quot;`"></span>
                    </p>
                </div>
            </div>

            <div class="mb-10 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm divide-y divide-gray-100">
                <template x-for="(faq, index) in filteredFaqs" :key="`${faq.question}-${index}`">
                    <div x-data="{ expanded: false }" class="transition-colors hover:bg-gray-50">
                        <button @click="expanded = !expanded"
                            class="flex w-full items-center justify-between p-6 text-left transition-colors focus:bg-gray-50 focus:outline-none">
                            <div class="flex items-start gap-4 text-left">
                                <div class="mt-1 shrink-0">
                                    <svg x-show="!expanded" class="h-5 w-5 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <svg x-show="expanded" style="display:none;" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold leading-snug text-gray-900 md:text-lg" x-text="faq.question"></h3>
                                    <span x-show="searchQuery.trim() !== ''"
                                        class="mt-2 inline-block rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold tracking-wider text-[#2563eb] uppercase"
                                        x-text="faq.category"></span>
                                </div>
                            </div>
                        </button>

                        <div x-show="expanded" x-collapse style="display: none;"
                            class="px-6 pt-0 pb-6 pl-[3.25rem] text-sm leading-relaxed text-gray-600 sm:px-8 md:text-base">
                            <p x-text="faq.answer"></p>
                        </div>
                    </div>
                </template>

                <div x-show="filteredFaqs.length === 0" style="display: none;" class="p-12 text-center text-gray-500">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium">Maaf, kami tidak menemukan pertanyaan yang relevan.</p>
                    <p class="mt-1 text-sm">Coba gunakan kata kunci lain, misalnya "sertifikat" atau "ujian".</p>
                </div>
            </div>

            <div class="rounded-2xl bg-[#1f2937] p-8 text-center text-white shadow-xl shadow-gray-900/10 sm:flex sm:items-center sm:justify-between">
                <div class="mb-6 sm:mb-0 sm:text-left">
                    <h3 class="mb-2 text-xl font-extrabold tracking-tight">{{ $helpTitle }}</h3>
                    <p class="text-gray-400">{{ $helpText }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('kontak') }}"
                        class="inline-flex w-full items-center justify-center rounded-full border border-transparent bg-blue-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700 sm:w-auto">
                        {{ $helpButtonText }}
                    </a>
                </div>
            </div>
        </div>
    </main>

    <x-public.footer />

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('faqPageData', (tabs, faqs) => ({
                searchQuery: '',
                activeTab: 'Semua',
                tabs,
                faqs,
                get filteredFaqs() {
                    if (this.searchQuery.trim() === '') {
                        if (this.activeTab === 'Semua') {
                            return this.faqs;
                        }

                        return this.faqs.filter((faq) => faq.category === this.activeTab);
                    }

                    const query = this.searchQuery.toLowerCase();

                    return this.faqs.filter((faq) =>
                        faq.question.toLowerCase().includes(query) ||
                        faq.answer.toLowerCase().includes(query)
                    );
                },
            }));
        });
    </script>
</div>
