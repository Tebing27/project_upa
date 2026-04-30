    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Thumbnails
            const thumbsSwiper = new Swiper('.myThumbsSwiper', {
                direction: 'vertical',
                spaceBetween: 20,
                slidesPerView: 'auto',
                freeMode: {
                    enabled: true,
                    sticky: false,
                },
                grabCursor: true,
                mousewheel: {
                    forceToAxis: true,
                },
                watchSlidesProgress: true,
            });

            // Initialize Main Hero
            new Swiper('.myHeroSwiper', {
                loop: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });

        // Ensure Alpine.js knows about the scroll position on load
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('scroll'));
        });
    </script>
