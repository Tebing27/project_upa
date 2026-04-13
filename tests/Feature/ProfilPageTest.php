<?php

it('renders the profil page', function () {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('UPA LUK')
        ->assertSee('Profil')
        ->assertSee('Bagan Susunan Pengurus')
        ->assertSee('images/struktur-organisasi-luk.jpeg', false);
});
