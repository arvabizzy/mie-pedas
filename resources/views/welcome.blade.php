<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HotNoodle - Level Up Your Spice!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            500: '#DC2626', // Red-600 (Warna Pedas)
                            600: '#991B1B' // Red-800
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.4;
        }

        .float-anim {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-800 selection:bg-red-600 selection:text-white">

    <nav class="absolute w-full z-50 top-0 py-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-red-600 text-white w-10 h-10 flex items-center justify-center rounded-xl shadow-lg">
                    <i class="fa-solid fa-fire-burner text-xl"></i>
                </div>
                <span class="text-2xl font-bold tracking-tight text-gray-900">Hot<span
                        class="text-red-600">Noodle</span></span>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        {{-- Jika sudah login, cek role-nya --}}
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-red-600 transition">Ke Panel Admin</a>
                        @else
                            <a href="{{ route('kasir.dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-red-600 transition">Ke Kasir</a>
                        @endif

                        {{-- Tombol Logout Langsung di Homepage (Opsional tapi berguna) --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-red-600 ml-4">Logout</button>
                        </form>
                    @else
                        {{-- Jika belum login --}}
                        <a href="{{ route('login') }}"
                            class="hidden md:inline-block font-semibold text-gray-600 hover:text-red-600 transition">Login
                            Staff</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-full shadow-lg transition transform hover:-translate-y-1">
                                Daftar Admin
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="blob bg-red-400 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob bg-yellow-300 w-96 h-96 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center pt-20">
            <div class="text-center md:text-left z-10">
                <div
                    class="inline-block bg-red-100 text-red-600 px-4 py-1 rounded-full text-sm font-bold mb-4 border border-red-200">
                    🔥 Pedasnya Nampol!
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Level Pedas <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Sesukamu</span>,
                    Bayar Secepat Kilat.
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    HotNoodle POS: Solusi kasir modern khusus gerai mie pedas. Kelola stok cabe, level pedas, hingga
                    laporan harian dalam satu genggaman.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('login') }}"
                        class="bg-gray-900 text-white text-lg font-bold py-4 px-8 rounded-xl shadow-xl hover:bg-gray-800 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cash-register"></i> Mulai Kasir
                    </a>
                    <a href="#features"
                        class="bg-white text-gray-900 border border-gray-200 text-lg font-bold py-4 px-8 rounded-xl hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            <div class="relative z-10 hidden md:block">
                <div class="relative float-anim">
                    <div
                        class="absolute -top-10 -right-10 bg-white p-4 rounded-2xl shadow-2xl transform rotate-6 z-20 w-48 text-center">
                        <div class="text-red-600 text-3xl font-black italic">LEVEL 10+</div>
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-widest">Sangat Berbahaya!</div>
                    </div>

                    <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        class="rounded-[3rem] shadow-2xl border-4 border-white object-cover w-full h-[500px] transform -rotate-2">
                </div>
            </div>
        </div>
    </div>

    <section id="features" class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur HotNoodle POS</h2>
            <div class="h-1 w-20 bg-red-600 mx-auto rounded-full mb-12"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-gray-50 hover:shadow-xl transition">
                    <div
                        class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-2xl mb-6 mx-auto">
                        <i class="fa-solid fa-pepper-hot"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Custom Level</h3>
                    <p class="text-gray-600">Atur level pedas pelanggan mulai dari 0 sampai tak terhingga dengan mudah.
                    </p>
                </div>
                <div class="p-8 rounded-3xl bg-gray-50 hover:shadow-xl transition">
                    <div
                        class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl mb-6 mx-auto">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Stok Bahan</h3>
                    <p class="text-gray-600">Pantau stok mie, topping dimsum, dan minuman secara real-time.</p>
                </div>
                <div class="p-8 rounded-3xl bg-gray-50 hover:shadow-xl transition">
                    <div
                        class="w-14 h-14 bg-gray-800 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 mx-auto">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Struk Kilat</h3>
                    <p class="text-gray-600">Cetak struk pesanan dapur dan pelanggan hanya dalam satu klik.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 text-center">
        <p class="text-gray-400">© 2026 HotNoodle POS - Final Project RPL</p>
    </footer>
</body>

</html>
