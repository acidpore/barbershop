<x-dashboard-layout title="Pembayaran">
    <x-card title="Menunggu Pembayaran">
        <div class="overflow-x-auto">
            <table class="rtable w-full text-sm text-center [&_th]:text-center sm:[&_td]:text-center">
                <thead class="border-b border-neutral-200 text-xs uppercase tracking-wider text-neutral-400">
                    <tr>
                        <th class="py-2.5 pr-4 font-medium">Customer</th>
                        <th class="py-2.5 pr-4 font-medium">Barber</th>
                        <th class="py-2.5 pr-4 font-medium">Paket</th>
                        <th class="py-2.5 pr-4 font-medium">Harga</th>
                        <th class="py-2.5 pr-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($unpaid as $booking)
                        <tr class="transition-colors hover:bg-neutral-50">
                            <td data-label="Customer" class="py-3 pr-4 font-medium text-neutral-900">{{ $booking->customer_name }}</td>
                            <td data-label="Barber" class="py-3 pr-4 text-neutral-600">{{ $booking->barber->name }}</td>
                            <td data-label="Paket" class="py-3 pr-4 text-neutral-600">{{ $booking->service->name }}</td>
                            <td data-label="Harga" class="py-3 pr-4 text-neutral-600">Rp{{ number_format($booking->service->price, 0, ',', '.') }}</td>
                            <td data-label="Aksi" class="py-3 pr-4">
                                <a href="{{ route('payments.create', $booking) }}" class="rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-neutral-800">Proses Bayar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-neutral-400">Tidak ada tagihan menunggu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <x-card title="Pembayaran Terbaru" class="mt-6">
        <div class="overflow-x-auto">
            <table class="rtable w-full text-sm text-center [&_th]:text-center sm:[&_td]:text-center">
                <thead class="border-b border-neutral-200 text-xs uppercase tracking-wider text-neutral-400">
                    <tr>
                        <th class="py-2.5 pr-4 font-medium">Waktu</th>
                        <th class="py-2.5 pr-4 font-medium">Paket</th>
                        <th class="py-2.5 pr-4 font-medium">Metode</th>
                        <th class="py-2.5 pr-4 font-medium">Kasir</th>
                        <th class="py-2.5 pr-4 font-medium">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($recent as $payment)
                        <tr class="transition-colors hover:bg-neutral-50">
                            <td data-label="Waktu" class="py-3 pr-4 text-neutral-600">{{ $payment->paid_at->format('d M Y H:i') }}</td>
                            <td data-label="Paket" class="py-3 pr-4 text-neutral-600">{{ $payment->booking->service->name }}</td>
                            <td data-label="Metode" class="py-3 pr-4 capitalize text-neutral-600">{{ $payment->method }}</td>
                            <td data-label="Kasir" class="py-3 pr-4 text-neutral-600">{{ $payment->cashier->name }}</td>
                            <td data-label="Jumlah" class="py-3 pr-4 font-medium text-neutral-900">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-neutral-400">Belum ada pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-dashboard-layout>
