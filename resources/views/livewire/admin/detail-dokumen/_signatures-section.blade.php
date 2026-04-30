            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Tanda Tangan Pemohon</h3>
                    <p class="mt-2 text-sm text-slate-500">Tanda tangan dari FR.APL.01 Bagian 4 yang diisi peserta.</p>

                    @if ($registration->applicant_signature_path)
                        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white p-4">
                            <img src="{{ Storage::url($registration->applicant_signature_path) }}" alt="Tanda tangan pemohon"
                                class="h-40 w-full object-contain">
                        </div>
                    @else
                        <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-white p-5 text-sm text-slate-500">
                            Peserta belum memiliki tanda tangan tersimpan.
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700">Tanda Tangan Admin</h3>
                    <p class="mt-2 text-sm text-emerald-800">
                        Tanda tangan admin dikelola dari halaman index verifikasi dokumen. Saat dokumen peserta diverifikasi, tanda tangan global itu otomatis ditempelkan ke pendaftaran peserta ini.
                    </p>

                    @if ($registration->admin_signature_path)
                        <div class="mt-5 overflow-hidden rounded-2xl border border-emerald-100 bg-white p-4">
                            <img src="{{ Storage::url($registration->admin_signature_path) }}" alt="Tanda tangan admin"
                                class="h-32 w-full object-contain">
                            <p class="mt-3 text-sm font-semibold text-slate-800">{{ $registration->admin_signatory_name ?: '-' }}</p>
                        </div>
                    @else
                        <div class="mt-5 rounded-2xl border border-dashed border-emerald-100 bg-white p-5 text-sm text-slate-500">
                            Belum ada tanda tangan admin yang ditempelkan ke pendaftaran ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
