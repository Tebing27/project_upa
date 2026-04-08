<?php

namespace App\Livewire;

use App\Models\Faculty;
use App\Models\Scheme;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class UserSchemesPage extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'fakultas', except: '')]
    public string $filterFaculty = '';

    #[Url(as: 'filter', except: '')]
    public string $filterPopularity = '';

    /**
     * @return Collection<int, Faculty>
     */
    public function getFaculties(): Collection
    {
        return Faculty::query()
            ->whereHas('studyPrograms.schemes', fn ($q) => $q->where('is_active', true))
            ->orWhereHas('schemes', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        $schemes = Scheme::query()
            ->with(['faculty', 'studyProgram'])
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('nama', 'like', "%{$this->search}%"))
            ->when($this->filterFaculty, fn ($q) => $q->where('faculty_id', $this->filterFaculty))
            ->when($this->filterPopularity === 'populer', fn ($q) => $q->where('is_popular', true))
            ->orderBy('nama')
            ->get();

        return view('livewire.user-schemes-page', [
            'schemes' => $schemes,
            'faculties' => $this->getFaculties(),
        ]);
    }
}
