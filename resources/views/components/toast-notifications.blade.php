<div
    wire:ignore
    data-toast-root
    x-data="{
        toasts: [],
        add(detail) {
            const payload = Array.isArray(detail) ? detail[0] : detail;

            if (! payload || ! payload.message) {
                return;
            }

            const toast = {
                id: Date.now() + Math.random(),
                message: payload.message,
                type: payload.type ?? 'success',
            };

            this.toasts.push(toast);

            window.setTimeout(() => {
                this.toasts = this.toasts.filter((item) => item.id !== toast.id);
            }, 4000);
        },
    }"
    x-on:toast.window="add($event.detail)"
    class="pointer-events-none fixed right-6 top-6 z-[100] flex flex-col gap-3"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-center gap-4 rounded-2xl border px-5 py-4 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)]"
            :class="toast.type === 'success' ? 'border-emerald-100 bg-white text-slate-800' : 'border-red-100 bg-red-50 text-red-900'"
        >
            <span
                x-show="toast.type === 'success'"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
            </span>

            <span
                x-show="toast.type !== 'success'"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </span>

            <p class="text-sm font-bold tracking-tight" x-text="toast.message"></p>
        </div>
    </template>
</div>
