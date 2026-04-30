@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollToActiveStep = () => {
                const container = document.getElementById('stepper-container');
                const activeItem = document.getElementById('active-step-item');

                if (container && activeItem) {
                    const scrollPosition = activeItem.offsetLeft - (container.clientWidth / 2) + (activeItem
                        .clientWidth / 2);
                    container.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            };

            setTimeout(scrollToActiveStep, 100);

            Livewire.hook('morph.updated', () => {
                setTimeout(scrollToActiveStep, 100);
            });
        });
    </script>
@endscript
