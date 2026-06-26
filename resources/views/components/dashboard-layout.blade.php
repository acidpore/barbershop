@props(['title' => 'Dashboard'])

@php
    $role = auth()->user()->role;
    $nav = [
        'owner' => [
            ['route' => 'owner.dashboard', 'label' => 'Dashboard', 'pattern' => 'owner', 'icon' => 'dashboard'],
            ['route' => 'bookings.index', 'label' => 'Booking', 'pattern' => 'bookings*', 'icon' => 'calendar'],
            ['route' => 'payments.index', 'label' => 'Pembayaran', 'pattern' => 'payments*', 'icon' => 'payment'],
            ['route' => 'owner.services.index', 'label' => 'Paket Layanan', 'pattern' => 'owner/services*', 'icon' => 'scissors'],
            ['route' => 'owner.users.index', 'label' => 'Pengguna', 'pattern' => 'owner/users*', 'icon' => 'users'],
            ['route' => 'owner.reports', 'label' => 'Laporan', 'pattern' => 'owner/reports', 'icon' => 'chart'],
        ],
        'kasir' => [
            ['route' => 'bookings.index', 'label' => 'Booking', 'pattern' => 'bookings*', 'icon' => 'calendar'],
            ['route' => 'payments.index', 'label' => 'Pembayaran', 'pattern' => 'payments*', 'icon' => 'payment'],
        ],
        'barber' => [
            ['route' => 'barber.queue', 'label' => 'Antrian Saya', 'pattern' => 'barber*', 'icon' => 'queue'],
        ],
    ][$role] ?? [];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — DOS.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ open: false }" class="font-sans antialiased bg-neutral-100 text-neutral-800">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-neutral-950 text-neutral-400 flex flex-col
                      transform transition-transform md:translate-x-0"
               :class="open ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-16 flex items-center gap-2 px-6 text-white border-b border-white/5">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>
                </svg>
                <span class="font-serif text-xl font-bold tracking-tight">DOS.</span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @foreach ($nav as $item)
                    @php $active = request()->is($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                              {{ $active ? 'bg-white text-neutral-900' : 'hover:bg-white/5 hover:text-white' }}">
                        <x-icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="px-3 py-4 border-t border-white/5 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                          {{ request()->is('profile*') ? 'bg-white text-neutral-900' : 'hover:bg-white/5 hover:text-white' }}">
                    <x-icon name="settings" class="h-5 w-5 shrink-0" />
                    Pengaturan
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white transition">
                        <x-icon name="logout" class="h-5 w-5 shrink-0" />
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0 md:ml-64">
            <header class="sticky top-0 z-20 h-16 bg-white border-b border-neutral-200 flex items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button @click="open = true" class="md:hidden text-neutral-600 hover:text-neutral-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h1 class="font-serif text-lg sm:text-xl font-bold text-neutral-900">{{ $title }}</h1>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    {{-- Aktifkan Web Push reminder (sembunyi jika sudah diizinkan). --}}
                    <button type="button" onclick="enablePush()"
                            x-data="{ granted: window.Notification && Notification.permission === 'granted' }"
                            x-on:push-enabled.window="granted = true"
                            x-show="!granted"
                            title="Aktifkan notifikasi booking"
                            class="grid h-9 w-9 place-items-center rounded-full text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    {{-- Jam live: bantu owner pantau waktu vs jadwal booking. --}}
                    <div x-data="{ now: '' }"
                         x-init="const f = () => { now = new Date().toLocaleString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit', second: '2-digit' }); }; f(); setInterval(f, 1000)"
                         class="hidden items-center gap-2 rounded-lg bg-neutral-100 px-3 py-1.5 md:flex">
                        <svg class="h-4 w-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        <span class="text-xs font-medium tabular-nums text-neutral-700" x-text="now"></span>
                    </div>
                    <div class="text-sm text-right hidden sm:block">
                        <div class="font-medium text-neutral-900 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-neutral-500 capitalize text-xs">{{ $role }}</div>
                    </div>
                    <div class="grid place-items-center w-9 h-9 rounded-full bg-neutral-900 text-white text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @if (session('status'))
                    <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('partials.push')

    <style>
        [x-cloak]{display:none!important}
        @keyframes riseUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
        .rise { opacity: 0; animation: riseUp .6s cubic-bezier(.16,1,.3,1) forwards; }
        @media (prefers-reduced-motion: reduce) { .rise { animation: none; opacity: 1; } }

        /* Tabel responsif: di mobile tiap baris jadi kartu (tanpa scroll samping). */
        @media (max-width: 639px) {
            .rtable, .rtable tbody, .rtable tr, .rtable td { display: block; width: 100%; }
            .rtable thead { display: none; }
            .rtable tr { margin-bottom: .75rem; border: 1px solid #e5e5e5; border-radius: .75rem; padding: .25rem .9rem; }
            .rtable td { display: flex; justify-content: space-between; align-items: center; gap: 1rem; text-align: right; padding: .5rem 0; border-bottom: 1px solid #f0f0f0; }
            .rtable tr td:last-child { border-bottom: 0; }
            .rtable td::before { content: attr(data-label); font-weight: 600; color: #737373; text-align: left; }
            .rtable td:not([data-label]) { justify-content: center; text-align: center; }
            .rtable td:not([data-label])::before { content: none; }
        }
    </style>
</body>
</html>
