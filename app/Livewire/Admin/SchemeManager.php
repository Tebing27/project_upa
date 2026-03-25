<?php

namespace App\Livewire\Admin;

use App\Models\Scheme;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SchemeManager extends Component
{
    // Form fields
    public $schemeId;

    public $name = '';

    public $faculty = '';

    public $study_program = '';

    public $description = '';

    public $is_active = true;

    public $filterFaculty = '';

    protected $queryString = ['filterFaculty'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    #[Computed]
    public function groupedSchemes()
    {
        $query = Scheme::query();

        if ($this->filterFaculty) {
            $query->where('faculty', $this->filterFaculty);
        }

        return $query->orderBy('faculty')
            ->orderBy('study_program')
            ->orderBy('name')
            ->get()
            ->groupBy(['faculty', 'study_program']);
    }

    #[Computed]
    public function availableFaculties()
    {
        return Scheme::query()
            ->select('faculty')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty');
    }

    public function create()
    {
        $this->resetFields();
        // Default to active for new schemes
        $this->is_active = true;
        $this->dispatch('open-modal', name: 'modal-scheme-form');
    }

    public function edit($id)
    {
        $this->resetFields();
        $scheme = Scheme::findOrFail($id);

        $this->schemeId = $scheme->id;
        $this->name = $scheme->name;
        $this->faculty = $scheme->faculty;
        $this->study_program = $scheme->study_program;
        $this->description = $scheme->description;
        $this->is_active = $scheme->is_active;

        $this->dispatch('open-modal', name: 'modal-scheme-form');
    }

    public function save()
    {
        $this->validate();

        if ($this->schemeId) {
            $scheme = Scheme::findOrFail($this->schemeId);
            $scheme->update([
                'name' => $this->name,
                'faculty' => $this->faculty,
                'study_program' => $this->study_program,
                'description' => $this->description,
                'is_active' => $this->is_active ?? false,
            ]);
            $this->dispatch('toast', ['message' => 'Skema berhasil diperbarui.', 'type' => 'success']);
        } else {
            Scheme::create([
                'name' => $this->name,
                'faculty' => $this->faculty,
                'study_program' => $this->study_program,
                'description' => $this->description,
                'is_active' => $this->is_active ?? false,
            ]);
            $this->dispatch('toast', ['message' => 'Skema baru berhasil ditambahkan.', 'type' => 'success']);
        }

        $this->dispatch('close-modal', name: 'modal-scheme-form');
    }

    public function confirmDelete($id)
    {
        $this->schemeId = $id;
        $this->dispatch('open-modal', name: 'modal-scheme-delete');
    }

    public function delete()
    {
        $scheme = Scheme::findOrFail($this->schemeId);
        $scheme->delete();

        $this->dispatch('close-modal', name: 'modal-scheme-delete');
        $this->dispatch('toast', ['message' => 'Skema berhasil dihapus.', 'type' => 'success']);
    }

    public function resetFields()
    {
        $this->reset(['schemeId', 'name', 'faculty', 'study_program', 'description', 'is_active']);
    }

    public function render()
    {
        return view('livewire.admin.scheme-manager');
    }
}
