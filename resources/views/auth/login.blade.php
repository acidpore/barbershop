<x-guest-layout title="Masuk" subtitle="Selamat datang kembali. Silakan masuk ke akunmu.">

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('email') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-neutral-700 mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-lg border-neutral-300 focus:border-neutral-900 focus:ring-neutral-900">
            @error('password') <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">
                <span class="ms-2 text-sm text-neutral-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-neutral-600 hover:text-neutral-900 underline">Lupa sandi?</a>
            @endif
        </div>

        <button class="w-full rounded-lg bg-neutral-900 px-6 py-3.5 font-semibold text-white hover:bg-neutral-800 transition-colors">
            Masuk
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-neutral-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-neutral-900 hover:underline">Daftar di sini</a>
    </p>
</x-guest-layout>
