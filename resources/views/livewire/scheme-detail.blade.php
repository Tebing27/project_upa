<div class="min-h-screen bg-slate-50/50">
    <x-public.navbar active="skema" sticky="true" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        @include('livewire.scheme-detail._breadcrumb')
        @include('livewire.scheme-detail._main-card')
        @include('livewire.scheme-detail._tabs')
    </div>
</div>
