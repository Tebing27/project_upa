<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    @include('livewire.admin.dashboard._header')
    @include('livewire.admin.dashboard._stats-cards')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @include('livewire.admin.dashboard._recent-registrations')
        @include('livewire.admin.dashboard._upcoming-schedules')
    </div>
</div>
