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
