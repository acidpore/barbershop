<x-dashboard-layout title="Laporan">
    <x-page-header title="Laporan & Performa" subtitle="Pantau omzet, performa barber, dan paket terlaris.">
        <x-slot:action>
            <div class="flex gap-2">
                <a href="{{ route('owner.reports.pdf') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                    <x-icon name="download" class="h-4 w-4" /> PDF
                </a>
                <a href="{{ route('owner.reports.xlsx') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-white/25 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-white/10">
                    <x-icon name="download" class="h-4 w-4" /> Excel
                </a>
            </div>
        </x-slot:action>
    </x-page-header>

    {{-- Grafik: 2 kolom. Pakai bar HTML murni (tanpa dependency JS). --}}
    <div class="mb-6 grid gap-6 lg:grid-cols-2">
        @php
            $days = $revenueByDay->reverse()->values();
            $maxDay = $revenueByDay->max('total') ?: 1;
            $maxBarber = $revenueByBarber->max('total') ?: 1;
        @endphp

        <div class="rise" style="animation-delay:90ms">
            <x-card title="Tren Omzet Harian">
                <p class="-mt-3 mb-4 text-xs text-neutral-400">Nilai dalam ribuan Rupiah</p>
                <div class="flex h-56 items-end gap-1.5">
                    @forelse ($days as $row)
                        @php $pct = max(6, round($row->total / $maxDay * 88)); @endphp
                        <div class="group flex h-full flex-1 flex-col items-center">
                            <div class="relative w-full flex-1">
                                <div class="absolute bottom-0 left-0 w-full rounded-t bg-gradient-to-t from-neutral-700 to-neutral-400 transition-all duration-300 group-hover:from-neutral-900 group-hover:to-neutral-600"
                                     style="height: {{ $pct }}%"
                                     title="Rp{{ number_format($row->total, 0, ',', '.') }}"></div>
                                <span class="absolute left-0 right-0 text-center text-[10px] font-bold tabular-nums text-neutral-800"
                                      style="bottom: calc({{ $pct }}% + 2px)">{{ number_format($row->total / 1000, 0, ',', '.') }}</span>
                            </div>
                            <span class="mt-1.5 text-[10px] text-neutral-400">{{ \Illuminate\Support\Carbon::parse($row->day)->format('d/m') }}</span>
                        </div>
                    @empty
                        <p class="w-full py-10 text-center text-sm text-neutral-400">Belum ada data.</p>
                    @endforelse
                </div>
            </x-card>
        </div>

        <div class="rise" style="animation-delay:180ms">
            <x-card title="Omzet per Barber">
                <div class="space-y-3 py-1">
                    @forelse ($revenueByBarber as $row)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-medium text-neutral-700">{{ $row->barber }}</span>
                                <span class="text-neutral-500">Rp{{ number_format($row->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-neutral-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-neutral-700 to-neutral-400 transition-all duration-500" style="width: {{ max(2, round($row->total / $maxBarber * 100)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="py-10 text-center text-sm text-neutral-400">Belum ada data.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

    <div class="grid gap-6">
        <div class="rise" style="animation-delay:270ms">
            <x-card title="Performa per Paket">
                <table class="w-full text-sm text-center [&_th]:text-center [&_td]:text-center">
                    <thead class="border-b border-neutral-200 text-xs uppercase tracking-wider text-neutral-400">
                        <tr><th class="py-2.5 font-medium">Paket</th><th class="py-2.5 text-right font-medium">Jumlah</th><th class="py-2.5 text-right font-medium">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse ($revenueByService as $row)
                            <tr class="transition-colors hover:bg-neutral-50">
                                <td class="py-3 text-neutral-600">{{ $row->service }}</td>
                                <td class="py-3 text-right text-neutral-600">{{ $row->qty }}</td>
                                <td class="py-3 text-right font-medium text-neutral-900">Rp{{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-neutral-400">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
</x-dashboard-layout>
