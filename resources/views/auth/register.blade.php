<x-guest-layout title="Daftar" subtitle="Buat akun untuk mulai booking layanan DOS.">

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('name') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('email') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-neutral-700 mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('password') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('password_confirmation') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <button class="w-full rounded-lg bg-neutral-900 px-6 py-3.5 font-semibold text-white hover:bg-neutral-800 transition-colors">
            Daftar
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-neutral-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-neutral-900 hover:underline">Masuk di sini</a>
    </p>
</x-guest-layout>
