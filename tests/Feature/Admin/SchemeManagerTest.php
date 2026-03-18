<?php

use App\Livewire\Admin\SchemeManager;
use App\Models\Scheme;
use App\Models\User;
use Livewire\Livewire;

it('renders the scheme manager component successfully for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.schemes'))
        ->assertStatus(200)
        ->assertSeeLivewire(SchemeManager::class);
});

it('cannot be accessed by common user', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get(route('admin.schemes'))
        ->assertStatus(403);
});

it('can create a new scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(SchemeManager::class)
        ->set('name', 'Skema Uji Komputer Baru')
        ->set('faculty', 'Fakultas Teknik')
        ->set('study_program', 'Sistem Informasi')
        ->set('description', 'Deskripsi Skema')
        ->set('is_active', true)
        ->call('save')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $this->assertDatabaseHas('schemes', [
        'name' => 'Skema Uji Komputer Baru',
        'faculty' => 'Fakultas Teknik',
        'study_program' => 'Sistem Informasi',
        'is_active' => true,
    ]);
});

it('can update an existing scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::create([
        'name' => 'Skema Lama',
        'faculty' => 'Fakultas Lama',
        'study_program' => 'Prodi Lama',
        'description' => 'Deskripsi Lama',
        'is_active' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(SchemeManager::class)
        ->call('edit', $scheme->id)
        ->assertSet('name', 'Skema Lama')
        ->set('name', 'Skema Diupdate')
        ->set('is_active', true)
        ->call('save')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $this->assertDatabaseHas('schemes', [
        'id' => $scheme->id,
        'name' => 'Skema Diupdate',
        'is_active' => true,
    ]);
});

it('can delete a scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::create([
        'name' => 'Skema Untuk Dihapus',
        'faculty' => 'Fakultas Hapus',
        'study_program' => 'Prodi Hapus',
        'description' => 'Akan dhapus',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(SchemeManager::class)
        ->call('confirmDelete', $scheme->id)
        ->assertSet('schemeId', $scheme->id)
        ->call('delete')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $this->assertDatabaseMissing('schemes', [
        'id' => $scheme->id,
    ]);
});

it('validates required fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(SchemeManager::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name', 'faculty', 'study_program']);
});
