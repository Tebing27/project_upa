<?php

use App\Livewire\Admin\DetailPembayaran;
use App\Livewire\Admin\VerifikasiPembayaran;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Livewire\Livewire;

it('shows pending payment registrations in the payment verification page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create(['name' => 'Peserta Bayar']);

    Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ok',
        'payment_reference' => 'PAY-001',
    ]);

    Livewire::actingAs($admin)
        ->test(VerifikasiPembayaran::class)
        ->assertSee('Peserta Bayar')
        ->assertSee('PAY-001')
        ->assertSee('Belum upload bukti');
});

it('can verify uploaded payment proof and move registration to paid', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'pending_payment',
        'payment_proof_path' => 'payments/proofs/test.pdf',
        'document_statuses' => [
            'payment_proof_path' => [
                'status' => 'pending',
                'note' => null,
            ],
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(DetailPembayaran::class, ['registration' => $registration])
        ->call('verifikasiPembayaran');

    $registration->refresh();

    expect($registration->status)->toBe('paid')
        ->and($registration->document_statuses['payment_proof_path']['status'])->toBe('verified')
        ->and($registration->payment_verified_at)->not->toBeNull();
});
