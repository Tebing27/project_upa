                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">b. Data Institusi / Perusahaan Sekarang</h3>
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="nama_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Nama Institusi / Perusahaan*</label>
                            <input id="nama_perusahaan" type="text" wire:model="nama_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('nama_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telp_rumah" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telepon Rumah</label>
                            <input id="telp_rumah" type="text" wire:model="telp_rumah" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('telp_rumah') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telp_kantor" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telepon Kantor</label>
                            <input id="telp_kantor" type="text" wire:model="telp_kantor" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('telp_kantor') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="jabatan" class="block text-sm font-medium text-zinc-900 dark:text-white">Jabatan</label>
                            <input id="jabatan" type="text" wire:model="jabatan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('jabatan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="alamat_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Alamat Lembaga / Perusahaan</label>
                            <textarea id="alamat_perusahaan" wire:model="alamat_perusahaan" rows="3" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"></textarea>
                            @error('alamat_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="kode_pos_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Kode POS Perusahaan*</label>
                            <input id="kode_pos_perusahaan" type="text" wire:model="kode_pos_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('kode_pos_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="no_telp_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">No. Telp Perusahaan</label>
                            <input id="no_telp_perusahaan" type="text" wire:model="no_telp_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('no_telp_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email_perusahaan" class="block text-sm font-medium text-zinc-900 dark:text-white">Email Perusahaan</label>
                            <input id="email_perusahaan" type="email" wire:model="email_perusahaan" class="mt-2 block w-full rounded-xl border border-zinc-200 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @error('email_perusahaan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
