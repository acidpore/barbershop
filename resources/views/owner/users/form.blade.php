<x-dashboard-layout :title="$user->exists ? 'Edit User' : 'Tambah User'">
    <x-page-header :title="$user->exists ? 'Edit User' : 'Tambah User'"
                   :subtitle="$user->exists ? 'Perbarui data akun pengguna.' : 'Buat akun Owner, Kasir, atau Barber baru.'" />

    <x-card class="rise relative w-full max-w-3xl overflow-hidden" style="animation-delay:90ms">
        <x-ornament variant="corner" tone="dark" class="absolute top-4 right-4 h-12 w-12 opacity-70" />
        <form method="POST" action="{{ $user->exists ? route('owner.users.update', $user) : route('owner.users.store') }}" class="space-y-5">
            @csrf
            @if ($user->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @include('partials.field', ['name' => 'name', 'label' => 'Nama', 'value' => $user->name])
                @include('partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $user->email])
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Role</label>
                    <select name="role" class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                        @foreach (['owner' => 'Owner', 'kasir' => 'Kasir', 'barber' => 'Barber'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                @include('partials.field', ['name' => 'phone', 'label' => 'Telepon', 'value' => $user->phone])
            </div>

            @include('partials.field', ['name' => 'password', 'label' => $user->exists ? 'Password (kosongkan jika tidak diubah)' : 'Password', 'type' => 'password', 'value' => ''])

            <label class="flex items-center gap-2 text-sm text-neutral-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-800">
                Akun aktif
            </label>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button class="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">Simpan</button>
                <a href="{{ route('owner.users.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-center text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Batal</a>
            </div>
        </form>
    </x-card>
</x-dashboard-layout>
