<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <h2 class="text-xl font-bold text-gray-900">Pilih Skema Sertifikasi</h2>
    <p class="mt-1 text-sm text-gray-500">Pilih skema sertifikasi yang ingin Anda ikuti.</p>

    <div class="mt-8">
            <p class="mt-1 text-sm text-gray-500">
                @if ($showFacultyFilters)
                    Gunakan filter fakultas dan program studi untuk mempersempit pilihan skema. Jika
                    biodata sudah terisi, filter akan otomatis menyesuaikan.
                @else
                    Pilih skema yang sesuai dengan tujuan sertifikasi Anda.
                @endif
            </p>

            @if ($showFacultyFilters)
                <div
                    class="mb-6 mt-4 grid grid-cols-1 gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                        <select wire:model.live="faculty"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm">
                            <option value="">-- Semua Fakultas --</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->id }}">{{ $fac->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <select wire:model.live="studyProgram"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm disabled:cursor-not-allowed disabled:bg-gray-100"
                            @disabled(! $faculty)>
                            <option value="">-- Semua Program Studi --</option>
                            @foreach ($studyPrograms as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @php
                $schemes = $newSchemes;
            @endphp

            @if ($schemes->isEmpty())
                <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50/50 p-5 text-sm text-amber-700">
                    Tidak ada skema baru yang tersedia untuk pilihan Anda saat ini.
                    @if ($hasMatchingCertifiedSchemeForNewRegistration)
                        <p class="mt-2 text-amber-800">Anda sudah memiliki riwayat sertifikasi untuk skema yang cocok.</p>
                    @endif
                </div>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($schemes as $scheme)
                        <label wire:key="scheme-{{ $scheme->id }}" @class([
                            'flex cursor-pointer items-start gap-4 rounded-xl border-2 p-5 transition-all',
                            'border-emerald-500 bg-emerald-50/30' => (int) $schemeId === $scheme->id,
                            'border-gray-200 hover:border-gray-300' => (int) $schemeId !== $scheme->id,
                        ])>
                            <input type="radio" wire:model.live="schemeId" value="{{ $scheme->id }}"
                                class="mt-1 h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $scheme->name }}</p>
                                <p class="mt-0.5 text-[13px] text-gray-500">
                                    {{ $scheme->faculty ?: 'Umum' }}{{ $scheme->study_program ? ' - ' . $scheme->study_program : '' }}
                                </p>
                                @if ($scheme->description)
                                    <p class="mt-1 text-[13px] text-gray-400">{{ $scheme->description }}</p>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
            @error('schemeId')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
    </div>

    <div class="mt-8 flex justify-end">
        <button type="button" wire:click="nextStep" @disabled(! $schemeId)
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
            Selanjutnya
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
