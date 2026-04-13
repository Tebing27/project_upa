<?php

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', false)
        ->assertSee('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', false)
        ->assertSee('new Swiper(\'.myHeroSwiper\'', false)
        ->assertSee('Uji Kompetensi Terakreditasi')
        ->assertSee('LIHAT SKEMA')
        ->assertSee('https://web.whatsapp.com/send', false)
        ->assertSee('name="phone" value="6287784644193"', false)
        ->assertSee('Halo! Ada yang bisa kami bantu terkait layanan')
        ->assertSee('placeholder="Ketik pesan..."', false)
        ->assertSee('Admin UPA-LUK')
        ->assertDontSee('window.scrollTo({top: 0, behavior: \'smooth\'})', false);
});
