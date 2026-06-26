<x-dashboard-layout title="Proses Pembayaran">
    <x-card class="max-w-lg">
        <div class="space-y-2 text-sm border-b border-neutral-200 pb-4 mb-4">
            <div class="flex justify-between"><span class="text-neutral-500">Customer</span><span class="font-medium">{{ $booking->customer_name }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-500">Barber</span><span class="font-medium">{{ $booking->barber->name }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-500">Paket</span><span class="font-medium">{{ $booking->service->name }}</span></div>
            <div class="flex justify-between text-lg"><span class="text-neutral-500">Total</span><span class="font-bold text-neutral-900">Rp{{ number_format($booking->service->price, 0, ',', '.') }}</span></div>
        </div>

        <form method="POST" action="{{ route('payments.store', $booking) }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Metode Pembayaran</label>
                <select name="method" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                    @forelse ($methods as $method)
                        <option value="{{ $method->name }}">{{ $method->name }}</option>
                    @empty
                        <option value="Tunai">Tunai</option>
                    @endforelse
                </select>
                @error('method') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Konfirmasi Pembayaran</button>
                <a href="{{ route('payments.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Batal</a>
            </div>
        </form>
    </x-card>
</x-dashboard-layout>
