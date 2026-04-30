<div class="min-h-screen space-y-6 bg-slate-50/50 p-6">
    <div class="mx-auto max-w-4xl">
        @include('livewire.daftar-skema-baru._page-header')

        @if ($errorMessage)
            @include('livewire.daftar-skema-baru._error-state')
        @else
            @php
                $visibleStepKeys = array_keys($steps);
                $currentVisibleIndex = array_search($currentStep, $visibleStepKeys, true);
                $currentVisibleIndex =
                    $currentVisibleIndex === false ? count($visibleStepKeys) : $currentVisibleIndex + 1;
                $progressWidth =
                    count($visibleStepKeys) > 1
                        ? (($currentVisibleIndex - 1) / (count($visibleStepKeys) - 1)) * 100
                        : 0;
            @endphp

            @include('livewire.daftar-skema-baru._progress-stepper')

            <div class="mt-16" x-data="{ showConfirm: false }">
                @if ($currentStep === 1)
                    @include('livewire.daftar-skema-baru._step-1')
                @endif

                @if ($currentStep === 2)
                    @include('livewire.daftar-skema-baru._step-2-apl-01')
                @endif

                @if ($currentStep === 3)
                    @include('livewire.daftar-skema-baru._step-3-apl-02')
                @endif

                @if ($currentStep === 4)
                    @include('livewire.daftar-skema-baru._step-4-review')
                @endif

                @if ($currentStep === 5)
                    @include('livewire.daftar-skema-baru._step-5-success')
                @endif
            </div>
        @endif
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
        <style>
            .cropper-view-box,
            .cropper-face {
                border-radius: 4px;
            }

            .aspect-3-4 {
                aspect-ratio: 3 / 4;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    @endpush
@endonce
