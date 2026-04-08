<?php

use App\Livewire\Admin\SchemeForm;
use App\Livewire\Admin\SchemeManager;
use App\Models\Faculty;
use App\Models\Scheme;
use App\Models\StudyProgram;
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
    $scheme = createScheme([
        'nama' => 'Skema Test Edit',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.schemes.edit', $scheme))
        ->assertStatus(200)
        ->assertSeeLivewire(SchemeForm::class);
});

it('can create a new scheme with full details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $faculty = Faculty::factory()->create(['name' => 'Fakultas Teknik']);
    $studyProgram = StudyProgram::factory()->create([
        'faculty_id' => $faculty->id,
        'nama' => 'Sistem Informasi',
    ]);

    Livewire::actingAs($admin)
        ->test(SchemeForm::class)
        ->set('name', 'Skema Uji Komputer Baru')
        ->set('kode_skema', 'SKK-24-10/2024')
        ->set('jenis_skema', 'Okupasi')
        ->set('izin_nirkertas', 'SJJ')
        ->set('harga', '500000')
        ->set('faculty_id', $faculty->id)
        ->set('study_program_id', $studyProgram->id)
        ->set('description', 'Deskripsi Skema')
        ->set('unitKompetensis', [
            ['kode_unit' => 'J.611000.004.01', 'nama_unit' => 'Merancang Pengalamatan Jaringan', 'nama_unit_en' => 'Designing Network Addressing'],
        ])
        ->set('persyaratanDasars', [
            ['deskripsi' => 'Pendidikan minimal kelas 12 SMA/Sederajat'],
        ])
        ->set('persyaratanAdministrasis', [
            ['deskripsi' => 'Kartu Tanda Penduduk (KTP)'],
        ])
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $scheme = Scheme::query()->where('nama', 'Skema Uji Komputer Baru')->first();

    $this->assertDatabaseHas('schemes', [
        'nama' => 'Skema Uji Komputer Baru',
        'kode_skema' => 'SKK-24-10/2024',
        'jenis_skema' => 'Okupasi',
        'izin_nirkertas' => 'SJJ',
        'harga' => 500000.00,
        'faculty_id' => $faculty->id,
        'study_program_id' => $studyProgram->id,
        'is_active' => true,
        'is_popular' => false,
    ]);

    $this->assertDatabaseHas('scheme_unit_kompetensis', [
        'scheme_id' => $scheme->id,
        'kode_unit' => 'J.611000.004.01',
        'nama_unit' => 'Merancang Pengalamatan Jaringan',
    ]);

    $this->assertDatabaseHas('scheme_persyaratan_dasar', [
        'scheme_id' => $scheme->id,
        'deskripsi' => 'Pendidikan minimal kelas 12 SMA/Sederajat',
    ]);

    $this->assertDatabaseHas('scheme_persyaratan_administrasis', [
        'scheme_id' => $scheme->id,
        'deskripsi' => 'Kartu Tanda Penduduk (KTP)',
    ]);
});

it('can update an existing scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = createScheme([
        'nama' => 'Skema Lama',
        'deskripsi' => 'Deskripsi Lama',
        'is_active' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(SchemeForm::class, ['scheme' => $scheme])
        ->assertSet('name', 'Skema Lama')
        ->set('name', 'Skema Diupdate')
        ->call('save')
        ->assertDispatched('toast');

    $this->assertDatabaseHas('schemes', [
        'id' => $scheme->id,
        'nama' => 'Skema Diupdate',
        'is_active' => false,
    ]);
});

it('can toggle active and popular states from the scheme manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create([
        'is_active' => true,
        'is_popular' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(SchemeManager::class)
        ->call('toggleActive', $scheme->id)
        ->call('togglePopular', $scheme->id)
        ->assertDispatched('toast');

    $this->assertDatabaseHas('schemes', [
        'id' => $scheme->id,
        'is_active' => false,
        'is_popular' => true,
    ]);
});

it('can delete a scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = createScheme([
        'nama' => 'Skema Untuk Dihapus',
        'deskripsi' => 'Akan dhapus',
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
        ->assertHasErrors(['name', 'faculty_id']);
});
