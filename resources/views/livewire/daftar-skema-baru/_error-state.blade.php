<div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
    <div class="flex flex-col items-center text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50">
            <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak Dapat Mendaftar</h3>
        <p class="mt-2 max-w-md text-sm text-gray-500">{{ $errorMessage }}</p>
        <a href="{{ route('dashboard') }}"
            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
            Kembali ke Dashboard
        </a>
    </div>
</div>
