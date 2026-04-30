        <div class="border-b border-gray-100 px-6 py-5 md:px-8 flex flex-col gap-5">
            <h2 class="text-[1.35rem] font-bold text-gray-900">Semua Sertifikat</h2>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div
                    class="inline-flex w-full overflow-x-auto sm:w-auto items-center p-1.5 bg-gray-100 rounded-xl shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-900 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Semua
                        </span>
                    </label>

                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="active" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-[#1b8a6b] peer-checked:bg-white peer-checked:text-[#1b8a6b] peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Aktif
                        </span>
                    </label>

                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="inactive" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-900 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tidak Aktif
                        </span>
                    </label>
                </div>

                @if ($faculties->isNotEmpty())
                    <div class="w-full sm:w-64">
                        <select wire:model.live="filterFaculty"
                            class="block w-full rounded-xl border border-gray-200 bg-white py-2 pl-4 pr-10 text-sm font-medium text-gray-700 shadow-sm transition-all focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Kategori Fakultas</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty }}">{{ $faculty }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
