        {{-- Tabs --}}
        <div class="flex gap-1 rounded-xl bg-slate-100 p-1 w-fit">
            <button type="button" wire:click="$set('activeTab', 'info')"
                @class([
                    'px-5 py-2.5 rounded-lg text-sm font-semibold transition-all',
                    'bg-white text-gray-900 shadow-sm' => $activeTab === 'info',
                    'text-gray-500 hover:text-gray-700' => $activeTab !== 'info',
                ])>
                Info Dasar
            </button>
            <button type="button" wire:click="$set('activeTab', 'unit')"
                @class([
                    'px-5 py-2.5 rounded-lg text-sm font-semibold transition-all',
                    'bg-white text-gray-900 shadow-sm' => $activeTab === 'unit',
                    'text-gray-500 hover:text-gray-700' => $activeTab !== 'unit',
                ])>
                Unit Kompetensi
                @if (count($unitKompetensis) > 0)
                    <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">{{ count($unitKompetensis) }}</span>
                @endif
            </button>
            <button type="button" wire:click="$set('activeTab', 'persyaratan')"
                @class([
                    'px-5 py-2.5 rounded-lg text-sm font-semibold transition-all',
                    'bg-white text-gray-900 shadow-sm' => $activeTab === 'persyaratan',
                    'text-gray-500 hover:text-gray-700' => $activeTab !== 'persyaratan',
                ])>
                Persyaratan
                @php $totalPersyaratan = count($persyaratanDasars) + count($persyaratanAdministrasis); @endphp
                @if ($totalPersyaratan > 0)
                    <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">{{ $totalPersyaratan }}</span>
                @endif
            </button>
        </div>

