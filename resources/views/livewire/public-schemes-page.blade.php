<div class="min-h-screen bg-[#f8fafc]">
    <x-public.navbar active="skema" />

    @include('livewire.public-schemes-page._hero')

    <main class="px-6 pb-16 lg:px-16">
        <div class="relative z-10 mx-auto -mt-12 w-full max-w-[85rem] space-y-10">
            @include('livewire.public-schemes-page._filters')
            @include('livewire.public-schemes-page._list-header')
            @include('livewire.public-schemes-page._scheme-list')
        </div>
    </main>

    <x-public.footer />
</div>
