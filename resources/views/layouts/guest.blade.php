@props(['title' => 'Selamat Datang', 'subtitle' => null])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — DOS.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-950 font-sans antialiased text-neutral-900">
    <div class="min-h-screen lg:grid lg:grid-cols-5">

        {{-- Panel visual (kiri, 60%) --}}
        <div class="relative hidden lg:block lg:col-span-3 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=1600&q=80"
                 alt="DOS. Barbershop" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-neutral-950/65"></div>
            {{-- Ornamen: barber-pole di tepi kiri + bingkai sudut --}}
            <x-ornament variant="pole" class="absolute inset-y-0 left-0 w-2" />
            <x-ornament variant="corner" class="absolute top-8 right-8 h-16 w-16" />
            <x-ornament variant="corner" class="absolute bottom-8 left-8 h-16 w-16 rotate-180" />
            <div class="relative z-10 flex h-full flex-col justify-between p-12">
                <a href="{{ route('landing') }}" class="flex items-center gap-2 text-white w-fit">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/>
                        <line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>
                    </svg>
                    <span class="font-serif text-2xl font-bold tracking-tight">DOS.</span>
                </a>
                <div>
                    <span class="mb-6 block h-1 w-12 rounded-full bg-white/80"></span>
                    <h2 class="font-serif text-4xl font-bold text-white leading-tight max-w-md">
                        Tampil rapi, percaya diri setiap hari.
                    </h2>
                    <p class="mt-4 text-white/70 max-w-md">
                        Booking barber pilihanmu dan nikmati layanan grooming premium di DOS..
                    </p>
                </div>
            </div>
        </div>

        {{-- Panel form (kanan, 40%) --}}
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-neutral-50 px-5 py-10 sm:px-8 lg:col-span-2">
            {{-- Ornamen panel form --}}
            <x-ornament variant="dots" tone="dark" class="absolute -top-6 -right-6 h-40 w-40 opacity-60" />
            <x-ornament variant="dots" tone="dark" class="absolute -bottom-6 -left-6 h-32 w-32 opacity-50" />
            <x-ornament variant="corner" tone="dark" class="absolute bottom-6 right-6 h-12 w-12 rotate-180 opacity-70" />
            <x-ornament variant="pole" tone="dark" class="absolute inset-y-0 left-0 w-1 opacity-70" />
            <a href="{{ route('landing') }}"
               class="absolute top-5 right-5 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 hover:text-neutral-900 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke beranda
            </a>
            <div class="relative z-10 w-full max-w-md">
                {{-- Brand untuk layar kecil --}}
                <a href="{{ route('landing') }}" class="lg:hidden flex items-center justify-center gap-2 text-neutral-900 mb-8">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/>
                        <line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>
                    </svg>
                    <span class="font-serif text-2xl font-bold tracking-tight">DOS.</span>
                </a>

                <div class="mb-8">
                    <h1 class="font-serif text-3xl font-bold text-neutral-900">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="mt-2 text-neutral-500">{{ $subtitle }}</p>
                    @endif
                    <x-ornament variant="divider" tone="dark" class="mt-6" />
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
