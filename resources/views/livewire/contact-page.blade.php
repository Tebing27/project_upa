    <div class="min-h-screen bg-white text-[#0f172a]">
        <x-public.navbar active="kontak" />

        <section
            class="relative flex min-h-[42vh] items-center justify-center overflow-hidden bg-slate-950 px-6 pb-20 pt-32">
            <img src="{{ asset('assets/background.webp') }}" alt="Kontak UPA LUK"
                class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.62),rgba(30,41,59,0.72))]"></div>

            <div class="relative z-10 mx-auto flex w-full max-w-340 justify-center text-center">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-white md:text-5xl">
                        <span class="text-slate-200 font-medium">UPA LUK</span>
                        <span class="mx-2 font-normal text-white/70">/</span>
                        Kontak
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-slate-300 md:text-base">Hubungi kami
                        untuk informasi layanan, pendaftaran,
                        dan dukungan terkait sertifikasi.</p>
                </div>
            </div>
        </section>

        <main class="px-6 pb-24 pt-16 lg:px-16 lg:pt-20">
            <div class="mx-auto max-w-340">
                <section class="grid gap-6 lg:grid-cols-3">
                    <article
                        class="rounded-md border border-slate-100 bg-white px-8 py-14 text-center shadow-[0_22px_50px_-38px_rgba(15,23,42,0.28)]">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center text-[#ff5b1f]">
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2"
                                class="h-20 w-20">
                                <path d="M10 20l14-7 15 8 14-6v40l-14 6-15-8-14 7V20Z" />
                                <path d="M24 13v40" />
                                <path d="M39 21v32" />
                                <circle cx="51" cy="39" r="7.5" />
                                <path d="m51 46.5 4.5 7" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-3xl font-semibold text-slate-950">Address</h2>
                        <p class="mt-4 text-[1.05rem] text-slate-800">Jalan RS. Fatmawati Raya</p>
                    </article>

                    <article
                        class="rounded-md border border-slate-100 bg-white px-8 py-14 text-center shadow-[0_22px_50px_-38px_rgba(15,23,42,0.28)]">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center text-[#ff5b1f]">
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2"
                                class="h-20 w-20">
                                <rect x="12" y="20" width="40" height="28" rx="4" />
                                <path d="m14 24 18 16 18-16" />
                                <path d="M22 15h20a4 4 0 0 1 4 4v1H18v-1a4 4 0 0 1 4-4Z" />
                                <path d="M24 27h16M24 33h16" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-3xl font-semibold text-slate-950">Email Address</h2>
                        <p class="mt-4 text-[1.05rem] text-slate-800">lsp@upnvj.ac.id</p>
                    </article>

                    <article
                        class="rounded-md border border-slate-100 bg-white px-8 py-14 text-center shadow-[0_22px_50px_-38px_rgba(15,23,42,0.28)]">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center text-[#ff5b1f]">
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2"
                                class="h-20 w-20">
                                <rect x="22" y="12" width="20" height="40" rx="4" />
                                <path d="M28 18h8" />
                                <circle cx="32" cy="45" r="2.5" />
                                <path d="M16 20c-2 2-3 4.5-3 7s1 5 3 7" />
                                <path d="M48 20c2 2 3 4.5 3 7s-1 5-3 7" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-3xl font-semibold text-slate-950">Phone Number</h2>
                        <p class="mt-4 text-[1.05rem] text-slate-800">+62 812-8028-0908</p>
                    </article>
                </section>

                <section
                    class="mt-20 overflow-hidden rounded-md border border-slate-200 bg-white shadow-[0_28px_70px_-45px_rgba(15,23,42,0.3)]">
                    <div class="google-map aspect-16/8 w-full">
                        <iframe
                            src="https://www.google.com/maps?q=Universitas%20Pembangunan%20Nasional%20Veteran%20Jakarta&z=15&output=embed"
                            class="h-full w-full" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </section>
            </div>
        </main>

        <x-public.footer />
    </div>
