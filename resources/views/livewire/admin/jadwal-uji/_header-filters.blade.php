    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Jadwal Uji</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola peserta yang siap dijadwalkan dan yang sudah memiliki jadwal.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span
                class="inline-flex items-center rounded-full bg-slate-100 px-3.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                {{ $registrations->count() }} Total
            </span>
            <span
                class="inline-flex items-center rounded-full bg-emerald-50 px-3.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                {{ $readyRegistrationsCount }} Lunas
            </span>
            <span
                class="inline-flex items-center rounded-full bg-amber-50 px-3.5 py-1 text-xs font-semibold text-amber-700 border border-amber-100">
                {{ $scheduledRegistrationsCount }} Terjadwal
            </span>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Filter Pencarian</h3>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nama / NIM / NIK..."
                        class="block w-full px-4 py-3 pl-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>

                <div class="flex-1 lg:w-56">
                    <input wire:model.live="filterDate" type="date"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>
            </div>
        </div>
    </div>

