@props([
    'variant' => 'pole',   // pole | corner | dots | divider
    'tone' => 'light',      // light (untuk bg gelap) | dark (untuk bg terang)
])

@php
    // Satu sumber warna; sisanya cuma opacity utility class dari pemanggil.
    $stroke = $tone === 'light' ? 'rgba(255,255,255,0.18)' : 'rgba(23,23,23,0.12)';
    $stripe = $tone === 'light' ? 'rgba(255,255,255,0.10)' : 'rgba(23,23,23,0.06)';
@endphp

@if ($variant === 'pole')
    {{-- Barber-pole: pita garis miring tipis, dekoratif. --}}
    <div aria-hidden="true" {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
         style="background-image: repeating-linear-gradient(45deg, {{ $stripe }} 0 6px, transparent 6px 14px);">
    </div>

@elseif ($variant === 'corner')
    {{-- Sudut: dua garis siku tipis sebagai bingkai. --}}
    <svg aria-hidden="true" {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
         viewBox="0 0 64 64" fill="none">
        <path d="M2 22V2h20" stroke="{{ $stroke }}" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M62 42v20H42" stroke="{{ $stroke }}" stroke-width="1.5" stroke-linecap="round"/>
    </svg>

@elseif ($variant === 'dots')
    {{-- Tekstur titik halus. --}}
    <div aria-hidden="true" {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
         style="background-image: radial-gradient({{ $stroke }} 1px, transparent 1.5px); background-size: 16px 16px;">
    </div>

@elseif ($variant === 'divider')
    {{-- Pembatas: garis dengan ikon gunting di tengah. --}}
    <div aria-hidden="true" {{ $attributes->merge(['class' => 'flex items-center gap-3 select-none']) }}>
        <span class="h-px flex-1" style="background: linear-gradient(to right, transparent, {{ $stroke }});"></span>
        <svg class="h-4 w-4 shrink-0" style="color: {{ $stroke }};" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/>
            <line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>
        </svg>
        <span class="h-px flex-1" style="background: linear-gradient(to left, transparent, {{ $stroke }});"></span>
    </div>
@endif
