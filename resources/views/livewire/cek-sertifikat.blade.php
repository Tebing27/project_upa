<div class="min-h-screen bg-slate-50">
    <x-public.navbar active="validasi" />

    <section class="relative flex min-h-[38vh] items-center justify-center overflow-hidden bg-gray-900 px-6 pb-20 pt-32">
        <img src="{{ asset('assets/background.webp') }}" alt="Validasi Sertifikat"
            class="absolute inset-0 h-full w-full object-cover opacity-55">
        <div
            class="absolute inset-0 bg-[linear-gradient(135deg,_rgba(15,23,42,0.62),_rgba(30,41,59,0.72))]">
        </div>
        <div class="relative z-10 text-center">
            <h1 class="text-[1.9rem] font-bold tracking-tight text-white md:text-5xl">Cek Keaslian Sertifikat</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-slate-300 md:text-base">
                Verifikasi keaslian sertifikat kompetensi yang diterbitkan oleh {{ config('app.name') }}.
            </p>
        </div>
    </section>

    <main class="mx-auto max-w-5xl px-6 pb-10 pt-6">
        <div class="space-y-8">
            <div
                class="mx-auto max-w-md rounded-[1.25rem] bg-white shadow-[0_18px_40px_-24px_rgba(15,23,42,0.4)] ring-1 ring-slate-200/80">
                <div class="px-6 py-6 md:px-8">
                    <form wire:submit="cekSertifikat" class="flex flex-col gap-5">
                        <div class="grid gap-5">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Nama
                                    Lengkap</label>
                                <input wire:model="name" type="text" id="name"
                                    placeholder="Masukkan nama lengkap..."
                                    class="block w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none placeholder:text-slate-400 text-sm" />
                                @error('name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="search" class="mb-2 block text-sm font-semibold text-gray-700">Nomor
                                    Sertifikasi /
                                    Registrasi</label>
                                <input wire:model="search" type="text" id="search"
                                    placeholder="Contoh: CERT-2210511042 atau CERT-000000000001"
                                    class="block w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none placeholder:text-slate-400 text-sm" />
                                @error('search')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-400 px-8 py-3 text-sm font-bold text-black shadow-sm transition hover:bg-emerald-500 sm:w-auto">
                                <span wire:loading.remove wire:target="cekSertifikat">Cek Sertifikat</span>
                                <span wire:loading wire:target="cekSertifikat">Mencari...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($hasSearched)
                <div
                    class="mx-auto max-w-5xl rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-5 md:px-8">
                        <h2 class="text-[1.1rem] font-bold text-gray-900">
                            Hasil Pencarian
                            <span class="ml-2 text-sm font-normal text-gray-500">({{ count($results) }}
                                ditemukan)</span>
                        </h2>
                    </div>

                    @if (count($results) > 0)
                        <div class="overflow-x-auto pb-4">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 md:px-8">
                                            No. Sertifikat</th>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                            Nama Pemilik</th>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                            Skema</th>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                            Tanggal Terbit</th>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                            Masa Berlaku</th>
                                        <th
                                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($results as $certificate)
                                        <tr wire:key="cert-{{ $certificate['id'] }}"
                                            class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-6 py-4 font-mono text-[13px] text-gray-600 md:px-8">
                                                {{ $certificate['nomor'] }}
                                            </td>
                                            <td class="px-6 py-4 text-[13px] font-semibold text-gray-900">
                                                {{ $certificate['nama_pemilik'] }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-[13px] font-semibold text-gray-900">
                                                    {{ $certificate['skema'] }}</p>
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
                                                <span
                                                    class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $badgeClasses }}">
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
                            <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <p class="mt-3 text-[13px] font-medium text-gray-500">Sertifikat tidak ditemukan.</p>
                            <p class="mt-1 text-[12px] text-gray-400">Pastikan nomor sertifikat yang Anda masukkan sudah
                                benar.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <x-public.footer />

    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-8 right-8 bg-[#1e40af] text-white p-3.5 rounded-full shadow-2xl hover:bg-blue-800 transition z-50 hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
</div>
