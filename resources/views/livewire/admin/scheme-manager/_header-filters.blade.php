    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Manajemen Skema</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola daftar skema sertifikasi yang tersedia.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filterFaculty"
                class="block w-48 px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                <option value="">Semua Fakultas</option>
                @foreach ($this->availableFaculties as $facultyOption)
                    <option value="{{ $facultyOption->id }}">{{ $facultyOption->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.schemes.create') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Skema
            </a>
        </div>
    </div>
