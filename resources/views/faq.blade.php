<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ & Pusat Bantuan - LSP UPNVJ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <x-public.navbar active="informasi" />

    <!-- Alpine State Wrapper -->
    <main class="flex-grow pt-32 pb-24 px-6 lg:px-16 container mx-auto"
          x-data="{
              searchQuery: '',
              activeTab: 'Semua',
              tabs: ['Semua', 'Pendaftaran', 'Asesmen', 'Sertifikat', 'Lainnya'],
              faqs: [
                  { category: 'Pendaftaran', question: 'Bagaimana cara mendaftar uji kompetensi di UPA LUK?', answer: 'Pendaftaran dilakukan secara online melalui portal ini. Anda perlu membuat akun, memverifikasi alamat email, melengkapi biodata profil, lalu memilih skema sertifikasi yang tersedia di halaman Dasbor Anda.' },
                  { category: 'Pendaftaran', question: 'Apa saja dokumen persyaratan yang dibutuhkan?', answer: 'Secara umum, Anda memerlukan KTP, Ijazah terakhir/Transkrip Nilai, Pas Foto formal berlatar merah/biru, dan portofolio bukti kompetensi (sertifikat pelatihan, surat keterangan magang, dsb) sesuai skema yang dipilih.' },
                  { category: 'Pendaftaran', question: 'Apakah mahasiswa dari luar UPN Veteran Jakarta bisa mendaftar?', answer: 'Bisa. UPA LUK melayani sertifikasi kompetensi untuk mahasiswa internal UPN Veteran Jakarta maupun asesi dari masyarakat umum (publik) atau institusi lain.' },
                  { category: 'Asesmen', question: 'Di mana lokasi pelaksanaan uji kompetensi?', answer: 'Pelaksanaan asesmen umumnya dilakukan di Tempat Uji Kompetensi (TUK) yang berlokasi di kampus UPN Veteran Jakarta, Pondok Labu. Beberapa skema tertentu mungkin mengizinkan TUK Sewaktu di industri terkait.' },
                  { category: 'Asesmen', question: 'Apa saja dokumen fisik yang harus saya bawa saat hari H asesmen?', answer: 'Asesi wajib membawa kartu identitas asli (KTP/KTM), bukti tagihan pembayaran lunas, dan mencetak dokumen fisik portofolio (berkmap merah/biru) yang telah diajukan saat tahap pendaftaran online.' },
                  { category: 'Asesmen', question: 'Apa pakaian yang harus dikenakan saat Uji Kompetensi?', answer: 'Peserta diwajibkan mengenakan pakaian formal rapi. Atasan kemeja berwarna putih (boleh berlengan pendek/panjang), celana kain/rok hitam gelap, dan bersepatu tertutup. Menggunakan Almamater bagi mahasiswa UPNVJ sangat disarankan.' },
                  { category: 'Sertifikat', question: 'Berapa lama proses penerbitan sertifikat BNSP?', answer: 'Setelah Anda dinyatakan Kompeten (K) secara pleno, LSP akan merekapitulasi dan mengajukan blanko ke BNSP Pusat. Proses pencetakan dan distribusi sertifikat membutuhkan waktu estimasi 2 hingga 4 minggu setelah tanggal ujian.' },
                  { category: 'Sertifikat', question: 'Berapa lama masa berlaku sertifikat kompetensi BNSP?', answer: 'Sertifikat kompetensi berlogo Garuda (dari BNSP) umumnya memiliki masa berlaku selama 3 (tiga) tahun. Setelah masa berlaku habis, Anda harus melakukan proses perpanjangan (Recognition of Current Competency / RCC).' },
                  { category: 'Sertifikat', question: 'Apakah sertifikat BNSP diakui secara Internasional?', answer: 'Ya, sertifikat BNSP diakui oleh negara-negara di wilayah Asia Tenggara (ASEAN) dan beberapa negara mitra melalui kesepakatan Mutual Recognition Arrangement (MRA).' },
                  { category: 'Lainnya', question: 'Bagaimana jika saya dinyatakan Belum Kompeten (BK)?', answer: 'Anda berhak mengajukan banding atas keputusan Asesor melalui form banding yang tersedia pada hari asesmen. Atau, Anda dapat mengikuti uji ulang/remedial pada periode berikutnya (ketentuan biaya uji ulang disesuaikan).' },
                  { category: 'Lainnya', question: 'Saya lupa kata sandi akun saya, bagaimana cara reset?', answer: 'Gunakan fitur Lupa Password di halaman Login. Jika email tidak masuk, Anda dapat menghubungi Admin LSP melalui Email atau WhatsApp resmi pada Jam Kerja (08.00 - 16.00 WIB).' }
              ],
              get filteredFaqs() {
                  if (this.searchQuery.trim() === '') {
                      if (this.activeTab === 'Semua') {
                          return this.faqs;
                      }
                      return this.faqs.filter(faq => faq.category === this.activeTab);
                  } else {
                      const q = this.searchQuery.toLowerCase();
                      return this.faqs.filter(faq => 
                          faq.question.toLowerCase().includes(q) || 
                          faq.answer.toLowerCase().includes(q)
                      );
                  }
              }
          }">
        
        <div class="max-w-4xl mx-auto w-full">
            
            <!-- Header & Search Bar -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4 tracking-tight">Pusat Bantuan & <span class="text-[#ea580c]">FAQ</span></h1>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4 text-lg">Temukan jawaban atas pertanyaan yang sering diajukan seputar registrasi dan proses sertifikasi.</p>
                
                <div class="mt-8 relative max-w-2xl mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" 
                           class="block w-full pl-12 pr-4 py-4 rounded-full border-gray-200 bg-white focus:bg-white focus:ring-2 focus:ring-[#ea580c] focus:border-[#ea580c] shadow-md transition-shadow text-gray-800 placeholder-gray-400" 
                           placeholder="Cari pertanyaan... (misal: pendaftaran, sertifikat)">
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-8" x-show="searchQuery.trim() === ''">
                <!-- Mobile Dropdown/Scrollable hint (Hidden on Desktop) -->
                <p class="text-xs text-gray-500 mb-2 md:hidden italic text-center">Geser untuk melihat kategori lain</p>
                
                <div class="flex overflow-x-auto hide-scrollbar whitespace-nowrap border-b border-gray-200 pb-px gap-2 md:gap-8 justify-start md:justify-center px-2 md:px-0">
                    <template x-for="tab in tabs" :key="tab">
                        <button @click="activeTab = tab"
                                :class="{'border-[#ea580c] text-[#ea580c] font-bold': activeTab === tab, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium': activeTab !== tab}"
                                class="border-b-2 py-3 px-3 md:px-1 transition-colors duration-200 focus:outline-none flex-shrink-0 text-sm md:text-base"
                                x-text="tab">
                        </button>
                    </template>
                </div>
            </div>
            
            <!-- Result Information -->
            <div x-show="searchQuery.trim() !== ''" style="display: none;" class="mb-6 mb-8 text-center text-gray-600 bg-orange-50 py-3 rounded-lg border border-orange-100">
                <p>Menampilkan hasil pencarian untuk: <span class="font-bold text-[#ea580c]" x-text="`&quot;${searchQuery}&quot;`"></span></p>
            </div>

            <!-- Accordion List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100 mb-10 overflow-hidden">
                <template x-for="(faq, index) in filteredFaqs" :key="index">
                    <div x-data="{ expanded: false }" class="transition-colors hover:bg-gray-50">
                        <button @click="expanded = !expanded" 
                                class="flex items-center justify-between w-full p-6 focus:outline-none focus:bg-gray-50 transition-colors">
                            <div class="flex items-start text-left gap-4">
                                <div class="mt-1 flex-shrink-0">
                                    <svg x-show="!expanded" class="w-5 h-5 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <svg x-show="expanded" style="display:none;" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-gray-900 leading-snug" x-text="faq.question"></h3>
                                    <!-- Category Badge only shows during search -->
                                    <span x-show="searchQuery.trim() !== ''" class="inline-block mt-2 text-[10px] uppercase font-bold tracking-wider rounded-full px-2 py-0.5 bg-blue-50 text-[#2563eb]" x-text="faq.category"></span>
                                </div>
                            </div>
                        </button>
                        
                        <div x-show="expanded" 
                             x-collapse 
                             style="display: none;" 
                             class="px-6 pb-6 pt-0 text-gray-600 sm:px-8 text-sm md:text-base leading-relaxed pl-[3.25rem]">
                            <p x-text="faq.answer"></p>
                        </div>
                    </div>
                </template>
                
                <!-- Empty State -->
                <div x-show="filteredFaqs.length === 0" style="display: none;" class="p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium">Maaf, kami tidak menemukan pertanyaan yang relevan.</p>
                    <p class="text-sm mt-1">Coba gunakan kata kunci lain (misal: "sertifikat", "ujian").</p>
                </div>
            </div>

            <!-- Footer Help Callout -->
            <div class="bg-[#1f2937] rounded-2xl p-8 text-center sm:flex sm:items-center sm:justify-between text-white shadow-xl shadow-gray-900/10">
                <div class="sm:text-left mb-6 sm:mb-0">
                    <h3 class="text-xl font-extrabold mb-2 tracking-tight">Masih butuh bantuan?</h3>
                    <p class="text-gray-400">Tim operasional UPA LUK siap membantu Anda pada jam kerja.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('kontak') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition w-full sm:w-auto">
                        Hubungi Kami
                    </a>
                </div>
            </div>

        </div>
    </main>

    <x-public.footer />

    <!-- Hide Scrollbar CSS -->
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</body>
</html>
