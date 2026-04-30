    <!-- Banner Section -->
    <div class="relative h-[16rem] w-full overflow-hidden bg-slate-950 lg:h-[16rem]">
        <div class="swiper myHeroSwiper h-full w-full">
            <div class="swiper-wrapper">
                @if (isset($homeContent['hero_slides']) && count($homeContent['hero_slides']) > 0)
                    @foreach ($homeContent['hero_slides'] as $slideUrl)
                        <div class="swiper-slide relative h-full w-full">
                            <img src="{{ $slideUrl }}" alt="Hero Slide"
                                class="h-full w-full object-cover object-center">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide relative h-full w-full">
                        <img src="{{ asset('images/hero-upnvj.png') }}" alt="Gedung UPN Veteran Jakarta"
                            class="h-full w-full object-cover object-center">
                    </div>
                    <div class="swiper-slide relative h-full w-full">
                        <img src="{{ asset('assets/background.webp') }}" alt="Kampus UPN Veteran Jakarta"
                            class="h-full w-full object-cover object-center">
                    </div>
                @endif
            </div>
            <!-- Pagination Dots -->
            <div class="swiper-pagination !bottom-8"></div>
        </div>
    </div>

