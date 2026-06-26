<x-dashboard-layout title="Pengaturan">
    <x-page-header title="Pengaturan Akun" subtitle="Kelola profil, kata sandi, dan akun kamu." />

    <div class="mx-auto max-w-5xl space-y-6">
        {{-- Informasi Profil --}}
        <section class="rise grid gap-4 lg:grid-cols-3 lg:gap-8" style="animation-delay:90ms">
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700"><x-icon name="user" class="h-5 w-5" /></span>
                    <h3 class="font-serif text-lg font-bold text-neutral-900">Informasi Profil</h3>
                </div>
                <p class="mt-2 text-sm text-neutral-500">Perbarui nama dan alamat email akunmu.</p>
            </div>
            <div class="lg:col-span-2">
                <x-card>@include('profile.partials.update-profile-information-form')</x-card>
            </div>
        </section>

        {{-- Ubah Kata Sandi --}}
        <section class="rise grid gap-4 lg:grid-cols-3 lg:gap-8" style="animation-delay:180ms">
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700"><x-icon name="lock" class="h-5 w-5" /></span>
                    <h3 class="font-serif text-lg font-bold text-neutral-900">Ubah Kata Sandi</h3>
                </div>
                <p class="mt-2 text-sm text-neutral-500">Gunakan kata sandi yang panjang dan acak agar tetap aman.</p>
            </div>
            <div class="lg:col-span-2">
                <x-card>@include('profile.partials.update-password-form')</x-card>
            </div>
        </section>

        {{-- Metode Pembayaran (khusus owner) --}}
        @if (auth()->user()->isOwner())
            <section class="rise grid gap-4 lg:grid-cols-3 lg:gap-8" style="animation-delay:240ms">
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700"><x-icon name="payment" class="h-5 w-5" /></span>
                        <h3 class="font-serif text-lg font-bold text-neutral-900">Metode Pembayaran</h3>
                    </div>
                    <p class="mt-2 text-sm text-neutral-500">Atur metode yang bisa dipilih kasir saat pembayaran (mis. Tunai, QRIS, Debit BCA).</p>
                </div>
                <div class="lg:col-span-2">
                    <x-card>
                        <form method="POST" action="{{ route('owner.payment-methods.store') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="mb-1 block text-sm font-medium text-neutral-700">Nama Metode</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="mis. Debit BCA"
                                       class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800">
                                @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">
                                <x-icon name="plus" class="h-4 w-4" /> Tambah
                            </button>
                        </form>

                        <div class="divide-y divide-neutral-100 border-t border-neutral-200">
                            @forelse (\App\Models\PaymentMethod::orderBy('name')->get() as $method)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="font-medium text-neutral-900">{{ $method->name }}</span>
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $method->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-200 text-neutral-600' }}">
                                            {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <form method="POST" action="{{ route('owner.payment-methods.update', $method) }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="name" value="{{ $method->name }}">
                                            <input type="hidden" name="is_active" value="{{ $method->is_active ? 0 : 1 }}">
                                            <button class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $method->is_active ? 'text-neutral-600 hover:bg-neutral-100' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                                {{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('owner.payment-methods.destroy', $method) }}" onsubmit="return confirm('Hapus metode ini?')">
                                            @csrf @method('DELETE')
                                            <button title="Hapus" class="grid h-8 w-8 place-items-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-neutral-400">Belum ada metode pembayaran.</p>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </section>
        @endif

        {{-- Hapus Akun --}}
        <section class="rise grid gap-4 lg:grid-cols-3 lg:gap-8" style="animation-delay:270ms">
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-rose-100 text-rose-600"><x-icon name="trash" class="h-5 w-5" /></span>
                    <h3 class="font-serif text-lg font-bold text-neutral-900">Hapus Akun</h3>
                </div>
                <p class="mt-2 text-sm text-neutral-500">Hapus akun beserta seluruh datanya secara permanen.</p>
            </div>
            <div class="lg:col-span-2">
                <x-card class="border-rose-200">@include('profile.partials.delete-user-form')</x-card>
            </div>
        </section>
    </div>
</x-dashboard-layout>
