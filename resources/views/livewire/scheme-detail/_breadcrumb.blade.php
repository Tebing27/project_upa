        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors">Home</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('skema.index') }}" class="hover:text-gray-700 transition-colors">Skema
                    Sertifikasi</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="font-semibold text-gray-900 uppercase">{{ $scheme->name }}</span>
            </nav>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Detail Skema</h1>
            <div class="mt-2 h-1 w-32 rounded-full bg-emerald-400"></div>
        </div>
