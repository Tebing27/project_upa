<?php

namespace App\Livewire;

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
     * @return Collection<int, string>
     */
    public function getFaculties(): Collection
    {
        return Scheme::query()
            ->where('is_active', true)
            ->whereNotNull('faculty')
            ->distinct()
            ->pluck('faculty')
            ->sort()
            ->values();
    }

    public function render(): View
    {
        $schemes = Scheme::query()
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterFaculty, fn ($q) => $q->where('faculty', $this->filterFaculty))
            ->when($this->filterPopularity === 'populer', fn ($q) => $q->where('is_popular', true))
            ->orderBy('name')
            ->get();

        return view('livewire.user-schemes-page', [
            'schemes' => $schemes,
            'faculties' => $this->getFaculties(),
        ]);
    }
}
