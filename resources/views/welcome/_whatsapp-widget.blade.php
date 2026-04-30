    <div x-data="{ open: false, message: '', now: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }"
        class="fixed bottom-4 right-4 sm:bottom-8 sm:right-8 z-50 flex flex-col items-end font-sans drop-shadow-2xl">

        <div x-show="open" x-transition:enter="transition-all ease-out duration-300 origin-bottom-right"
            x-transition:enter-start="opacity-0 translate-y-10 scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition-all ease-in duration-200 origin-bottom-right"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-50"
            class="mb-4 w-[calc(100vw-2rem)] sm:w-[22rem] max-w-[24rem] overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl"
            style="display: none;">

            <div
                class="flex items-center justify-between bg-gradient-to-r from-[#075e54] to-[#128c7e] px-4 py-3.5 text-white">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/20 p-1">
                            <img src="{{ asset('assets/logo.webp') }}" alt="Admin"
                                class="h-full w-full rounded-full object-cover bg-white"
                                onerror="this.src='https://ui-avatars.com/api/?name=LSP&background=fff&color=128c7e'">
                        </div>
                        <span
                            class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-[#128c7e] bg-green-400"></span>
                    </div>
                    <div>
                        <p class="text-[0.95rem] font-bold leading-tight text-white line-clamp-1">Admin UPA-LUK</p>
                        <p class="text-xs text-green-100 line-clamp-1">Biasanya membalas seketika</p>
                    </div>
                </div>
            </div>

            <div class="min-h-[14rem] sm:min-h-[16rem] bg-[#efeae2] px-4 py-5 relative flex flex-col justify-end"
                style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                <div x-show="open" x-transition:enter="transition-all ease-out duration-500 delay-200"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="relative max-w-[85%] rounded-2xl rounded-tl-none bg-white px-4 py-2.5 text-slate-800 shadow-sm border border-gray-100/50 mb-auto">
                    <div class="absolute -left-2 top-0 text-white">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="currentColor">
                            <path d="M12 0H0L12 15V0Z" />
                        </svg>
                    </div>
                    <p class="text-sm sm:text-[0.95rem] leading-snug">Halo! Ada yang bisa kami bantu terkait layanan
                        sertifikasi di UPN Veteran Jakarta? 😊</p>
                    <p class="mt-1 text-right text-[0.65rem] font-medium text-slate-400" x-text="now"></p>
                </div>
            </div>

            <div class="bg-gray-50 px-3 py-3 border-t border-gray-200">
                <form action="https://web.whatsapp.com/send" method="GET" target="_blank"
                    class="flex items-center gap-2"
                    @submit="if (!message.trim()) { message = 'Halo, saya ingin bertanya tentang sertifikasi.' }">
                    <input type="hidden" name="phone" value="{{ $homeContent[8][1] ?? '6287784644193' }}">

                    <div
                        class="flex min-w-0 flex-1 items-center gap-2 rounded-full border border-gray-200 bg-white px-3 sm:px-4 py-2 sm:py-2.5 shadow-sm focus-within:border-[#128c7e] focus-within:ring-1 focus-within:ring-[#128c7e] transition-all">
                        <input x-model="message" name="text" type="text" placeholder="Ketik pesan..."
                            class="min-w-0 flex-1 border-0 bg-transparent p-0 text-base sm:text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:ring-0">
                    </div>

                    <button type="submit"
                        class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-full bg-[#128c7e] text-white shadow-md transition hover:scale-105 hover:bg-[#075e54]"
                        aria-label="Kirim pesan">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 translate-x-[2px]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="relative z-50">
            <div x-show="!open" class="absolute -top-1 -right-1 z-10 flex h-4 w-4">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 border-2 border-white bg-red-500"></span>
            </div>

            <button type="button" @click="open = !open"
                class="group relative flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full text-white shadow-[0_4px_20px_rgba(37,211,102,0.4)] transition-all duration-300 hover:scale-110"
                :class="open ? 'bg-slate-800 hover:bg-slate-700' : 'bg-[#25D366] hover:bg-[#20bd5a]'"
                aria-label="Toggle WhatsApp">

                <svg x-show="!open" x-transition:enter="transition-all duration-300 ease-out"
                    x-transition:enter-start="opacity-0 rotate-45 scale-50"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 -rotate-45 scale-50" class="absolute h-7 w-7 sm:h-8 sm:w-8"
                    viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51h-.57c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>

                <svg x-show="open" x-transition:enter="transition-all duration-300 ease-out delay-100"
                    x-transition:enter-start="opacity-0 -rotate-45 scale-50"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 rotate-45 scale-50" class="absolute h-6 w-6 sm:h-7 sm:w-7"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                    style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

