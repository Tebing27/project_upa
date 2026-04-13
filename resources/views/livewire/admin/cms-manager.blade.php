<div class="space-y-6">
    <!-- Header -->
    @include('livewire.admin.cms-manager._header')

    <!-- Navigation Tabs -->
    @include('livewire.admin.cms-manager._tabs')

    <div class="space-y-10">
        @if ($pageType === 'artikel' && !$isCreatingPage && !$articleId)
            <!-- Artikel List View -->
            @include('livewire.admin.cms-manager._artikel-list')
        @elseif ($pageType === 'artikel')
            <!-- Unified Artikel Editor Form (Title, Editor, Meta, Baca Juga) -->
            @include('livewire.admin.cms-manager._artikel-editor')
        @elseif ($cmsTab === 'gallery')
            <!-- Gallery Manager View -->
            @livewire('admin.gallery-manager')

        @else
            <!-- Page Settings Form -->
            @include('livewire.admin.cms-manager._page-form')

            @if (!$isCreatingPage)
                <!-- Page Fields Form (skema baru) -->
                @include('livewire.admin.cms-manager._page-fields-form')
            @endif
        @endif
    </div>

    <!-- Quill Editor Assets & Script -->
    @include('livewire.admin.cms-manager._quill-assets')

</div>
