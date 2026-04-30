<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    @include('livewire.admin.detail-dokumen._header')

    <div class="space-y-6">
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            @include('livewire.admin.detail-dokumen._registration-summary')
            @include('livewire.admin.detail-dokumen._biodata-section')
            @include('livewire.admin.detail-dokumen._documents-section')
            @include('livewire.admin.detail-dokumen._signatures-section')
        </div>
    </div>

    @include('livewire.admin.detail-dokumen._reject-modal')
</div>
