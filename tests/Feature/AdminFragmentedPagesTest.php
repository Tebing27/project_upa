<?php

use App\Models\User;

it('renders fragmented admin Livewire pages', function (string $routeName, string $heading) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee($heading);
})->with([
    'scheme form' => ['admin.schemes.create', 'Tambah Skema Baru'],
    'jadwal uji' => ['admin.jadwal', 'Jadwal Uji'],
    'upload hasil uji' => ['admin.hasil-uji', 'Upload Hasil Uji'],
]);
