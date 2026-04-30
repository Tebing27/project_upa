                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">a. Data Pribadi</h3>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="no_ktp" class="block text-sm font-medium text-zinc-900 dark:text-white">NIK*</label>
                            <input id="no_ktp" type="text" wire:model="no_ktp" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_ktp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <span class="block text-sm font-medium text-zinc-900 dark:text-white">Jenis Kelamin*</span>
                            <div class="mt-3 flex gap-6">
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" wire:model="jenis_kelamin" value="L"> Laki-Laki</label>
                                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" wire:model="jenis_kelamin" value="P"> Perempuan</label>
                            </div>
                            @error('jenis_kelamin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-zinc-900 dark:text-white">Tempat Lahir*</label>
                            <input id="tempat_lahir" type="text" wire:model="tempat_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('tempat_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-zinc-900 dark:text-white">Tanggal Lahir*</label>
                            <input id="tanggal_lahir" type="date" wire:model="tanggal_lahir" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('tanggal_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_rumah" class="block text-sm font-medium text-zinc-900 dark:text-white">Alamat Domisili / Sesuai KTP*</label>
                            <textarea id="alamat_rumah" wire:model="alamat_rumah" rows="4" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"></textarea>
                            @error('alamat_rumah') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="kode_pos_rumah" class="block text-sm font-medium text-zinc-900 dark:text-white">Kode Pos*</label>
                            <input id="kode_pos_rumah" type="text" wire:model="kode_pos_rumah" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('kode_pos_rumah') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="no_wa" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telp / No WhatsApp Aktif*</label>
                            <input id="no_wa" type="text" wire:model="no_wa" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_wa') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="kualifikasi_pendidikan" class="block text-sm font-medium text-zinc-900 dark:text-white">Kualifikasi Pendidikan*</label>
                            <input id="kualifikasi_pendidikan" type="text" wire:model="kualifikasi_pendidikan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('kualifikasi_pendidikan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>
