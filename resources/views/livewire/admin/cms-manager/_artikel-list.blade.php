    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Content Library</h2>
                <p class="mt-1 text-sm text-slate-500">Manage and curate your editorial catalog</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm border border-slate-200 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
                <button
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm border border-slate-200 hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div
                class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Articles</p>
                <p class="mt-2 text-4xl font-bold text-slate-900">{{ number_format($this->totalArticlesCount) }}
                </p>
            </div>
            <div
                class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Published</p>
                <p class="mt-2 text-4xl font-bold text-slate-900 flex items-center gap-2">
                    {{ number_format($this->publishedArticlesCount) }}
                </p>
            </div>
            <div
                class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">In Review</p>
                <p class="mt-2 text-4xl font-bold text-slate-900">{{ number_format($this->draftArticlesCount) }}
                </p>
            </div>
            <div
                class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Live Traffic</p>
                <p class="mt-2 text-4xl font-bold text-slate-900">{{ number_format($this->totalArticlesViews) }}
                </p>
            </div>
        </div>

        <!-- Main Table Container -->
        <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-5">
                    <label class="flex items-center gap-3">
                        <input type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/30">
                        <span
                            class="text-sm font-semibold text-slate-700 cursor-pointer">{{ count($selectedArticles) }}
                            items selected</span>
                    </label>
                    <button wire:click="bulkArchive"
                        class="text-sm font-bold text-slate-800 hover:text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">Archive</button>
                    <button wire:click="bulkDelete"
                        class="text-sm font-bold text-red-600 hover:text-red-700 bg-red-50 border border-red-100 px-3 py-1.5 rounded-lg">Delete</button>
                </div>
                <div class="text-sm font-medium text-slate-500">
                    Showing
                    {{ $this->articleEntries->firstItem() ?? 0 }}-{{ $this->articleEntries->lastItem() ?? 0 }}
                    of {{ number_format($this->articleEntries->total()) }}
                </div>
            </div>

            <div
                class="grid grid-cols-[50px_minmax(0,2fr)_150px_80px_100px_120px] gap-4 px-6 py-4 border-b border-slate-50 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                <div></div>
                <span>Article Title</span>
                <span>Author</span>
                <span>Views</span>
                <span>Status</span>
                <span>Date</span>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse ($this->articleEntries as $article)
                    <div class="grid items-center w-full grid-cols-[50px_minmax(0,2fr)_150px_80px_100px_120px] gap-4 px-6 py-4 text-left transition hover:bg-slate-50 cursor-pointer"
                        wire:click="selectArticle({{ $article->id }})">
                        <div class="flex items-center pt-2 h-full" wire:click.stop>
                            <input type="checkbox" value="{{ $article->id }}"
                                wire:model.live="selectedArticles"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/30">
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-slate-200 border border-slate-100 shadow-sm relative">
                                @php
                                    preg_match('/<img[^>]+src="([^">]+)"/', $article->body, $matches);
                                    $thumbnail = $matches[1] ?? null;
                                @endphp
                                @if ($thumbnail)
                                    <img src="{{ $thumbnail }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="h-full w-full bg-slate-800 flex items-center justify-center text-white font-bold text-[9px] tracking-widest uppercase">
                                        Img</div>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900 tracking-tight">
                                    {{ $article->title }}</p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">
                                    {{ $article->tags->pluck('name')->join(', ') ?: 'No category' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full max-w-[150px] overflow-hidden">
                            <div
                                class="h-6 w-6 flex-shrink-0 overflow-hidden rounded-full border border-slate-200 bg-white">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name ?? 'A') }}&background=0F172A&color=fff&bold=true"
                                    class="w-full h-full">
                            </div>
                            <span
                                class="text-sm font-semibold text-slate-700 truncate">{{ $article->author_name ?? 'Unknown' }}</span>
                        </div>
                        <div class="text-sm font-bold text-slate-700">
                            {{ number_format($article->views ?? 0) }}
                        </div>
                        <div>
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider',
                                'bg-indigo-50 text-indigo-700' => $article->status === 'published',
                                'bg-slate-200/70 text-slate-600' => $article->status === 'draft',
                                'bg-slate-900 text-white' => $article->status === 'scheduled',
                            ])>
                                {{ $article->status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-slate-700">
                                {{ optional($article->published_at)->format('M d, Y') ?? 'Unpublished' }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                {{ optional($article->published_at)->format('h:i A') ?? '' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-slate-500">No articles found.</p>
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $this->articleEntries->links() }}
            </div>
        </div>
    </div>
