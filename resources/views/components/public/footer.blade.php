<footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8 text-white relative z-20">
    <div class="max-w-[85rem] mx-auto w-full px-6 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            
            <!-- Col 1: LSP Info -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <img src="{{ asset('assets/logo.webp') }}" alt="Logo LSP UPNVJ" class="h-20 w-auto object-contain self-start bg-white p-2 rounded-xl shadow-lg">
                <p class="text-slate-300 text-sm leading-relaxed max-w-sm mt-1">
                    Lembaga Sertifikasi Profesi UPN "Veteran" Jakarta berdedikasi menciptakan lulusan kompeten dengan standar lisensi BNSP Nasional yang disinergikan dengan kebutuhan industri.
                </p>
                <div class="flex flex-col gap-4 mt-2 text-sm text-slate-300">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 text-[#ea580c]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="mt-1.5 leading-snug">Jl. RS. Fatmawati, Pondok Labu, Jakarta Selatan, 12450</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 text-[#ea580c]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span>lsp@upnvj.ac.id</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 text-[#ea580c]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <span>+62 812-8028-0908</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Tautan Cepat -->
            <div class="lg:col-span-2">
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Tautan Cepat
                    <span class="absolute -bottom-2.5 left-0 w-8 h-1 bg-[#ea580c] rounded-full"></span>
                </h3>
                <ul class="flex flex-col gap-4 text-sm text-slate-300">
                    <li><a href="{{ route('home') }}" class="hover:text-white hover:translate-x-1 transition-all flex items-center gap-2 group"><svg class="w-4 h-4 text-slate-600 group-hover:text-[#ea580c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Home</a></li>
                    <li><a href="{{ route('profil') }}" class="hover:text-white hover:translate-x-1 transition-all flex items-center gap-2 group"><svg class="w-4 h-4 text-slate-600 group-hover:text-[#ea580c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Profil LSP</a></li>
                    <li><a href="{{ route('skema.index') }}" class="hover:text-white hover:translate-x-1 transition-all flex items-center gap-2 group"><svg class="w-4 h-4 text-slate-600 group-hover:text-[#ea580c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Skema Sertifikasi</a></li>
                    <li><a href="{{ route('cek-sertifikat') }}" class="hover:text-white hover:translate-x-1 transition-all flex items-center gap-2 group"><svg class="w-4 h-4 text-slate-600 group-hover:text-[#ea580c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> Validasi Sertifikat</a></li>
                </ul>
            </div>

            <!-- Col 3: Maps -->
            <div class="lg:col-span-4">
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Lokasi Kami
                    <span class="absolute -bottom-2.5 left-0 w-8 h-1 bg-[#ea580c] rounded-full"></span>
                </h3>
                <div class="w-full h-48 rounded-xl overflow-hidden border border-slate-700 shadow-lg">
                    <iframe
                        src="https://www.google.com/maps?q=Universitas%20Pembangunan%20Nasional%20Veteran%20Jakarta&z=15&output=embed"
                        class="h-full w-full" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Col 4: Sosial Media -->
            <div class="lg:col-span-2">
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Sosial Media
                    <span class="absolute -bottom-2.5 left-0 w-8 h-1 bg-[#ea580c] rounded-full"></span>
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="https://www.instagram.com/lspupnvj/" target="_blank" aria-label="Instagram" class="w-10 h-10 rounded-full bg-slate-800 hover:bg-[#ea580c] flex items-center justify-center transition-all hover:scale-110 text-white shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.88z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/school/universitas-pembangunan-nasional-veteran-jakarta/" target="_blank" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-slate-800 hover:bg-[#ea580c] flex items-center justify-center transition-all hover:scale-110 text-white shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@UPNVeteranJakarta" target="_blank" aria-label="YouTube" class="w-10 h-10 rounded-full bg-slate-800 hover:bg-[#ea580c] flex items-center justify-center transition-all hover:scale-110 text-white shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 mt-4 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} UPA LUK UPN Veteran Jakarta. All rights reserved.</p>
            <div class="flex gap-6 font-medium">
                <a href="#" class="hover:text-[#ea580c] transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-[#ea580c] transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
