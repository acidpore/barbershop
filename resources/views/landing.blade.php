<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>DOS. Barber & Supplies — Barbershop Pria Jakarta Selatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .9s cubic-bezier(.16, 1, .3, 1), transform .9s cubic-bezier(.16, 1, .3, 1);
            transition-delay: var(--reveal-delay, 0ms);
            will-change: opacity, transform;
        }
        .reveal.is-visible {
            opacity: 1;
            transform: none;
        }
        .hero-zoom {
            transform: scale(1.12);
            transition: transform 2.4s cubic-bezier(.16, 1, .3, 1);
        }
        .hero-zoom.is-visible {
            transform: scale(1);
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal, .hero-zoom {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 font-sans antialiased selection:bg-neutral-900 selection:text-white overflow-x-hidden">

    {{-- Header --}}
    <header class="absolute top-0 left-0 right-0 z-50 px-5 py-5 sm:px-6 sm:py-6 lg:px-12">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center gap-2 text-white">
                <svg class="h-7 w-7 sm:h-8 sm:w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="14.48" x2="20" y2="20"/><line x1="8.12" y1="8.12" x2="12" y2="12"/>
                </svg>
                <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight">DOS.</span>
            </div>
            <nav class="hidden md:flex items-center gap-8 text-white/90">
                <a href="#services" class="text-sm font-medium hover:text-white transition-colors">Layanan</a>
                <a href="#about" class="text-sm font-medium hover:text-white transition-colors">Tentang</a>
                <a href="#gallery" class="text-sm font-medium hover:text-white transition-colors">Galeri</a>
            </nav>
            <div class="flex items-center gap-3 sm:gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-black px-4 sm:px-6 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-semibold hover:bg-neutral-200 transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:block text-sm font-medium text-white hover:text-white/80 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-white text-black px-4 sm:px-6 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-semibold hover:bg-neutral-200 transition-colors whitespace-nowrap">Booking Sekarang</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{-- Hero full-screen (svh agar pas di iPhone/Safari) --}}
        <section class="relative flex items-center justify-center min-h-screen min-h-[100svh] pt-28 pb-16 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=2074&q=80"
                     alt="Barbershop interior" class="hero-zoom w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/60"></div>
            </div>
            <div class="relative z-10 mx-auto max-w-7xl px-5 sm:px-6 lg:px-12 text-center text-white">
                <h1 class="reveal font-serif text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight max-w-4xl mx-auto leading-[1.08]">
                    Seni Grooming Pria Masa Kini.
                </h1>
                <p class="reveal mt-6 sm:mt-8 text-base sm:text-lg md:text-xl text-white/80 max-w-2xl mx-auto font-light" style="--reveal-delay:120ms">
                    Potongan rambut presisi, cukur jenggot dengan handuk hangat, dan layanan grooming premium dalam suasana yang nyaman dan elegan.
                </p>
                <div class="reveal mt-10 sm:mt-12 flex flex-col sm:flex-row items-center justify-center gap-4" style="--reveal-delay:240ms">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white text-black px-8 py-4 rounded-full text-base font-semibold hover:bg-neutral-200 transition-colors text-center">
                        Booking Sekarang
                    </a>
                    <a href="#services" class="w-full sm:w-auto px-8 py-4 rounded-full text-base font-semibold border border-white/30 hover:bg-white/10 transition-colors text-center">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </section>

        {{-- Experience --}}
        <section id="about" class="py-20 sm:py-28 lg:py-32 bg-neutral-50">
            <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-12">
                <div class="grid lg:grid-cols-2 gap-10 sm:gap-14 lg:gap-16 items-center">
                    <div class="reveal order-2 lg:order-1 relative">
                        <div class="aspect-[4/5] rounded-2xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1605497788044-5a32c7078486?auto=format&fit=crop&w=1200&q=80"
                                 alt="Master barber working" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="reveal order-1 lg:order-2" style="--reveal-delay:120ms">
                        <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6 sm:mb-8 text-neutral-900 leading-tight">
                            Lebih dari <br>sekadar potong rambut.
                        </h2>
                        <p class="text-neutral-600 text-base sm:text-lg mb-5 sm:mb-6 leading-relaxed">
                            DOS. — diambil dari kata Spanyol untuk "dua" — mewakili dualitas penting dalam grooming pria: Tradisi dan Inovasi. Jika dulu grooming sekadar ritual sederhana, DOS. mengangkatnya ke ranah presisi.
                        </p>
                        <p class="text-neutral-600 text-base sm:text-lg mb-8 sm:mb-10 leading-relaxed">
                            Kami percaya setiap helai rambut punya cerita, dan setiap wajah layak mendapat perhatian detail sekaligus sentuhan estetika. Datang, santai, dan rasakan pengalaman grooming terbaik.
                        </p>
                        <a href="#about" class="inline-block border-b border-neutral-900 text-neutral-900 pb-1 font-semibold hover:text-neutral-600 hover:border-neutral-600 transition-colors uppercase tracking-widest text-sm">
                            Tentang Kami
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Services --}}
        <section id="services" class="py-20 sm:py-28 lg:py-32 bg-neutral-900 text-white">
            <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-12">
                <div class="reveal flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16 lg:mb-20 gap-6 sm:gap-8">
                    <div class="max-w-2xl">
                        <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">Layanan Unggulan</h2>
                        <p class="text-neutral-400 text-base sm:text-lg">Tingkatkan penampilanmu dengan layanan premium kami, dikerjakan oleh barber profesional dengan produk berkualitas.</p>
                    </div>
                    <a href="{{ route('register') }}" class="hidden md:inline-block px-8 py-4 rounded-full text-sm font-semibold bg-white text-neutral-900 hover:bg-neutral-200 transition-colors shrink-0">
                        Lihat Semua Layanan
                    </a>
                </div>

                @php
                    $serviceImages = [
                        'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
                    ];
                @endphp

                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                    @forelse ($services as $index => $service)
                        <div class="reveal group cursor-pointer" style="--reveal-delay:{{ ($index % 3) * 120 }}ms">
                            <div class="overflow-hidden rounded-2xl mb-6 sm:mb-8 aspect-[3/4] relative">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
                                <img src="{{ $serviceImages[$index % count($serviceImages)] }}" alt="{{ $service->name }}"
                                     loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="flex justify-between items-start mb-3 sm:mb-4 gap-4">
                                <h3 class="font-serif text-xl sm:text-2xl font-semibold group-hover:text-neutral-300 transition-colors">{{ $service->name }}</h3>
                                <span class="text-base sm:text-lg font-medium whitespace-nowrap">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-neutral-400 leading-relaxed text-sm sm:text-base">
                                {{ $service->description ?: 'Layanan grooming profesional dengan produk premium untuk hasil terbaik.' }}
                            </p>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-neutral-500">Belum ada layanan tersedia.</p>
                    @endforelse
                </div>

                <a href="{{ route('register') }}" class="block text-center w-full mt-10 sm:mt-12 md:hidden px-8 py-4 rounded-full text-sm font-semibold bg-white text-neutral-900 hover:bg-neutral-200 transition-colors">
                    Lihat Semua Layanan
                </a>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-neutral-950 text-white pt-16 sm:pt-20 pb-10">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-12">
            <div class="reveal grid grid-cols-1 md:grid-cols-4 gap-10 sm:gap-12 mb-12 sm:mb-16">
                <div class="col-span-1 md:col-span-2">
                    <span class="font-serif text-2xl font-bold tracking-tight block mb-6">DOS. Barber &amp; Supplies</span>
                    <p class="text-neutral-400 max-w-sm leading-relaxed">
                        Barbershop pria di Jakarta Selatan yang memadukan tradisi dan inovasi — grooming presisi dengan sentuhan estetika.
                    </p>
                    <a href="https://www.instagram.com/dos.barberco/" target="_blank" rel="noopener"
                       class="mt-6 inline-flex items-center gap-2 text-neutral-400 hover:text-white transition-colors">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s0-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.21 8.8 2.2 12 2.2zm0 3.65A6.15 6.15 0 1 0 18.15 12 6.15 6.15 0 0 0 12 5.85zm0 10.15A4 4 0 1 1 16 12a4 4 0 0 1-4 4zm6.4-10.55a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44z"/></svg>
                        @dos.barberco
                    </a>
                </div>
                <div>
                    <h4 class="font-semibold mb-6">Kontak</h4>
                    <ul class="space-y-4 text-neutral-400">
                        <li>No.147 Jl. Asem Baris Raya</li>
                        <li>Jakarta Selatan, DKI Jakarta 12830</li>
                        <li><a href="tel:08175260612" class="hover:text-white transition-colors">0817-5260-612</a></li>
                        <li><a href="mailto:dos.barberco@gmail.com" class="hover:text-white transition-colors">dos.barberco@gmail.com</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-6">Jam Buka</h4>
                    <ul class="space-y-4 text-neutral-400">
                        <li class="flex justify-between"><span>Senin - Minggu</span><span>10.00 - 22.00</span></li>
                        <li class="text-neutral-500 text-sm">Buka setiap hari</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-neutral-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-neutral-500 text-sm text-center md:text-left">&copy; {{ date('Y') }} DOS. Barber &amp; Supplies. Hak cipta dilindungi.</p>
                <div class="flex gap-6 text-sm text-neutral-500">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Scroll-reveal halus per elemen; sekali tampil lalu berhenti diobservasi.
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

        // Hero animasi langsung saat load (di atas lipatan, tidak menunggu scroll).
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.hero-zoom').forEach((el) => el.classList.add('is-visible'));
            document.querySelectorAll('section:first-of-type .reveal').forEach((el) => el.classList.add('is-visible'));
        });
    </script>
</body>
</html>
