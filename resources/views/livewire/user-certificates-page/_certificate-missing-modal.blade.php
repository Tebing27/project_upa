    <div x-cloak x-show="certificateMissingOpen" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4">
        <div x-show="certificateMissingOpen" x-transition @click.outside="certificateMissingOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-900">Sertifikat belum tersedia</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Sertifikat copy belum diunggah admin. Silakan gunakan surat keterangan terlebih dahulu.
            </p>
            <div class="mt-5 flex justify-end">
                <button type="button" x-on:click="certificateMissingOpen = false"
                    class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-400">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
