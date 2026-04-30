<div class="mb-8 border-b border-gray-100 pb-4">
    <h3 class="text-xl font-bold italic text-gray-900">Bagian 2 : Data Sertifikasi</h3>
    <p class="mt-1 text-xs italic text-gray-400">Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan
        berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai
        dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.</p>
</div>
<div class="overflow-x-auto rounded-xl border border-gray-300">
    <table class="w-full min-w-[600px] border-collapse">
        <tbody>
            <tr>
                <td rowspan="2" class="w-48 border-r border-b border-gray-300 bg-gray-50 p-4 text-sm font-bold text-gray-700">
                    Skema Sertifikasi (KKNI/Okupasi/Klaster)</td>
                <td class="w-24 border-r border-b border-gray-300 bg-gray-50 p-3 text-sm font-bold text-gray-700">
                    Judul</td>
                <td class="border-b border-gray-300 p-4 text-sm italic">:
                    {{ $selectedScheme?->nama }}</td>
            </tr>
            <tr>
                <td class="border-r border-b border-gray-300 bg-gray-50 p-3 text-sm font-bold text-gray-700">
                    Nomor</td>
                <td class="border-b border-gray-300 p-4 text-sm italic">:
                    {{ $selectedScheme?->kode_skema }}</td>
            </tr>
            <tr>
                <td colspan="2" class="border-r border-b border-gray-300 bg-gray-50 p-4 text-sm font-bold text-gray-700">
                    Tujuan Asesmen</td>
                <td class="border-b border-gray-300 p-4">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 text-gray-400">:</span>
                        <div class="flex flex-col gap-3">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="radio" wire:model="assessmentPurpose" value="sertifikasi" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">Sertifikasi</span>
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="radio" wire:model="assessmentPurpose" value="paling_lambat_pkt" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">Pengakuan Kompetensi Terkini (PKT)</span>
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="radio" wire:model="assessmentPurpose" value="rpl" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">Rekognisi Pembelajaran Lampau (RPL)</span>
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="radio" wire:model="assessmentPurpose" value="lainnya" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">Lainnya</span>
                            </label>
                        </div>
                    </div>
                    @error('assessmentPurpose')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </td>
            </tr>
        </tbody>
    </table>
</div>

@if ($selectedScheme && $selectedScheme->unitKompetensis->isNotEmpty())
    <div class="mt-8">
        <p class="mb-2 text-xs font-bold italic text-gray-800">Daftar Unit Kompetensi sesuai kemasan:</p>
        <div class="overflow-x-auto rounded-xl border border-gray-300">
            <table class="w-full text-center text-xs">
                <thead class="border-b border-gray-300 bg-gray-50 font-bold">
                    <tr>
                        <td class="w-12 border-r border-gray-300 p-2">No.</td>
                        <td class="w-48 border-r border-gray-300 p-2">Kode Unit</td>
                        <td class="border-r border-gray-300 p-2">Judul Unit</td>
                        <td class="w-48 p-2">Standar Kompetensi Kerja</td>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 italic">
                    @foreach ($selectedScheme->unitKompetensis as $unit)
                        <tr>
                            <td class="border-r border-gray-300 p-2">{{ $loop->iteration }}.</td>
                            <td class="border-r border-gray-300 p-2">{{ $unit->kode_unit }}</td>
                            <td class="border-r border-gray-300 p-2 text-left">{{ $unit->nama_unit }}</td>
                            <td class="p-2">{{ $unit->standar }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
    <button type="button" wire:click="previousStep" class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
    </button>
    <button type="button" wire:click="nextStep" class="inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 w-full sm:w-auto">
        Lanjut ke Bagian 3
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>
