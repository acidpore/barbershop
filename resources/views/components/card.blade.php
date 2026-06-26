@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-neutral-200 shadow-sm']) }}>
    @if ($title)
        <div class="px-5 py-4 border-b border-neutral-200 font-semibold text-neutral-900">{{ $title }}</div>
    @endif
    <div class="p-5">
        {{ $slot }}
    </div>
</div>
