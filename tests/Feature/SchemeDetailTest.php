<?php

use App\Models\Scheme;

it('renders the scheme detail page with the public navbar', function () {
    $scheme = Scheme::factory()->create([
        'name' => 'Junior Web Developer',
    ]);

    $this->get(route('skema.detail', $scheme))
        ->assertOk()
        ->assertSee('Welcome to UPA LUK')
        ->assertSee('Detail Skema')
        ->assertDontSee('Register');
});
