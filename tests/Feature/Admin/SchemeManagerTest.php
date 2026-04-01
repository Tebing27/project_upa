<?php

use App\Livewire\Admin\SchemeForm;
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

it('renders the scheme create page for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.schemes.create'))
        ->assertStatus(200)
        ->assertSeeLivewire(SchemeForm::class);
});

it('renders the scheme edit page for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::create([
        'name' => 'Skema Test Edit',
        'faculty' => 'Fakultas Test',
        'study_program' => 'Prodi Test',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.schemes.edit', $scheme))
        ->assertStatus(200)
        ->assertSeeLivewire(SchemeForm::class);
});

it('can create a new scheme with full details', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(SchemeForm::class)
        ->set('name', 'Skema Uji Komputer Baru')
        ->set('kode_skema', 'SKK-24-10/2024')
        ->set('jenis_skema', 'Okupasi')
        ->set('izin_nirkertas', 'SJJ')
        ->set('harga', '500000')
        ->set('faculty', 'Fakultas Teknik')
        ->set('study_program', 'Sistem Informasi')
        ->set('description', 'Deskripsi Skema')
        ->set('is_active', true)
        ->set('unitKompetensis', [
            ['kode_unit' => 'J.611000.004.01', 'nama_unit' => 'Merancang Pengalamatan Jaringan', 'nama_unit_en' => 'Designing Network Addressing'],
        ])
        ->set('persyaratanDasars', [
            ['deskripsi' => 'Pendidikan minimal kelas 12 SMA/Sederajat'],
        ])
        ->set('persyaratanAdministrasis', [
            ['nama_dokumen' => 'Kartu Tanda Penduduk (KTP)'],
        ])
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $scheme = Scheme::where('name', 'Skema Uji Komputer Baru')->first();

    $this->assertDatabaseHas('schemes', [
        'name' => 'Skema Uji Komputer Baru',
        'kode_skema' => 'SKK-24-10/2024',
        'jenis_skema' => 'Okupasi',
        'izin_nirkertas' => 'SJJ',
        'harga' => 500000.00,
        'faculty' => 'Fakultas Teknik',
        'study_program' => 'Sistem Informasi',
        'is_active' => true,
    ]);

    $this->assertDatabaseHas('scheme_unit_kompetensis', [
        'scheme_id' => $scheme->id,
        'kode_unit' => 'J.611000.004.01',
        'nama_unit' => 'Merancang Pengalamatan Jaringan',
    ]);

    $this->assertDatabaseHas('scheme_persyaratan_dasars', [
        'scheme_id' => $scheme->id,
        'deskripsi' => 'Pendidikan minimal kelas 12 SMA/Sederajat',
    ]);

    $this->assertDatabaseHas('scheme_persyaratan_administrasis', [
        'scheme_id' => $scheme->id,
        'nama_dokumen' => 'Kartu Tanda Penduduk (KTP)',
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
        ->test(SchemeForm::class, ['scheme' => $scheme])
        ->assertSet('name', 'Skema Lama')
        ->set('name', 'Skema Diupdate')
        ->set('is_active', true)
        ->call('save')
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

it('validates required fields on the form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(SchemeForm::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name', 'faculty', 'study_program']);
});
