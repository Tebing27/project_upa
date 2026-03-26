<div class="space-y-8">
    <div class="text-center">
        <h1 class="text-[1.75rem] font-bold text-gray-900">Cek Keaslian Sertifikat</h1>
        <p class="mt-2 text-sm text-gray-500">Verifikasi keaslian sertifikat kompetensi yang diterbitkan oleh {{ config('app.name') }}.</p>
    </div>

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="px-6 py-6 md:px-8">
            <form wire:submit="cekSertifikat" class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Sertifikat</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input
                            wire:model="search"
                            type="text"
                            id="search"
                            placeholder="Masukkan nomor sertifikat (CERT-00001) atau nama pemilik..."
                            class="block w-full rounded-xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-all focus:border-emerald-500 focus:ring-emerald-500"
                        />
                    </div>
                    @error('search')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black transition hover:bg-emerald-500"
                >
                    <span wire:loading.remove wire:target="cekSertifikat">Cek Sertifikat</span>
                    <span wire:loading wire:target="cekSertifikat">Mencari...</span>
                </button>
            </form>
        </div>
    </div>

    @if ($hasSearched)
        <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-100 px-6 py-5 md:px-8">
                <h2 class="text-[1.1rem] font-bold text-gray-900">
                    Hasil Pencarian
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ count($results) }} ditemukan)</span>
                </h2>
            </div>

            @if (count($results) > 0)
                <div class="overflow-x-auto pb-4">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 md:px-8">
                                    No. Sertifikat</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Nama Pemilik</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Skema</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Tanggal Terbit</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Masa Berlaku</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($results as $certificate)
                                <tr wire:key="cert-{{ $certificate['id'] }}" class="transition-colors hover:bg-gray-50/50">
                                    <td class="px-6 py-4 font-mono text-[13px] text-gray-600 md:px-8">
                                        {{ $certificate['nomor'] }}
                                    </td>
                                    <td class="px-6 py-4 text-[13px] font-semibold text-gray-900">
                                        {{ $certificate['nama_pemilik'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-[13px] font-semibold text-gray-900">{{ $certificate['skema'] }}</p>
                                        <p class="mt-0.5 text-[12px] text-gray-500">
                                            {{ $certificate['fakultas'] }}
                                            @if ($certificate['program_studi'])
                                                - {{ $certificate['program_studi'] }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-[13px] text-gray-600">
                                        {{ $certificate['tanggal_terbit'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-[13px] text-gray-600">
                                        {{ $certificate['masa_berlaku'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClasses = $certificate['is_active']
                                                ? 'bg-emerald-50 text-[#1b8a6b] ring-emerald-200/50'
                                                : 'bg-red-50 text-red-600 ring-red-200/50';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                            {{ $certificate['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <p class="mt-3 text-[13px] font-medium text-gray-500">Sertifikat tidak ditemukan.</p>
                    <p class="mt-1 text-[12px] text-gray-400">Pastikan nomor sertifikat atau nama yang Anda masukkan sudah benar.</p>
                </div>
            @endif
        </div>
    @endif
</div>
