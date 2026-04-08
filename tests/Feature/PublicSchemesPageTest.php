<?php

use App\Livewire\PublicSchemesPage;
use App\Models\Faculty;
use Livewire\Livewire;

it('uses the public layout for the public schemes page', function () {
    $this->get(route('skema.index'))
        ->assertOk()
        ->assertSee('Welcome to UPA LUK')
        ->assertDontSee('Register');
});

it('shows only popular schemes when popular filter is selected on public schemes page', function () {
    $popularScheme = createScheme([
        'nama' => 'Skema Populer',
        'is_active' => true,
        'is_popular' => true,
    ]);

    $regularScheme = createScheme([
        'nama' => 'Skema Biasa',
        'is_active' => true,
        'is_popular' => false,
    ]);

    Livewire::test(PublicSchemesPage::class)
        ->set('sortOption', 'populer')
        ->assertSee($popularScheme->name)
        ->assertDontSee($regularScheme->name)
        ->assertSee('Populer');
});

it('only applies search and dropdown filters after clicking cari', function () {
    $teknikFaculty = Faculty::factory()->create(['name' => 'Teknik']);
    $ekonomiFaculty = Faculty::factory()->create(['name' => 'Ekonomi']);
    $targetScheme = createScheme([
        'nama' => 'Skema Cyber Security',
        'faculty_id' => $teknikFaculty->id,
        'jenis_skema' => 'Okupasi',
        'is_active' => true,
    ]);

    $otherScheme = createScheme([
        'nama' => 'Skema Administrasi Bisnis',
        'faculty_id' => $ekonomiFaculty->id,
        'jenis_skema' => 'Klaster',
        'is_active' => true,
    ]);

    Livewire::test(PublicSchemesPage::class)
        ->set('searchInput', 'Cyber')
        ->set('filterTypeInput', 'Okupasi')
        ->set('filterFacultyInput', (string) $teknikFaculty->id)
        ->assertSee($targetScheme->name)
        ->assertSee($otherScheme->name)
        ->call('applyFilters')
        ->assertSee($targetScheme->name)
        ->assertDontSee($otherScheme->name);
});
