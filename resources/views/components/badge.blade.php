@props(['status'])

@php
    $styles = [
        'pending' => 'bg-amber-100 text-amber-700',
        'in_progress' => 'bg-blue-100 text-blue-700',
        'done' => 'bg-emerald-100 text-emerald-700',
        'cancelled' => 'bg-rose-100 text-rose-700',
    ];
    $labels = [
        'pending' => 'Menunggu',
        'in_progress' => 'Dikerjakan',
        'done' => 'Selesai',
        'cancelled' => 'Batal',
    ];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $styles[$status] ?? 'bg-neutral-100 text-neutral-600' }}">
    {{ $labels[$status] ?? $status }}
</span>
