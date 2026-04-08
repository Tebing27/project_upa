<?php

namespace App\Livewire;

use App\Models\Faculty;
use App\Models\Scheme;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.public')]
class PublicSchemesPage extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public string $searchInput = '';

    #[Url(as: 'jenis', except: '')]
    public string $filterType = '';

    public string $filterTypeInput = '';

    #[Url(as: 'fakultas', except: '')]
    public string $filterFaculty = '';

    public string $filterFacultyInput = '';

    #[Url(as: 'filter', except: 'terbaru')]
    public string $sortOption = 'terbaru';

    public function mount(): void
    {
        $this->searchInput = $this->search;
        $this->filterTypeInput = $this->filterType;
        $this->filterFacultyInput = $this->filterFaculty;
    }

    public function applyFilters(): void
    {
        $this->search = trim($this->searchInput);
        $this->filterType = $this->filterTypeInput;
        $this->filterFaculty = $this->filterFacultyInput;
    }

    /**
     * @return Collection<int, Faculty>
     */
    public function getFaculties(): Collection
    {
        return Faculty::query()
            ->whereHas('schemes', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, string>
     */
    public function getJenisSkemas(): Collection
    {
        return Scheme::query()
            ->where('is_active', true)
            ->whereNotNull('jenis_skema')
            ->distinct()
            ->pluck('jenis_skema')
            ->sort()
            ->values();
    }

    public function render(): View
    {
        $query = Scheme::query()
            ->with(['faculty', 'studyProgram'])
            ->where('is_active', true);

        if ($this->search) {
            $query->where('nama', 'like', "%{$this->search}%");
        }

        if ($this->filterType) {
            $query->where('jenis_skema', $this->filterType);
        }

        if ($this->filterFaculty) {
            $query->where('faculty_id', $this->filterFaculty);
        }

        if ($this->sortOption === 'populer') {
            $query->where('is_popular', true)->latest();
        } elseif ($this->sortOption === 'terbaru') {
            $query->latest();
        } else {
            $query->orderBy('nama');
        }

        $schemes = $query->get();

        return view('livewire.public-schemes-page', [
            'schemes' => $schemes,
            'faculties' => $this->getFaculties(),
            'jenisSkemas' => $this->getJenisSkemas(),
        ]);
    }
}
