@props([
    'title',
    'subtitle' => null,
])

{{-- Banner serif gelap dengan ornamen barbershop; slot opsional untuk tombol aksi. --}}
<section class="rise relative mb-6 overflow-hidden rounded-2xl bg-neutral-950 px-6 py-6 text-white sm:px-8 sm:py-7">
    <x-ornament variant="pole" class="absolute inset-y-0 left-0 w-1.5" />
    <x-ornament variant="dots" class="absolute -top-8 -right-8 h-40 w-40 opacity-40" />
    <x-ornament variant="corner" class="absolute bottom-4 right-5 h-11 w-11 rotate-180 opacity-60" />

    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="block h-1 w-10 rounded-full bg-white/80"></span>
            <h2 class="mt-4 font-serif text-2xl font-bold leading-tight sm:text-3xl">{{ $title }}</h2>
            @if ($subtitle)
                <p class="mt-2 max-w-md text-white/60">{{ $subtitle }}</p>
            @endif
        </div>
        @isset($action)
            <div class="shrink-0">{{ $action }}</div>
        @endisset
    </div>
</section>
