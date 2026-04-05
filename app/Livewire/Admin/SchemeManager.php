<?php

namespace App\Livewire\Admin;

use App\Models\Scheme;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SchemeManager extends Component
{
    public ?int $schemeId = null;

    public string $filterFaculty = '';

    protected $queryString = ['filterFaculty'];

    #[Computed]
    public function groupedSchemes(): Collection
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
    public function availableFaculties(): Collection
    {
        return Scheme::query()
            ->select('faculty')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty');
    }

    public function confirmDelete(int $id): void
    {
        $this->schemeId = $id;
        $this->dispatch('open-modal', name: 'modal-scheme-delete');
    }

    public function toggleActive(int $id): void
    {
        $scheme = Scheme::query()->findOrFail($id);
        $scheme->update([
            'is_active' => ! $scheme->is_active,
        ]);

        unset($this->groupedSchemes);

        $this->dispatch('toast', [
            'message' => $scheme->is_active ? 'Skema berhasil diaktifkan.' : 'Skema berhasil dinonaktifkan.',
            'type' => 'success',
        ]);
    }

    public function togglePopular(int $id): void
    {
        $scheme = Scheme::query()->findOrFail($id);
        $scheme->update([
            'is_popular' => ! $scheme->is_popular,
        ]);

        unset($this->groupedSchemes);

        $this->dispatch('toast', [
            'message' => $scheme->is_popular ? 'Skema ditandai populer.' : 'Status populer skema dihapus.',
            'type' => 'success',
        ]);
    }

    public function delete(): void
    {
        $scheme = Scheme::query()->findOrFail($this->schemeId);
        $scheme->delete();

        $this->schemeId = null;
        $this->dispatch('close-modal', name: 'modal-scheme-delete');
        $this->dispatch('toast', ['message' => 'Skema berhasil dihapus.', 'type' => 'success']);
    }

    public function render(): View
    {
        return view('livewire.admin.scheme-manager');
    }
}
