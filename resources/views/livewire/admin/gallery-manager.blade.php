<div class="space-y-6">
    <!-- Sub Tabs -->
    <div class="flex items-center justify-between">
        <div class="flex gap-2 rounded-xl bg-white p-1 shadow-sm border border-slate-100">
            <button wire:click="switchTab('photo')" 
                @class([
                    'px-4 py-2 rounded-lg text-sm font-semibold transition-colors',
                    'bg-slate-900 text-white shadow' => $activeTab === 'photo',
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100' => $activeTab !== 'photo'
                ])>
                Photo
            </button>
            <button wire:click="switchTab('video')" 
                @class([
                    'px-4 py-2 rounded-lg text-sm font-semibold transition-colors',
                    'bg-slate-900 text-white shadow' => $activeTab === 'video',
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100' => $activeTab !== 'video'
                ])>
                Video
            </button>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah {{ ucfirst($activeTab) }}
        </button>
    </div>

    <!-- Data Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($this->galleries as $gallery)
            <div class="group relative overflow-hidden rounded-xl bg-slate-900 aspect-[4/3] border border-slate-200 shadow-sm">
                @if($gallery->type === 'photo')
                    <img src="{{ Storage::url($gallery->file_path) }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                    <video src="{{ Storage::url($gallery->file_path) }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                @endif
                
                <div class="absolute inset-0 flex flex-col justify-between bg-gradient-to-t from-black/80 via-black/20 to-transparent p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <div class="flex justify-end gap-2">
                        <button wire:click="edit({{ $gallery->id }})" class="rounded-lg bg-white/20 p-2 text-white backdrop-blur-md hover:bg-white/40 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $gallery->id }})" wire:confirm="Yakin ingin menghapus media ini?" class="rounded-lg bg-red-500/80 p-2 text-white backdrop-blur-md hover:bg-red-600 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white line-clamp-1 mb-1">{{ $gallery->title }}</h4>
                        @if($gallery->description)
                            <p class="text-xs text-slate-300 line-clamp-2">{{ $gallery->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200">
                Belum ada {{ $activeTab }} di galeri.
            </div>
        @endforelse
    </div>

    @if($this->galleries->hasPages())
        <div class="mt-6">
            {{ $this->galleries->links() }}
        </div>
    @endif

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('isModalOpen', false)"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-900">{{ $editingId ? 'Edit' : 'Tambah' }} {{ ucfirst($activeTab) }}</h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-slate-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul</label>
                        <input type="text" wire:model="title" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-slate-400 focus:ring-0">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-slate-400 focus:ring-0"></textarea>
                        @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Upload File {{ ucfirst($activeTab) }}</label>
                        @if($existingFilePath && !$fileParams)
                            <div class="mb-2 w-full max-w-[200px] overflow-hidden rounded-lg border border-slate-200">
                                @if($activeTab === 'photo')
                                    <img src="{{ Storage::url($existingFilePath) }}" class="w-full h-auto">
                                @else
                                    <video src="{{ Storage::url($existingFilePath) }}" class="w-full h-auto" controls></video>
                                @endif
                            </div>
                        @endif
                        <input type="file" wire:model="fileParams" accept="{{ $activeTab === 'photo' ? 'image/*' : 'video/mp4,video/x-m4v,video/*' }}" class="w-full max-w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        @error('fileParams') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="fileParams" class="text-sm text-blue-600 mt-2">Mengunggah file...</div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('isModalOpen', false)" class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800" wire:loading.attr="disabled" wire:target="save, fileParams">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
