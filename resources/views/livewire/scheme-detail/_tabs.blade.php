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
