<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Manajemen Skema</h1>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 dark:bg-emerald-500 dark:text-zinc-900 dark:hover:bg-emerald-400 transition-colors">
            <svg class="w-5 h-5 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Skema
        </button>
    </div>

    <div class="space-y-6">
        @forelse($this->groupedSchemes as $faculty => $programs)
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 shadow-sm overflow-hidden">
                <div class="bg-zinc-50 dark:bg-zinc-800/50 px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $faculty }}</h2>
                </div>
                
                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach($programs as $program => $schemes)
                        <div class="p-6">
                            <h3 class="text-md font-medium text-zinc-700 dark:text-zinc-300 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Program Studi: {{ $program }}
                            </h3>
                            
                            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-800">
                                <table class="w-full text-sm text-left align-middle divide-y divide-zinc-200 dark:divide-zinc-800">
                                    <thead class="text-xs text-zinc-500 uppercase bg-zinc-50 dark:bg-zinc-800/50 dark:text-zinc-400">
                                        <tr>
                                            <th class="px-6 py-3 font-medium tracking-wider w-1/3">Nama Skema</th>
                                            <th class="px-6 py-3 font-medium tracking-wider">Deskripsi</th>
                                            <th class="px-6 py-3 font-medium tracking-wider text-center w-28">Status</th>
                                            <th class="px-6 py-3 font-medium tracking-wider text-right w-32">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 bg-white dark:bg-zinc-900 dark:divide-zinc-800">
                                        @foreach($schemes as $scheme)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-zinc-900 dark:text-white">{{ $scheme->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                                    {{ $scheme->description ?: '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($scheme->is_active)
                                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400 dark:ring-emerald-900/50">Aktif</span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-700 ring-1 ring-inset ring-zinc-500/10 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button wire:click="edit({{ $scheme->id }})" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300 mr-3">Edit</button>
                                                    <button wire:click="confirmDelete({{ $scheme->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-12 text-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900">
                <svg class="w-12 h-12 mx-auto text-zinc-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Belum ada Skema</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Tambahkan skema sertifikasi baru untuk mulai mengelola pangkalan data skema uji.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal Form (Create/Edit) -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-form') show = true" 
         x-on:close-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-form') show = false" 
         x-on:keydown.escape.window="show = false" 
         x-show="show" 
         class="relative z-50" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         style="display: none;">
        
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-zinc-500/75 dark:bg-black/75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative overflow-hidden rounded-xl w-full bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-xl text-zinc-900 dark:text-zinc-100 pt-6">
                    <form wire:submit="save" class="flex flex-col h-full">
                        <div class="px-6 pb-6 space-y-5 flex-1">
                            <div>
                                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white" id="modal-title">
                                    {{ $schemeId ? 'Edit Skema' : 'Tambah Skema Baru' }}
                               </h2>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-1">Nama Skema <span class="text-red-500">*</span></label>
                                    <input wire:model="name" type="text" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6" required />
                                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-1">Fakultas <span class="text-red-500">*</span></label>
                                        <input wire:model="faculty" type="text" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6" required placeholder="Fakultas Ilmu Komputer"/>
                                        @error('faculty') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-1">Program Studi <span class="text-red-500">*</span></label>
                                        <input wire:model="study_program" type="text" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6" required placeholder="Sistem Informasi"/>
                                        @error('study_program') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-1">Deskripsi</label>
                                    <textarea wire:model="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6"></textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex items-center">
                                    <input wire:model="is_active" id="is_active" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:checked:bg-white dark:focus:ring-white">
                                    <label for="is_active" class="ml-2 block text-sm text-zinc-900 dark:text-zinc-300">
                                        Skema Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 bg-zinc-50 dark:bg-zinc-800/50">
                            <button type="button" @click="show = false" class="rounded-md bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700">Batal</button>
                            <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 dark:bg-emerald-500 dark:text-zinc-900 dark:hover:bg-emerald-400">
                                <span wire:loading.remove wire:target="save">Simpan Skema</span>
                                <span wire:loading wire:target="save">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Delete -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = true" 
         x-on:close-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = false" 
         x-on:keydown.escape.window="show = false" 
         x-show="show" 
         class="relative z-50" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         style="display: none;">
        
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-zinc-500/75 dark:bg-black/75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative overflow-hidden rounded-xl w-full bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-lg text-zinc-900 dark:text-zinc-100 pt-6">
                    <div class="px-6 pb-6 p-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-white" id="modal-title">Hapus Skema</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Apakah Anda yakin ingin menghapus skema ini? Data skema yang dihapus tidak dapat dikembalikan, perhatikan apakah ada peserta yang terkait dengan skema ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 bg-zinc-50 dark:bg-zinc-800/50">
                        <button type="button" @click="show = false" class="rounded-md bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700">Batal</button>
                        <button wire:click="delete" type="button" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                            <span wire:loading.remove wire:target="delete">Hapus Skema</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
