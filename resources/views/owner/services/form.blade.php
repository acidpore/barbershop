<x-dashboard-layout :title="$service->exists ? 'Edit Paket' : 'Tambah Paket'">
    <x-page-header :title="$service->exists ? 'Edit Paket' : 'Tambah Paket'"
                   :subtitle="$service->exists ? 'Perbarui detail paket layanan.' : 'Buat paket layanan baru.'" />

    <x-card class="rise relative w-full max-w-3xl overflow-hidden" style="animation-delay:90ms">
        <x-ornament variant="corner" tone="dark" class="absolute top-4 right-4 h-12 w-12 opacity-70" />
        <form method="POST" action="{{ $service->exists ? route('owner.services.update', $service) : route('owner.services.store') }}" class="space-y-5">
            @csrf
            @if ($service->exists) @method('PUT') @endif

            @include('partials.field', ['name' => 'name', 'label' => 'Nama Paket', 'value' => $service->name])

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">{{ old('description', $service->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @include('partials.field', ['name' => 'price', 'label' => 'Harga (Rp)', 'type' => 'number', 'value' => $service->price])
                @include('partials.field', ['name' => 'duration_minutes', 'label' => 'Durasi (menit)', 'type' => 'number', 'value' => $service->duration_minutes ?? 30])
            </div>

            <label class="flex items-center gap-2 text-sm text-neutral-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true)) class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-800">
                Paket aktif
            </label>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button class="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
                <a href="{{ route('owner.services.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-center text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Batal</a>
            </div>
        </form>
    </x-card>
</x-dashboard-layout>
