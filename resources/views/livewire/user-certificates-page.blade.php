<div class="space-y-8 bg-slate-50/50 p-6 min-h-screen lg:px-8" x-data="{ certificateMissingOpen: false }"
    x-on:certificate-missing.window="certificateMissingOpen = true">
    @php
        $downloadUrl = $activeCertificate?->file_path ? Storage::url($activeCertificate->file_path) : null;
        $resultDownloadUrl = $activeCertificate?->result_file_path
            ? Storage::url($activeCertificate->result_file_path)
            : null;
    @endphp

    @include('livewire.user-certificates-page._header')
    @include('livewire.user-certificates-page._pending-competency')

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        @include('livewire.user-certificates-page._filters')
        @include('livewire.user-certificates-page._desktop-table')
        @include('livewire.user-certificates-page._mobile-cards')
    </div>

    @include('livewire.user-certificates-page._certificate-missing-modal')
</div>
