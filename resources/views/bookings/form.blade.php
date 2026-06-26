<x-dashboard-layout :title="$booking->exists ? 'Edit Booking' : 'Booking Baru'">
    <x-page-header :title="$booking->exists ? 'Edit Booking' : 'Booking Baru'"
                   :subtitle="$booking->exists ? 'Perbarui detail booking.' : 'Buat jadwal booking baru.'" />

    <x-card class="rise relative w-full max-w-3xl overflow-hidden" style="animation-delay:90ms">
        <x-ornament variant="corner" tone="dark" class="absolute top-4 right-4 h-12 w-12 opacity-70" />
        <form method="POST" action="{{ $booking->exists ? route('bookings.update', $booking) : route('bookings.store') }}" class="space-y-5">
            @csrf
            @if ($booking->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @include('partials.field', ['name' => 'customer_name', 'label' => 'Nama Customer', 'value' => $booking->customer_name])
                @include('partials.field', ['name' => 'customer_phone', 'label' => 'Telepon', 'value' => $booking->customer_phone])
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Barber</label>
                    <select name="barber_id" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                        <option value="">Pilih barber</option>
                        @foreach ($barbers as $barber)
                            <option value="{{ $barber->id }}" @selected(old('barber_id', $booking->barber_id) == $barber->id)>{{ $barber->name }}</option>
                        @endforeach
                    </select>
                    @error('barber_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Paket</label>
                    <select name="service_id" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                        <option value="">Pilih paket</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $booking->service_id) == $service->id)>
                                {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Jadwal</label>
                    <input type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', optional($booking->scheduled_at)->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                    @error('scheduled_at') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                        @foreach (['pending' => 'Menunggu', 'in_progress' => 'Dikerjakan', 'done' => 'Selesai', 'cancelled' => 'Batal'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $booking->status ?? 'pending') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">{{ old('notes', $booking->notes) }}</textarea>
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button class="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
                <a href="{{ route('bookings.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-center text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Batal</a>
            </div>
        </form>
    </x-card>
</x-dashboard-layout>
