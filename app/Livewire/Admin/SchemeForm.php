<?php

namespace App\Livewire\Admin;

use App\Models\Faculty;
use App\Models\Scheme;
use App\Models\StudyProgram;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class SchemeForm extends Component
{
    use WithFileUploads;

    public ?int $schemeId = null;

    public string $name = '';

    public string $kode_skema = '';

    public string $jenis_skema = '';

    public string $izin_nirkertas = '';

    public string $harga = '';

    public string $ringkasan_skema = '';

    public ?int $faculty_id = null;

    public ?int $study_program_id = null;

    public string $description = '';

    /** @var TemporaryUploadedFile|null */
    public $gambar;

    /** @var TemporaryUploadedFile|null */
    public $dokumen_skema;

    public string $newFacultyName = '';

    public string $newStudyProgramName = '';

    public ?string $existingGambarUrl = null;

    public ?string $existingDokumenUrl = null;

    public string $activeTab = 'info';

    /**
     * @var array<int, array{kode_unit: string, nama_unit: string, nama_unit_en: string}>
     */
    public array $unitKompetensis = [];

    /**
     * @var array<int, array{deskripsi: string}>
     */
    public array $persyaratanDasars = [];

    /**
     * @var array<int, array{deskripsi: string}>
     */
    public array $persyaratanAdministrasis = [];

    public function mount(?Scheme $scheme = null): void
    {
        if ($scheme && $scheme->exists) {
            $scheme->load(['unitKompetensis', 'persyaratanDasars', 'persyaratanAdministrasis']);

            $this->schemeId = $scheme->id;
            $this->name = $scheme->nama;
            $this->kode_skema = $scheme->kode_skema ?? '';
            $this->jenis_skema = $scheme->jenis_skema ?? '';
            $this->izin_nirkertas = $scheme->izin_nirkertas ?? '';
            $this->harga = $scheme->harga ? (string) $scheme->harga : '';
            $this->ringkasan_skema = $scheme->ringkasan_skema ?? '';
            $this->faculty_id = $scheme->faculty_id;
            $this->study_program_id = $scheme->study_program_id;
            $this->description = $scheme->deskripsi ?? '';
            $this->existingGambarUrl = $scheme->gambar_path ? Storage::url($scheme->gambar_path) : null;
            $this->existingDokumenUrl = $scheme->dokumen_skema_path ? Storage::url($scheme->dokumen_skema_path) : null;
            $this->unitKompetensis = $scheme->unitKompetensis->map(fn ($uk) => [
                'kode_unit' => $uk->kode_unit,
                'nama_unit' => $uk->nama_unit,
                'nama_unit_en' => $uk->nama_unit_en ?? '',
            ])->toArray();

            $this->persyaratanDasars = $scheme->persyaratanDasars->map(fn ($pd) => [
                'deskripsi' => $pd->deskripsi,
            ])->toArray();

            $this->persyaratanAdministrasis = $scheme->persyaratanAdministrasis->map(fn ($pa) => [
                'deskripsi' => $pa->deskripsi,
            ])->toArray();
        }
    }

    #[Computed]
    public function faculties(): Collection
    {
        return Faculty::query()->orderBy('name')->get();
    }

    #[Computed]
    public function studyPrograms(): Collection
    {
        if (! $this->faculty_id) {
            return collect();
        }

        return StudyProgram::query()
            ->where('faculty_id', $this->faculty_id)
            ->orderBy('nama')
            ->get();
    }

    public function updatedFacultyId(): void
    {
        $this->study_program_id = null;
    }

    public function createFaculty(): void
    {
        $validated = $this->validate([
            'newFacultyName' => 'required|string|max:255',
        ], [
            'newFacultyName.required' => 'Nama fakultas wajib diisi.',
        ]);

        $faculty = Faculty::query()->firstOrCreate([
            'name' => trim($validated['newFacultyName']),
        ]);

        $this->faculty_id = $faculty->id;
        $this->study_program_id = null;
        $this->newFacultyName = '';

        unset($this->faculties, $this->studyPrograms);
    }

    public function createStudyProgram(): void
    {
        $validated = $this->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'newStudyProgramName' => 'required|string|max:255',
        ], [
            'faculty_id.required' => 'Pilih fakultas sebelum menambah program studi.',
            'newStudyProgramName.required' => 'Nama program studi wajib diisi.',
        ]);

        $studyProgram = StudyProgram::query()->firstOrCreate([
            'faculty_id' => $validated['faculty_id'],
            'nama' => trim($validated['newStudyProgramName']),
        ]);

        $this->study_program_id = $studyProgram->id;
        $this->newStudyProgramName = '';

        unset($this->studyPrograms);
    }

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'kode_skema' => 'nullable|string|max:255',
            'jenis_skema' => 'nullable|string|max:255',
            'izin_nirkertas' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'ringkasan_skema' => 'nullable|string',
            'faculty_id' => 'required|exists:faculties,id',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'description' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dokumen_skema' => 'nullable|file|mimes:pdf|max:4096',
            'unitKompetensis.*.kode_unit' => 'required|string|max:255',
            'unitKompetensis.*.nama_unit' => 'required|string|max:255',
            'unitKompetensis.*.nama_unit_en' => 'nullable|string|max:255',
            'persyaratanDasars.*.deskripsi' => 'required|string',
            'persyaratanAdministrasis.*.deskripsi' => 'required|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists' => 'Fakultas tidak valid.',
            'unitKompetensis.*.kode_unit.required' => 'Kode unit wajib diisi.',
            'unitKompetensis.*.nama_unit.required' => 'Nama unit wajib diisi.',
            'persyaratanDasars.*.deskripsi.required' => 'Deskripsi persyaratan wajib diisi.',
            'persyaratanAdministrasis.*.deskripsi.required' => 'Nama dokumen wajib diisi.',
        ];
    }

    public function addUnitKompetensi(): void
    {
        $this->unitKompetensis[] = ['kode_unit' => '', 'nama_unit' => '', 'nama_unit_en' => ''];
    }

    public function removeUnitKompetensi(int $index): void
    {
        unset($this->unitKompetensis[$index]);
        $this->unitKompetensis = array_values($this->unitKompetensis);
    }

    public function addPersyaratanDasar(): void
    {
        $this->persyaratanDasars[] = ['deskripsi' => ''];
    }

    public function removePersyaratanDasar(int $index): void
    {
        unset($this->persyaratanDasars[$index]);
        $this->persyaratanDasars = array_values($this->persyaratanDasars);
    }

    public function addPersyaratanAdministrasi(): void
    {
        $this->persyaratanAdministrasis[] = ['deskripsi' => ''];
    }

    public function removePersyaratanAdministrasi(int $index): void
    {
        unset($this->persyaratanAdministrasis[$index]);
        $this->persyaratanAdministrasis = array_values($this->persyaratanAdministrasis);
    }

    public function save(): void
    {
        $this->validate();

        $gambarPath = null;
        $dokumenPath = null;

        if ($this->gambar) {
            $gambarPath = $this->gambar->store('schemes/images', 'public');
        }

        if ($this->dokumen_skema) {
            $dokumenPath = $this->dokumen_skema->store('schemes/documents', 'public');
        }

        $data = [
            'nama' => $this->name,
            'kode_skema' => $this->kode_skema ?: null,
            'jenis_skema' => $this->jenis_skema ?: null,
            'izin_nirkertas' => $this->izin_nirkertas ?: null,
            'harga' => $this->harga !== '' ? (float) $this->harga : null,
            'ringkasan_skema' => $this->ringkasan_skema ?: null,
            'faculty_id' => $this->faculty_id,
            'study_program_id' => $this->study_program_id,
            'deskripsi' => $this->description ?: null,
        ];

        if ($gambarPath) {
            $data['gambar_path'] = $gambarPath;
        }

        if ($dokumenPath) {
            $data['dokumen_skema_path'] = $dokumenPath;
        }

        if ($this->schemeId) {
            $scheme = Scheme::query()->findOrFail($this->schemeId);
            $scheme->update($data);
            $message = 'Skema berhasil diperbarui.';
        } else {
            $scheme = Scheme::query()->create($data);
            $message = 'Skema baru berhasil ditambahkan.';
        }

        $this->saveRelatedData($scheme);

        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);

        $this->redirectRoute('admin.schemes', navigate: true);
    }

    private function saveRelatedData(Scheme $scheme): void
    {
        $scheme->unitKompetensis()->delete();
        foreach ($this->unitKompetensis as $index => $unit) {
            if (! empty($unit['kode_unit']) && ! empty($unit['nama_unit'])) {
                $scheme->unitKompetensis()->create([
                    'kode_unit' => $unit['kode_unit'],
                    'nama_unit' => $unit['nama_unit'],
                    'nama_unit_en' => $unit['nama_unit_en'] ?: null,
                    'order' => $index + 1,
                ]);
            }
        }

        $scheme->persyaratanDasars()->delete();
        foreach ($this->persyaratanDasars as $index => $persyaratan) {
            if (! empty($persyaratan['deskripsi'])) {
                $scheme->persyaratanDasars()->create([
                    'deskripsi' => $persyaratan['deskripsi'],
                    'order' => $index + 1,
                ]);
            }
        }

        $scheme->persyaratanAdministrasis()->delete();
        foreach ($this->persyaratanAdministrasis as $index => $persyaratan) {
            if (! empty($persyaratan['deskripsi'])) {
                $scheme->persyaratanAdministrasis()->create([
                    'deskripsi' => $persyaratan['deskripsi'],
                    'order' => $index + 1,
                ]);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.scheme-form');
    }
}
