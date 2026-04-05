<?php

it('renders the contact page with key contact information', function () {
    $this->get(route('kontak'))
        ->assertOk()
        ->assertSee('Welcome to UPA LUK')
        ->assertSee('Kontak')
        ->assertSee('Address')
        ->assertSee('Email Address')
        ->assertSee('Phone Number')
        ->assertSee('Jalan RS. Fatmawati Raya')
        ->assertSee('lsp@upnvj.ac.id')
        ->assertSee('+62 812-8028-0908');
});
