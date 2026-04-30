    <section
        class="relative overflow-hidden bg-[linear-gradient(135deg,_#183b68_0%,_#205b8f_45%,_#1fb6d9_100%)] px-6 pb-24 pt-36 lg:px-16">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.18),_transparent_28%)]">
        </div>
        <div class="absolute -left-24 top-24 h-56 w-56 rounded-full bg-white/10 blur-3xl">
        </div>
        <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-cyan-200/20 blur-3xl">
        </div>

        <div
            class="relative mx-auto flex w-full max-w-[85rem] flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-cyan-100/80">Skema Sertifikasi</p>
                <h1 class="mt-5 text-4xl font-bold leading-tight md:text-5xl lg:text-[3.6rem]">
                    Temukan jalur sertifikasi yang paling sesuai dengan kompetensi Anda.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-blue-50/88 md:text-lg">
                    Jelajahi skema aktif dari berbagai fakultas dan program studi UPA LUK, lalu pilih skema yang paling
                    relevan untuk pengembangan kompetensi Anda.
                </p>
            </div>

            <div class="grid max-w-xl gap-4 sm:grid-cols-3">
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">Skema Aktif</p>
                    <p class="mt-3 text-3xl font-bold text-white">{{ $schemes->count() }}</p>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">Fakultas</p>
                    <p class="mt-3 text-3xl font-bold text-white">{{ $faculties->count() }}</p>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">
                        {{ $jenisSkemas->isNotEmpty() ? 'Jenis Skema' : 'Klasifikasi' }}
                    </p>
                    @if ($jenisSkemas->isNotEmpty())
                        <p class="mt-3 text-3xl font-bold text-white">{{ $jenisSkemas->count() }}</p>
                    @else
                        <p class="mt-3 text-lg font-bold text-white">Belum diisi</p>
                        <p class="mt-1 text-xs leading-5 text-blue-50/80">Contohnya: Okupasi, Klaster, atau KKNI.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
