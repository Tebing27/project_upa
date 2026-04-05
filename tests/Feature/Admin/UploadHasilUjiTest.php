<?php

use App\Livewire\Admin\UploadHasilUji;
use App\Models\AppSetting;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

it('hides sertifikat_terbit registration when a perpanjangan registration exists for the same user and scheme', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $user = User::factory()->create(['name' => 'John Doe']);

    // Original registration
    $reg1 = Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'type' => 'baru',
        'status' => 'sertifikat_terbit',
        'exam_date' => Carbon::parse('2023-01-01 10:00:00'),
    ]);

    // Renewal registration
    $reg2 = Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'type' => 'perpanjangan',
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Perpanjangan',
        'assessor_name' => 'Asesor Perpanjangan',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/perpanjangan');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->assertSee('John Doe')
        ->assertSee('Perpanjangan') // Badge
        ->assertSee('10 Apr 2026') // Date of perpanjangan
        ->assertDontSee('01 Jan 2023'); // Date of old registration shouldn't be visible
});

it('shows Perpanjangan button for perpanjangan registrations instead of Upload Sekarang', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $user1 = User::factory()->create(['name' => 'User Renewal']);
    $user2 = User::factory()->create(['name' => 'User Baru']);

    Registration::factory()->create([
        'user_id' => $user1->id,
        'scheme_id' => $scheme->id,
        'type' => 'perpanjangan',
        'status' => 'terjadwal',
        'exam_date' => Carbon::now(),
        'exam_location' => 'Lab A',
        'assessor_name' => 'Asesor A',
    ]);

    Registration::factory()->create([
        'user_id' => $user2->id,
        'scheme_id' => $scheme->id,
        'type' => 'baru',
        'status' => 'terjadwal',
        'exam_date' => Carbon::now(),
        'exam_location' => 'Lab B',
        'assessor_name' => 'Asesor B',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/a');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->assertSee('User Renewal')
        ->assertSee('bg-blue-500')
        ->assertSee('Perpanjangan')
        ->assertSee('User Baru')
        ->assertSee('bg-emerald-400')
        ->assertSee('Upload Sekarang');
});

it('prevents exam result uploads when a terjadwal registration has no complete schedule data', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();

    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => null,
        'exam_location' => null,
        'assessor_name' => null,
    ]);

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->set('uploadRegistrationId', $registration->id)
        ->call('uploadParticipantFiles')
        ->assertHasErrors(['uploadRegistrationId']);

    expect($registration->refresh()->status)->toBe('terjadwal');
});
