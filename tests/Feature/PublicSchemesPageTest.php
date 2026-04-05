<?php

use App\Livewire\PublicSchemesPage;
use App\Models\Scheme;
use Livewire\Livewire;

it('uses the public layout for the public schemes page', function () {
    $this->get(route('skema.index'))
        ->assertOk()
        ->assertSee('Welcome to UPA LUK')
        ->assertDontSee('Register');
});

it('shows only popular schemes when popular filter is selected on public schemes page', function () {
    $popularScheme = Scheme::factory()->create([
        'name' => 'Skema Populer',
        'is_active' => true,
        'is_popular' => true,
    ]);

    $regularScheme = Scheme::factory()->create([
        'name' => 'Skema Biasa',
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
    $targetScheme = Scheme::factory()->create([
        'name' => 'Skema Cyber Security',
        'faculty' => 'Teknik',
        'jenis_skema' => 'Okupasi',
        'is_active' => true,
    ]);

    $otherScheme = Scheme::factory()->create([
        'name' => 'Skema Administrasi Bisnis',
        'faculty' => 'Ekonomi',
        'jenis_skema' => 'Klaster',
        'is_active' => true,
    ]);

    Livewire::test(PublicSchemesPage::class)
        ->set('searchInput', 'Cyber')
        ->set('filterTypeInput', 'Okupasi')
        ->set('filterFacultyInput', 'Teknik')
        ->assertSee($targetScheme->name)
        ->assertSee($otherScheme->name)
        ->call('applyFilters')
        ->assertSee($targetScheme->name)
        ->assertDontSee($otherScheme->name);
});
