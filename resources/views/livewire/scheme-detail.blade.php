<div class="min-h-screen bg-slate-50/50">
    <x-public.navbar active="skema" sticky="true" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors">Home</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('skema.index') }}" class="hover:text-gray-700 transition-colors">Skema
                    Sertifikasi</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="font-semibold text-gray-900 uppercase">{{ $scheme->name }}</span>
            </nav>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Detail Skema</h1>
            <div class="mt-2 h-1 w-32 rounded-full bg-emerald-400"></div>
        </div>

        {{-- Main Card --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
            <h2 class="text-center text-2xl font-bold text-gray-900 uppercase">{{ $scheme->name }}</h2>

            {{-- Daftar Button --}}
            <div class="mt-5 flex justify-center">
                @auth
                    <a href="{{ route('dashboard.daftar-skema', ['scheme' => $scheme->id, 'type' => 'baru']) }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black transition-all hover:bg-emerald-500">
                        Daftar Uji Kompetensi
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-semibold text-black transition-all hover:bg-emerald-500">
                        Daftar Uji Kompetensi
                    </a>
                @endauth
            </div>

            {{-- Detail Table --}}
            <div class="mt-8 overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @if ($scheme->kode_skema)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Kode Skema</td>
                                <td class="px-2 py-4 text-gray-400">:</td>
                                <td class="px-4 py-4 text-gray-700">{{ $scheme->kode_skema }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Nama Skema</td>
                            <td class="px-2 py-4 text-gray-400">:</td>
                            <td class="px-4 py-4 text-gray-700">{{ $scheme->name }}</td>
                        </tr>
                        @if ($scheme->jenis_skema)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Jenis Skema</td>
                                <td class="px-2 py-4 text-gray-400">:</td>
                                <td class="px-4 py-4 text-gray-700">{{ $scheme->jenis_skema }}</td>
                            </tr>
                        @endif
                        @if ($scheme->izin_nirkertas)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Izin Nirkertas</td>
                                <td class="px-2 py-4 text-gray-400">:</td>
                                <td class="px-4 py-4 text-gray-700">{{ $scheme->izin_nirkertas }}</td>
                            </tr>
                        @endif
                        @if ($scheme->harga)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Harga</td>
                                <td class="px-2 py-4 text-gray-400">:</td>
                                <td class="px-4 py-4 text-gray-700">Rp.
                                    {{ number_format((float) $scheme->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Unit Kompetensi</td>
                            <td class="px-2 py-4 text-gray-400">:</td>
                            <td class="px-4 py-4 text-gray-700">{{ $scheme->unitKompetensis->count() }}
                                ({{ $this->numberToWords($scheme->unitKompetensis->count()) }})</td>
                        </tr>
                        @if ($scheme->dokumen_skema_path)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3">Dokumen Skema</td>
                                <td class="px-2 py-4 text-gray-400">:</td>
                                <td class="px-4 py-4">
                                    <a href="{{ Storage::url($scheme->dokumen_skema_path) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if ($scheme->ringkasan_skema)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-emerald-600 w-1/3 align-top">Ringkasan Skema
                                </td>
                                <td class="px-2 py-4 text-gray-400 align-top">:</td>
                                <td class="px-4 py-4 text-gray-700 leading-relaxed whitespace-pre-line">{{ $scheme->ringkasan_skema }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabs: Unit Kompetensi & Persyaratan --}}
        <div class="mt-6 rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            {{-- Tab Headers --}}
            <div class="border-b border-gray-100">
                <div class="flex">
                    <button wire:click="$set('activeTab', 'unit')" @class([
                        'px-6 py-4 text-sm font-semibold border-b-2 transition-colors',
                        'border-emerald-500 text-emerald-600' => $activeTab === 'unit',
                        'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== 'unit',
                    ])>
                        Unit Kompetensi
                    </button>
                    <button wire:click="$set('activeTab', 'persyaratan')" @class([
                        'px-6 py-4 text-sm font-semibold border-b-2 transition-colors',
                        'border-emerald-500 text-emerald-600' => $activeTab === 'persyaratan',
                        'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== 'persyaratan',
                    ])>
                        Persyaratan
                    </button>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="p-6 md:p-8">
                @if ($activeTab === 'unit')
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Daftar Unit Kompetensi
                    </h3>
                    @if ($scheme->unitKompetensis->isNotEmpty())
                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 w-12">
                                            No</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 w-40">
                                            Kode Unit</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            Unit Kompetensi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($scheme->unitKompetensis as $index => $unit)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-4 py-3 text-emerald-600 font-semibold">
                                                {{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-blue-600 font-medium">
                                                {{ $unit->kode_unit }}</td>
                                            <td class="px-4 py-3 text-gray-700">
                                                <div>{{ $unit->nama_unit }}</div>
                                                @if ($unit->nama_unit_en)
                                                    <div class="text-gray-400 text-xs mt-0.5">
                                                        {{ $unit->nama_unit_en }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum ada unit kompetensi untuk skema ini.</p>
                    @endif
                @else
                    {{-- Persyaratan Dasar --}}
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Persyaratan Dasar</h3>
                    @if ($scheme->persyaratanDasars->isNotEmpty())
                        <div class="overflow-hidden rounded-xl border border-gray-200 mb-8">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 w-12">
                                            No</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            Persyaratan Dasar Pemohon</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($scheme->persyaratanDasars as $index => $pd)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-4 py-3 text-emerald-600 font-semibold">
                                                {{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $pd->deskripsi }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mb-8">Belum ada persyaratan dasar untuk skema ini.</p>
                    @endif

                    {{-- Persyaratan Administrasi --}}
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Persyaratan Administrasi
                    </h3>
                    @if ($scheme->persyaratanAdministrasis->isNotEmpty())
                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 w-12">
                                            No</th>
                                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            Persyaratan Administrasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($scheme->persyaratanAdministrasis as $index => $pa)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-4 py-3 text-emerald-600 font-semibold">
                                                {{ $index + 1 }}.</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $pa->nama_dokumen }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum ada persyaratan administrasi untuk skema ini.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
