<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.schemes') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2.5 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ $schemeId ? 'Edit Skema' : 'Tambah Skema Baru' }}
                </h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ $schemeId ? 'Perbarui informasi skema sertifikasi.' : 'Isi formulir berikut untuk menambahkan skema baru.' }}
                </p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6"
        x-data="{
            imagePreviewUrl: null,
            pdfPreviewUrl: null,
            apl02PreviewUrl: null,
            setImagePreview(event) {
                const [file] = event.target.files ?? [];
                this.imagePreviewUrl = file ? URL.createObjectURL(file) : null;
            },
            setPdfPreview(event) {
                const [file] = event.target.files ?? [];
                this.pdfPreviewUrl = file ? URL.createObjectURL(file) : null;
            },
            setApl02Preview(event) {
                const [file] = event.target.files ?? [];
                this.apl02PreviewUrl = file ? URL.createObjectURL(file) : null;
            },
        }">
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

        {{-- Tab: Info Dasar --}}
        <div x-show="$wire.activeTab === 'info'"
            class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Informasi Dasar</h2>
            </div>
            <div class="p-6 space-y-5">
                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                        Nama Skema <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                        required placeholder="Masukkan nama skema" />
                    @error('name')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Kode Skema
                        </label>
                        <input wire:model="kode_skema" type="text"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            placeholder="SKK-24-10/2024" />
                        @error('kode_skema')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Jenis Skema
                        </label>
                        <input wire:model="jenis_skema" type="text"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            placeholder="Okupasi" />
                        @error('jenis_skema')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Izin Nirkertas
                        </label>
                        <input wire:model="izin_nirkertas" type="text"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            placeholder="SJJ" />
                        @error('izin_nirkertas')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Harga
                        </label>
                        <input wire:model="harga" type="number" step="1000"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            placeholder="500000" />
                        @error('harga')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Fakultas <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="faculty_id"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            required>
                            <option value="">Pilih Fakultas</option>
                            @foreach ($this->faculties as $facultyOption)
                                <option value="{{ $facultyOption->id }}">{{ $facultyOption->name }}</option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-3">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">Tambah Fakultas Baru</label>
                            <div class="mt-2 flex gap-2">
                                <input wire:model="newFacultyName" type="text"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Contoh: Fakultas Teknik">
                                <button type="button" wire:click="createFaculty"
                                    class="shrink-0 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                    Tambah
                                </button>
                            </div>
                            @error('newFacultyName')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Program Studi
                        </label>
                        <select wire:model="study_program_id" wire:key="study-program-{{ $faculty_id ?? 'none' }}"
                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                            @disabled(!$faculty_id)>
                            <option value="">Semua Program Studi</option>
                            @foreach ($this->studyPrograms as $studyProgramOption)
                                <option value="{{ $studyProgramOption->id }}">{{ $studyProgramOption->nama }}</option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-3">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">Tambah Program Studi Baru</label>
                            <div class="mt-2 flex gap-2">
                                <input wire:model="newStudyProgramName" type="text"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Contoh: Sistem Informasi" @disabled(!$faculty_id)>
                                <button type="button" wire:click="createStudyProgram"
                                    class="shrink-0 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300"
                                    @disabled(!$faculty_id)>
                                    Tambah
                                </button>
                            </div>
                            @error('newStudyProgramName')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                            @if (!$faculty_id)
                                <p class="mt-2 text-xs text-slate-500">Pilih fakultas dulu agar program studi baru tersimpan ke kategori yang tepat.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                        Deskripsi Singkat
                    </label>
                    <textarea wire:model="description" rows="2"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                        placeholder="Deskripsi singkat skema"></textarea>
                    @error('description')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                        Ringkasan Skema
                    </label>
                    <textarea wire:model="ringkasan_skema" rows="4"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                        placeholder="Ringkasan lengkap mengenai skema sertifikasi..."></textarea>
                    @error('ringkasan_skema')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Gambar Skema
                        </label>
                        <input type="file" wire:model="gambar" accept="image/*" x-on:change="setImagePreview($event)"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                        @error('gambar')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($gambar)
                            <div class="mt-4 overflow-hidden rounded-2xl border border-emerald-100 bg-emerald-50/40 p-3">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Preview Gambar Baru</p>
                                    <a x-show="imagePreviewUrl" x-bind:href="imagePreviewUrl" target="_blank"
                                        class="inline-flex items-center rounded-xl border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Buka Gambar
                                    </a>
                                </div>
                                <img x-bind:src="imagePreviewUrl || '{{ $gambar->temporaryUrl() }}'" alt="Preview gambar skema"
                                    class="h-48 w-full rounded-xl object-cover">
                            </div>
                        @elseif ($existingGambarUrl)
                            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Preview Gambar Saat Ini</p>
                                    <a href="{{ $existingGambarUrl }}" target="_blank"
                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                        Buka Gambar
                                    </a>
                                </div>
                                <img src="{{ $existingGambarUrl }}" alt="Gambar skema saat ini"
                                    class="h-48 w-full rounded-xl object-cover">
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Dokumen Skema (PDF)
                        </label>
                        <input type="file" wire:model="dokumen_skema" accept=".pdf" x-on:change="setPdfPreview($event)"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                        @error('dokumen_skema')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($dokumen_skema)
                            <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Preview Dokumen Baru</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $dokumen_skema->getClientOriginalName() }}</p>
                                        <p class="mt-1 text-xs text-slate-500">File baru sudah dipilih dan akan menggantikan dokumen lama saat skema disimpan.</p>
                                    </div>
                                    <a x-show="pdfPreviewUrl" x-bind:href="pdfPreviewUrl" target="_blank"
                                        class="inline-flex items-center rounded-xl border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Buka PDF
                                    </a>
                                </div>
                                <iframe x-show="pdfPreviewUrl" x-bind:src="pdfPreviewUrl" class="mt-4 h-56 w-full rounded-xl border border-emerald-100 bg-white"></iframe>
                            </div>
                        @elseif ($existingDokumenUrl)
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Preview Dokumen Saat Ini</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ basename(parse_url($existingDokumenUrl, PHP_URL_PATH)) }}</p>
                                    </div>
                                    <a href="{{ $existingDokumenUrl }}" target="_blank"
                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                        Buka PDF
                                    </a>
                                </div>
                                <iframe src="{{ $existingDokumenUrl }}" class="mt-4 h-56 w-full rounded-xl border border-slate-200 bg-white"></iframe>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Template APL 02 (DOCX)
                        </label>
                        <input type="file" wire:model="apl_02_template" accept=".docx"
                            x-on:change="setApl02Preview($event)"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                        @error('apl_02_template')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($apl_02_template)
                            <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Template APL 02 Baru</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $apl_02_template->getClientOriginalName() }}</p>
                                        <p class="mt-1 text-xs text-slate-500">File DOCX ini akan tersedia untuk peserta pada step APL 02.</p>
                                    </div>
                                    <a x-show="apl02PreviewUrl" x-bind:href="apl02PreviewUrl" download
                                        class="inline-flex items-center rounded-xl border border-emerald-200 bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Unduh File
                                    </a>
                                </div>
                            </div>
                        @elseif ($existingApl02TemplateUrl)
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Template APL 02 Saat Ini</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ basename(parse_url($existingApl02TemplateUrl, PHP_URL_PATH)) }}</p>
                                    </div>
                                    <a href="{{ $existingApl02TemplateUrl }}" download
                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                        Unduh File
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Tab: Unit Kompetensi --}}
        <div x-show="$wire.activeTab === 'unit'"
            class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Unit Kompetensi</h2>
                <button type="button" wire:click="addUnitKompetensi"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Unit
                </button>
            </div>
            <div class="p-6 space-y-4">
                @forelse ($unitKompetensis as $index => $unit)
                    <div wire:key="unit-{{ $index }}" class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Unit {{ $index + 1 }}</span>
                            <button type="button" wire:click="removeUnitKompetensi({{ $index }})"
                                class="text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.kode_unit"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Kode Unit" />
                                @error("unitKompetensis.{$index}.kode_unit")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.nama_unit"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama Unit (ID)" />
                                @error("unitKompetensis.{$index}.nama_unit")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="unitKompetensis.{{ $index }}.nama_unit_en"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama Unit (EN) - opsional" />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border-2 border-dashed border-gray-200 py-10 text-center text-sm text-gray-400">
                        Belum ada unit kompetensi. Klik "Tambah Unit" untuk menambahkan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tab: Persyaratan --}}
        <div x-show="$wire.activeTab === 'persyaratan'" class="space-y-5">
            {{-- Persyaratan Dasar --}}
            <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Persyaratan Dasar</h2>
                    <button type="button" wire:click="addPersyaratanDasar"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-6 space-y-3">
                    @forelse ($persyaratanDasars as $index => $pd)
                        <div wire:key="pd-{{ $index }}" class="flex items-start gap-2">
                            <span class="mt-2.5 text-xs font-semibold text-gray-400 w-5 shrink-0">{{ $index + 1 }}.</span>
                            <div class="flex-1">
                                <input type="text" wire:model="persyaratanDasars.{{ $index }}.deskripsi"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Deskripsi persyaratan dasar" />
                                @error("persyaratanDasars.{$index}.deskripsi")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" wire:click="removePersyaratanDasar({{ $index }})"
                                class="mt-2 text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-gray-200 py-8 text-center text-sm text-gray-400">
                            Belum ada persyaratan dasar.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Persyaratan Administrasi --}}
            <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Persyaratan Administrasi</h2>
                    <button type="button" wire:click="addPersyaratanAdministrasi"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                </div>
                <div class="p-6 space-y-3">
                    @forelse ($persyaratanAdministrasis as $index => $pa)
                        <div wire:key="pa-{{ $index }}" class="flex items-start gap-2">
                            <span class="mt-2.5 text-xs font-semibold text-gray-400 w-5 shrink-0">{{ $index + 1 }}.</span>
                            <div class="flex-1">
                                <input type="text" wire:model="persyaratanAdministrasis.{{ $index }}.deskripsi"
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    placeholder="Nama dokumen (misal: Kartu Tanda Penduduk)" />
                                @error("persyaratanAdministrasis.{$index}.deskripsi")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" wire:click="removePersyaratanAdministrasi({{ $index }})"
                                class="mt-2 text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-gray-200 py-8 text-center text-sm text-gray-400">
                            Belum ada persyaratan administrasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] px-6 py-5">
            <a href="{{ route('admin.schemes') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 px-8 py-3 font-bold text-black bg-emerald-400 rounded-xl hover:bg-emerald-500 transition-all">
                <span wire:loading.remove wire:target="save">Simpan Skema</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
                <svg wire:loading.remove wire:target="save"
                    class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7-7 7M3 12h18" />
                </svg>
            </button>
        </div>
    </form>
</div>
