@props(['tags' => []])

@if (is_array($tags) && count($tags) > 0)
    <div {{ $attributes->class('flex flex-wrap gap-2') }}>
        @foreach ($tags as $tag)
            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700">
                {{ $tag }}
            </span>
        @endforeach
    </div>
@endif
