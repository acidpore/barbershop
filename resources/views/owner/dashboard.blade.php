<x-dashboard-layout title="Dashboard">

    {{-- Hero sambutan: panel gelap serif + ornamen, set nada premium --}}
    <section class="rise relative overflow-hidden rounded-2xl bg-neutral-950 px-6 py-7 text-white sm:px-8 sm:py-8">
        <x-ornament variant="pole" class="absolute inset-y-0 left-0 w-1.5" />
        <x-ornament variant="dots" class="absolute -top-8 -right-8 h-44 w-44 opacity-40" />
        <x-ornament variant="corner" class="absolute bottom-5 right-6 h-12 w-12 rotate-180 opacity-60" />

        <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-widest text-white/50">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h2 class="mt-2 font-serif text-3xl font-bold leading-tight sm:text-4xl">
                    Halo, {{ auth()->user()->name }}.
                </h2>
                <p class="mt-2 max-w-md text-white/60">Ini ringkasan operasional DOS. hari ini.</p>
            </div>
            <a href="{{ route('bookings.create') }}"
               class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Booking Baru
            </a>
        </div>
    </section>

    {{-- Stat cards: ikon + barber-pole accent + animasi count-up --}}
    @php
        $stats = [
            ['label' => 'Omzet Hari Ini', 'value' => $revenueToday, 'prefix' => 'Rp', 'icon' => 'M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'],
            ['label' => 'Booking Hari Ini', 'value' => $bookingsToday, 'prefix' => '', 'icon' => 'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'],
            ['label' => 'Booking Menunggu', 'value' => $pendingBookings, 'prefix' => '', 'icon' => 'M12 6v6l4 2M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z'],
            ['label' => 'Barber Aktif', 'value' => $barberCount, 'prefix' => '', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
        ];
    @endphp

    <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $i => $stat)
            <div class="rise group relative overflow-hidden rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                 style="animation-delay: {{ ($i + 1) * 90 }}ms">
                <span class="absolute inset-x-0 top-0 h-1 bg-neutral-900 origin-left scale-x-0 transition-transform duration-300 group-hover:scale-x-100"></span>
                <div class="flex items-start justify-between">
                    <span class="text-sm text-neutral-500">{{ $stat['label'] }}</span>
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700 transition-colors group-hover:bg-neutral-900 group-hover:text-white">
                        <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $stat['icon'] }}"/></svg>
                    </span>
                </div>
                <div class="mt-3 font-serif text-3xl font-bold text-neutral-900"
                     x-data="{ n: 0 }"
                     x-init="$nextTick(() => { const target = {{ (int) $stat['value'] }}; if (target === 0) { n = 0; return; } const dur = 900, t0 = performance.now(); const tick = (now) => { const p = Math.min((now - t0) / dur, 1); n = Math.round((1 - Math.pow(1 - p, 3)) * target); if (p < 1) requestAnimationFrame(tick); }; requestAnimationFrame(tick); })">
                    <span>{{ $stat['prefix'] }}</span><span x-text="n.toLocaleString('id-ID')">0</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Booking terbaru --}}
    <div class="rise mt-6" style="animation-delay: 480ms">
        <x-card>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-serif text-lg font-bold text-neutral-900">Booking Terbaru</h3>
                <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-neutral-500 transition-colors hover:text-neutral-900">Lihat semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="rtable w-full text-sm text-center [&_th]:text-center sm:[&_td]:text-center">
                    <thead class="border-b border-neutral-200 text-xs uppercase tracking-wider text-neutral-400">
                        <tr>
                            <th class="py-2.5 pr-4 font-medium">Customer</th>
                            <th class="py-2.5 pr-4 font-medium">Barber</th>
                            <th class="py-2.5 pr-4 font-medium">Paket</th>
                            <th class="py-2.5 pr-4 font-medium">Jadwal</th>
                            <th class="py-2.5 pr-4 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse ($recentBookings as $booking)
                            <tr class="transition-colors hover:bg-neutral-50">
                                <td data-label="Customer" class="py-3 pr-4 font-medium text-neutral-900">{{ $booking->customer_name }}</td>
                                <td data-label="Barber" class="py-3 pr-4 text-neutral-600">{{ $booking->barber->name }}</td>
                                <td data-label="Paket" class="py-3 pr-4 text-neutral-600">{{ $booking->service->name }}</td>
                                <td data-label="Jadwal" class="py-3 pr-4 text-neutral-600">{{ $booking->scheduled_at->format('d M Y H:i') }}</td>
                                <td data-label="Status" class="py-3 pr-4"><x-badge :status="$booking->status" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-neutral-400">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

</x-dashboard-layout>
