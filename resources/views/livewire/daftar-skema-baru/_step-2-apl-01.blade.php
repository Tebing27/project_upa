<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    @include('livewire.daftar-skema-baru._apl-01-stepper')

    @if ($apl01SubStep === 1)
        @include('livewire.daftar-skema-baru._apl-01-applicant-data')
    @endif

    @if ($apl01SubStep === 2)
        @include('livewire.daftar-skema-baru._apl-01-certification-data')
    @endif

    @if ($apl01SubStep === 3)
        @include('livewire.daftar-skema-baru._apl-01-documents')
    @endif

    @if ($apl01SubStep === 4)
        @include('livewire.daftar-skema-baru._apl-01-recommendation-signature')
    @endif
</div>
