            {{-- Biodata Section --}}
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Pribadi Peserta
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $biodata = [
                            'Nama Lengkap' => $registration->user->name ?: '-',
                            'Email' => $registration->user->email ?: '-',
                            'NIK' => $registration->user->no_ktp ?: '-',
                            ...($registration->user->isUpnvjUser()
                                ? [
                                    'NIM' => $registration->user->nim ?: '-',
                                    'Fakultas' => $registration->user->fakultas ?: '-',
                                    'Program Studi' => $registration->user->program_studi ?: '-',
                                ]
                                : []),
                            'Tempat Lahir' => $registration->user->tempat_lahir ?: '-',
                            'Tanggal Lahir' => $registration->user->tanggal_lahir
                                ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->translatedFormat(
                                    'd M Y',
                                )
                                : '-',
                            'Jenis Kelamin' =>
                                $registration->user->jenis_kelamin === 'L'
                                    ? 'Laki-laki'
                                    : ($registration->user->jenis_kelamin === 'P'
                                        ? 'Perempuan'
                                        : '-'),
                            'Nomor WhatsApp' => $registration->user->no_wa ?: '-',
                            'Kualifikasi Pendidikan' => $registration->user->kualifikasi_pendidikan ?: '-',
                            'Kode Pos' => $registration->user->profile?->kode_pos_rumah ?: '-',
                            'Telepon Rumah' => $registration->user->profile?->telp_rumah ?: '-',
                            'Telepon Kantor' => $registration->user->profile?->telp_kantor ?: '-',
                            'Nama Institusi / Perusahaan' => $registration->user->nama_perusahaan ?: '-',
                            'Jabatan' => $registration->user->jabatan ?: '-',
                            'Alamat Perusahaan' => $registration->user->alamat_perusahaan ?: '-',
                            'Kode Pos Perusahaan' => $registration->user->kode_pos_perusahaan ?: '-',
                            'Telepon Perusahaan' => $registration->user->no_telp_perusahaan ?: '-',
                            'Email Perusahaan' => $registration->user->email_perusahaan ?: '-',
                        ];
                    @endphp
                    @foreach ($biodata as $label => $value)
                        <div class="flex flex-col p-5 rounded-2xl bg-gray-50/50 border border-gray-50">
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">{{ $label }}</span>
                            <span class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col p-5 rounded-2xl bg-gray-50/50 border border-gray-50">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Alamat
                        Rumah</span>
                    <span
                        class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $registration->user->alamat_rumah ?: '-' }}</span>
                </div>
            </div>
