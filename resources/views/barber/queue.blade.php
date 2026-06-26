<x-dashboard-layout title="Antrian Saya">
    <p class="text-sm text-neutral-500 mb-5">Daftar booking yang ditugaskan kepada Anda.</p>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($bookings as $booking)
            <x-card>
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold text-neutral-900">{{ $booking->customer_name }}</div>
                        <div class="text-sm text-neutral-500">{{ $booking->customer_phone ?? '-' }}</div>
                    </div>
                    <x-badge :status="$booking->status" />
                </div>
                <div class="mt-3 text-sm text-neutral-600">
                    <div>{{ $booking->service->name }}</div>
                    <div class="text-neutral-400">{{ $booking->scheduled_at->format('d M Y H:i') }}</div>
                </div>
                @if ($booking->notes)
                    <p class="mt-2 text-sm text-neutral-500 italic">{{ $booking->notes }}</p>
                @endif

                <form method="POST" action="{{ route('barber.bookings.status', $booking) }}" class="mt-4 flex gap-2">
                    @csrf @method('PATCH')
                    @if ($booking->status === 'pending')
                        <button name="status" value="in_progress" class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-500">Mulai</button>
                    @endif
                    @if ($booking->status === 'in_progress')
                        <button name="status" value="done" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-500">Selesai</button>
                    @endif
                    <button name="status" value="cancelled" class="rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50" onclick="return confirm('Batalkan booking ini?')">Batal</button>
                </form>
            </x-card>
        @empty
            <p class="text-neutral-400">Tidak ada antrian saat ini.</p>
        @endforelse
    </div>
</x-dashboard-layout>
