        {{-- Jadwal Uji Mendatang --}}
        <div class="lg:col-span-1">
            <div class="h-full rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Jadwal Mendatang</h2>
                </div>

                <div class="space-y-4">
                    @forelse($upcomingSchedules as $schedule)
                        <div
                            class="rounded-2xl border border-gray-100 bg-gray-50/50 p-5 transition-all hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->exam_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="mt-1 text-xs font-medium text-gray-500">Jam
                                        {{ \Carbon\Carbon::parse($schedule->exam_date)->format('H:i') }}</p>
                                </div>
                                <span
                                    class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-100">
                                    {{ $schedule->participant_count }} Peserta
                                </span>
                            </div>

                            <div class="mt-4 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $schedule->exam_location ?? 'Belum ditentukan' }}</span>
                            </div>

                            <div class="mt-1.5 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ optional($schedule->scheme)->name }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 flex flex-col items-center text-center">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-medium text-gray-500">Tidak ada jadwal mendatang.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
