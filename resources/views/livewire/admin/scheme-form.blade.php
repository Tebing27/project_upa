<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.schemes') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2.5 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ $schemeId ? 'Edit Skema' : 'Tambah Skema Baru' }}
                </h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ $schemeId ? 'Perbarui informasi skema sertifikasi.' : 'Isi formulir berikut untuk menambahkan skema baru.' }}
                </p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6"
        x-data="{
            imagePreviewUrl: null,
            pdfPreviewUrl: null,
            apl02PreviewUrl: null,
            setImagePreview(event) {
                const [file] = event.target.files ?? [];
                this.imagePreviewUrl = file ? URL.createObjectURL(file) : null;
            },
            setPdfPreview(event) {
                const [file] = event.target.files ?? [];
                this.pdfPreviewUrl = file ? URL.createObjectURL(file) : null;
            },
            setApl02Preview(event) {
                const [file] = event.target.files ?? [];
                this.apl02PreviewUrl = file ? URL.createObjectURL(file) : null;
            },
        }">
        @include('livewire.admin.scheme-form._tabs')

        @if ($activeTab === 'basic')
            @include('livewire.admin.scheme-form._basic-info-tab')
        @elseif ($activeTab === 'units')
            @include('livewire.admin.scheme-form._unit-kompetensi-tab')
        @elseif ($activeTab === 'requirements')
            @include('livewire.admin.scheme-form._requirements-tab')
        @endif

        @include('livewire.admin.scheme-form._actions')
    </form>
</div>
