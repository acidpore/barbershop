<x-dashboard-layout title="Booking">
    @php
        $startHour = 8;
        $endHour = 21;
        $hourPx = 80;
        $totalPx = ($endHour - $startHour) * $hourPx;
        $isToday = $selectedDate->isToday();
        $nowTop = ($isToday)
            ? ((now()->hour * 60 + now()->minute) - $startHour * 60) / 60 * $hourPx
            : null;
        // Warna blok per status.
        $styles = [
            'pending'     => 'bg-amber-50 border-amber-400 text-amber-900',
            'in_progress' => 'bg-blue-50 border-blue-500 text-blue-900',
            'done'        => 'bg-emerald-50 border-emerald-500 text-emerald-900',
            'cancelled'   => 'bg-neutral-100 border-neutral-300 text-neutral-400 line-through',
        ];
    @endphp

    {{-- Header: bulan + navigasi minggu --}}
    <x-page-header :title="$selectedDate->translatedFormat('F Y')" :subtitle="$selectedDate->translatedFormat('l, d F Y')">
        <x-slot:action>
            <div class="flex items-center gap-2">
                <a href="{{ route('bookings.index', ['date' => $selectedDate->copy()->subWeek()->toDateString()]) }}"
                   class="grid h-10 w-10 place-items-center rounded-full border border-white/25 text-white transition-colors hover:bg-white/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
                <a href="{{ route('bookings.index') }}"
                   class="rounded-full border border-white/25 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-white/10">Hari ini</a>
                <a href="{{ route('bookings.index', ['date' => $selectedDate->copy()->addWeek()->toDateString()]) }}"
                   class="grid h-10 w-10 place-items-center rounded-full border border-white/25 text-white transition-colors hover:bg-white/10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </x-slot:action>
    </x-page-header>

    {{-- Strip minggu --}}
    <div class="rise mb-6" style="animation-delay:90ms">
        <x-card>
            <div class="grid grid-cols-7 gap-1 sm:gap-2">
                @foreach ($weekDays as $day)
                    @php
                        $active = $day->isSameDay($selectedDate);
                        $count = $weekCounts[$day->toDateString()] ?? 0;
                    @endphp
                    <a href="{{ route('bookings.index', ['date' => $day->toDateString()]) }}"
                       class="flex flex-col items-center gap-1 rounded-xl py-2 transition-colors
                              {{ $active ? 'bg-neutral-900 text-white' : 'hover:bg-neutral-100 text-neutral-700' }}">
                        <span class="text-[11px] font-medium uppercase {{ $active ? 'text-white/60' : 'text-neutral-400' }}">{{ $day->translatedFormat('D') }}</span>
                        <span class="text-base font-bold {{ $day->isToday() && ! $active ? 'text-neutral-900' : '' }}">{{ $day->format('d') }}</span>
                        <span class="h-1.5 w-1.5 rounded-full {{ $count ? ($active ? 'bg-white' : 'bg-neutral-900') : 'bg-transparent' }}"></span>
                    </a>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Timeline harian --}}
    <div class="rise" style="animation-delay:180ms">
        <x-card>
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="font-serif text-lg font-bold text-neutral-900">{{ $dayBookings->count() }} Booking</h3>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs text-neutral-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>Menunggu</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>Dikerjakan</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Selesai</span>
                    <span class="hidden h-3.5 w-px bg-neutral-200 sm:inline-block"></span>
                    <span class="inline-flex items-center gap-1.5"><span class="rounded bg-emerald-600 px-1.5 py-0.5 text-[10px] font-bold text-white">Lunas</span></span>
                    <span class="inline-flex items-center gap-1.5"><span class="rounded px-1.5 py-0.5 text-[10px] font-bold text-rose-600 ring-1 ring-rose-200">Belum</span></span>
                </div>
            </div>

            <div class="relative" style="height: {{ $totalPx }}px">
                {{-- Garis jam + label --}}
                @for ($h = $startHour; $h <= $endHour; $h++)
                    <div class="absolute left-0 right-0 flex items-start" style="top: {{ ($h - $startHour) * $hourPx }}px">
                        <span class="w-14 shrink-0 -translate-y-2 pr-2 text-right text-xs text-neutral-400">{{ \Illuminate\Support\Carbon::createFromTime($h)->format('g A') }}</span>
                        <span class="mt-px h-px flex-1 bg-neutral-100"></span>
                    </div>
                @endfor

                {{-- Garis "sekarang" --}}
                @if ($nowTop !== null && $nowTop >= 0 && $nowTop <= $totalPx)
                    <div class="absolute left-14 right-0 z-20 flex items-center" style="top: {{ $nowTop }}px">
                        <span class="-ml-1 h-2 w-2 rounded-full bg-rose-500"></span>
                        <span class="h-px flex-1 bg-rose-500"></span>
                    </div>
                @endif

                {{-- Blok booking --}}
                <div class="absolute inset-y-0 left-14 right-0">
                    @forelse ($dayBookings as $booking)
                        @php
                            $top = ($booking->startMin - $startHour * 60) / 60 * $hourPx;
                            $height = $booking->durationMin / 60 * $hourPx;
                            $widthPct = 100 / $laneCount;
                        @endphp
                        <a href="{{ route('bookings.edit', $booking) }}"
                           class="absolute flex flex-col justify-center overflow-hidden rounded-lg border-l-4 px-3 py-1 text-xs leading-tight shadow-sm transition hover:shadow-md {{ $styles[$booking->status] }}"
                           style="top: {{ $top }}px; height: {{ max($height - 4, 50) }}px; left: calc({{ $booking->lane * $widthPct }}% + 2px); width: calc({{ $widthPct }}% - 4px);">
                            <div class="flex items-center justify-between gap-1">
                                <span class="truncate font-semibold">{{ $booking->scheduled_at->format('H:i') }} &middot; {{ $booking->customer_name }}</span>
                                @if ($booking->payment)
                                    <span class="shrink-0 rounded bg-emerald-600 px-1.5 text-[10px] font-bold text-white">Lunas</span>
                                @elseif ($booking->status !== 'cancelled')
                                    <span class="shrink-0 rounded bg-white/80 px-1.5 text-[10px] font-bold text-rose-600 ring-1 ring-rose-200">Belum</span>
                                @endif
                            </div>
                            <div class="truncate opacity-70">{{ $booking->service->name }} &middot; {{ $booking->barber->name }}</div>
                        </a>
                    @empty
                        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 text-center text-sm text-neutral-400">
                            Tidak ada booking pada hari ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </x-card>
    </div>

    {{-- Daftar semua booking --}}
    <div class="rise mt-6" style="animation-delay:270ms">
        <x-card>
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="font-serif text-lg font-bold text-neutral-900">Semua Booking</h3>
                <form method="GET" class="flex flex-1 items-center gap-2 sm:max-w-md sm:justify-end">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari customer..."
                           class="min-w-0 flex-1 rounded-lg border-neutral-300 text-sm focus:border-neutral-800 focus:ring-neutral-800 sm:max-w-xs">
                    <select name="status" class="shrink-0 rounded-lg border-neutral-300 text-sm focus:border-neutral-800 focus:ring-neutral-800">
                        <option value="">Semua status</option>
                        @foreach (['pending' => 'Menunggu', 'in_progress' => 'Dikerjakan', 'done' => 'Selesai', 'cancelled' => 'Batal'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="shrink-0 rounded-lg bg-neutral-800 px-4 py-2 text-sm font-semibold text-white hover:bg-neutral-700">Filter</button>
                </form>
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
                            <th class="py-2.5 pr-4 font-medium">Bayar</th>
                            <th class="py-2.5 pr-4 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse ($bookings as $booking)
                            <tr class="transition-colors hover:bg-neutral-50">
                                <td data-label="Customer" class="py-3 pr-4 font-medium text-neutral-900">{{ $booking->customer_name }}</td>
                                <td data-label="Barber" class="py-3 pr-4 text-neutral-600">{{ $booking->barber->name }}</td>
                                <td data-label="Paket" class="py-3 pr-4 text-neutral-600">{{ $booking->service->name }}</td>
                                <td data-label="Jadwal" class="py-3 pr-4 text-neutral-600">{{ $booking->scheduled_at->format('d M Y H:i') }}</td>
                                <td data-label="Status" class="py-3 pr-4"><x-badge :status="$booking->status" /></td>
                                <td data-label="Bayar" class="py-3 pr-4">
                                    @if ($booking->payment)
                                        <span class="font-medium text-emerald-600">Lunas</span>
                                    @elseif ($booking->status === 'done')
                                        <a href="{{ route('payments.create', $booking) }}" class="font-medium text-neutral-900 hover:underline">Bayar</a>
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td data-label="Aksi" class="py-3 pr-4 whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <a href="{{ route('bookings.edit', $booking) }}" title="Edit"
                                           class="grid h-8 w-8 place-items-center rounded-lg text-neutral-600 transition-colors hover:bg-neutral-100 hover:text-neutral-900">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')">
                                            @csrf @method('DELETE')
                                            <button title="Hapus" class="grid h-8 w-8 place-items-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-6 text-center text-neutral-400">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $bookings->links() }}</div>
        </x-card>
    </div>

    {{-- Tombol tambah --}}
    <a href="{{ route('bookings.create') }}"
       class="fixed bottom-6 right-6 z-30 grid h-14 w-14 place-items-center rounded-full bg-neutral-900 text-white shadow-lg transition-transform hover:scale-105">
        <x-icon name="plus" class="h-6 w-6" />
    </a>
</x-dashboard-layout>
