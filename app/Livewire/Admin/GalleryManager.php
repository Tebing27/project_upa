<?php

namespace App\Livewire\Admin;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GalleryManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $activeTab = 'photo';

    public bool $isModalOpen = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $description = '';

    public $fileParams = null;

    public ?string $existingFilePath = null;

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    #[Computed]
    public function galleries()
    {
        return Gallery::query()
            ->where('type', $this->activeTab)
            ->latest()
            ->paginate(12);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $this->resetForm();
        $gallery = Gallery::query()->findOrFail($id);
        $this->editingId = $gallery->id;
        $this->title = $gallery->title;
        $this->description = $gallery->description ?? '';
        $this->existingFilePath = $gallery->file_path;
        $this->isModalOpen = true;
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|string|max:255',
        ];

        if (! $this->editingId) {
            if ($this->activeTab === 'photo') {
                $rules['fileParams'] = 'required|image|max:10240';
            } else {
                $rules['fileParams'] = 'required|mimes:mp4,mov,ogg,qt|max:51200';
            }
        } else {
            if ($this->fileParams) {
                if ($this->activeTab === 'photo') {
                    $rules['fileParams'] = 'image|max:10240';
                } else {
                    $rules['fileParams'] = 'mimes:mp4,mov,ogg,qt|max:51200';
                }
            }
        }

        $this->validate($rules, [
            'title.required' => 'Judul wajib diisi.',
            'fileParams.required' => 'File wajib diunggah.',
            'fileParams.image' => 'File harus berupa gambar.',
            'fileParams.max' => 'Ukuran file terlalu besar.',
            'fileParams.mimes' => 'Format file video tidak didukung.',
        ]);

        $filePath = $this->existingFilePath;

        if ($this->fileParams) {
            if ($this->existingFilePath && Storage::disk('public')->exists($this->existingFilePath)) {
                Storage::disk('public')->delete($this->existingFilePath);
            }

            $folder = $this->activeTab === 'photo' ? 'cms/galleries/photos' : 'cms/galleries/videos';
            $filePath = $this->fileParams->store($folder, 'public');
        }

        if ($this->editingId) {
            Gallery::query()->where('id', $this->editingId)->update([
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
            ]);
            $message = 'Media berhasil diperbarui.';
        } else {
            Gallery::query()->create([
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->activeTab,
                'file_path' => $filePath,
                'created_by' => auth()->id(),
            ]);
            $message = 'Media baru berhasil ditambahkan.';
        }

        $this->isModalOpen = false;
        $this->resetForm();
        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function delete(int $id): void
    {
        $gallery = Gallery::query()->findOrFail($id);

        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();
        $this->dispatch('toast', ['message' => 'Media berhasil dihapus.', 'type' => 'success']);
    }

    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->fileParams = null;
        $this->existingFilePath = null;
    }

    public function render()
    {
        return view('livewire.admin.gallery-manager');
    }
}
