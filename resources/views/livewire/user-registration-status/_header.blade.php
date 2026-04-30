<div>
    <h1 class="text-2xl md:text-[28px] font-bold tracking-tight text-[#1e293b]">Status Pendaftaran</h1>
    <p class="mt-1.5 text-[15px] text-slate-500">Pantau progres pendaftaran, cek status dokumen, dan upload
        ulang
        dokumen yang ditolak.</p>
</div>

@if ($successMessage)
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ $successMessage }}
    </div>
@endif
