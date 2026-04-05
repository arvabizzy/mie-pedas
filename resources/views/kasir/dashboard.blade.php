<x-app-layout>
    <x-slot name="title">
        Kasir HotNoodle | Order Baru 🛒
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terminal Kasir HotNoodle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-xl shadow-md font-bold text-center mb-6 animate-bounce">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">

                {{-- KIRI: DAFTAR MENU & FILTER --}}
                <div class="md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between border-b pb-4 mb-4 gap-4">
                            <h3 class="text-lg font-black text-gray-800 uppercase italic flex-shrink-0 flex items-center gap-2">
                                <span class="bg-red-600 w-2 h-6 rounded-full"></span>
                                Menu HotNoodle
                            </h3>

                            <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar">
                                <button onclick="filterMenu('all')" class="cat-btn active bg-red-600 text-white px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap shadow-md">Semua</button>
                                <button onclick="filterMenu('mie')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Mie</button>
                                <button onclick="filterMenu('snack')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Snack</button>
                                <button onclick="filterMenu('minuman')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Minuman</button>
                                <button onclick="filterMenu('paket')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Paket</button>
                            </div>
                        </div>

                        <div id="menu-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[65vh] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-gray-200">
                            @foreach ($menus as $menu)
                                <div class="menu-item border-2 border-transparent p-4 rounded-3xl text-center bg-gray-50 hover:border-red-500 hover:bg-white hover:shadow-xl transition-all group flex flex-col justify-between"
                                     data-category="{{ strtolower($menu->kategori) }}">
                                    <div>
                                        <div class="relative w-full h-36 overflow-hidden rounded-2xl mb-3 shadow-inner">
                                            @if ($menu->foto)
                                                <img src="{{ asset('storage/' . $menu->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                            @endif
                                            <span class="absolute top-2 right-2 bg-white/80 backdrop-blur-md px-2 py-1 rounded-lg text-[9px] font-black text-gray-600 border border-white">STOK: {{ $menu->stok }}</span>
                                        </div>

                                        <p class="font-black text-gray-800 line-clamp-1 group-hover:text-red-600 transition">{{ $menu->nama_menu }}</p>
                                        <p class="text-red-600 font-black text-lg mb-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        <p class="text-[11px] text-gray-500 mb-4 line-clamp-2 min-h-[2.75rem]">
                                            {{ $menu->deskripsi ?? 'Nikmati kelezatan menu spesial dari HotNoodle.' }}
                                        </p>
                                    </div>

                                    <form action="{{ route('kasir.cart.add', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white py-3 rounded-2xl hover:bg-red-700 w-full font-black shadow-lg shadow-red-100 transition transform active:scale-95 text-xs uppercase tracking-widest">
                                            + Tambah ke Order
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- KANAN: PESANAN & PEMBAYARAN --}}
                <div class="md:w-1/3">
                    <div class="bg-white shadow-2xl sm:rounded-3xl p-6 border-t-[12px] border-red-600 sticky top-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-black flex items-center gap-2 text-gray-800 uppercase italic">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                Struk Order
                            </h3>
                        </div>

                        <div class="space-y-4 mb-6 max-h-[30vh] overflow-y-auto pr-2 scrollbar-thin">
                            @php $subtotal = 0 @endphp
                            @forelse (session('cart', []) as $id => $details)
                                @php $subtotal += ($details['harga'] ?? 0) * ($details['jumlah'] ?? 0) @endphp
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-gray-800">{{ $details['nama'] ?? 'Item' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Rp {{ number_format($details['harga'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                            <form action="{{ route('kasir.cart.decrease', $id) }}" method="POST">
                                                @csrf<button class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:bg-red-50 hover:text-red-600 transition">-</button>
                                            </form>
                                            <span class="w-6 text-center font-black text-sm text-gray-800">{{ $details['jumlah'] ?? 0 }}</span>
                                            <form action="{{ route('kasir.cart.add', $id) }}" method="POST">
                                                @csrf<button class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:bg-green-50 hover:text-green-600 transition">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 border-2 border-dashed border-gray-100 rounded-3xl">
                                    <p class="text-5xl mb-4 grayscale opacity-30">🍜</p>
                                    <p class="text-gray-400 italic text-sm font-medium">Belum ada pesanan masuk.</p>
                                </div>
                            @endforelse
                        </div>

                        @if (count(session('cart', [])) > 0)
                            <div class="border-t-2 border-dashed border-gray-200 pt-6 space-y-3">
                                @php
                                    $pajak = $subtotal * 0.1;
                                    $total_akhir = $subtotal + $pajak;
                                @endphp
                                <div class="flex justify-between text-sm text-gray-400 font-bold uppercase tracking-wider">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-400 font-bold uppercase tracking-wider">
                                    <span>PPN (10%)</span>
                                    <span>Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-2xl font-black mt-4 py-4 border-y border-gray-100 text-gray-900 bg-gray-50/50 -mx-6 px-6">
                                    <span>TOTAL</span>
                                    <span class="text-red-600">Rp {{ number_format($total_akhir, 0, ',', '.') }}</span>
                                </div>

                                <form action="{{ Route::has('kasir.transaction.store') ? route('kasir.transaction.store') : (Route::has('admin.transaksi.store') ? route('admin.transaksi.store') : '#') }}" method="POST" class="mt-6 space-y-4">
                                    @csrf
                                    <div>
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Customer</label>
                                        <input type="text" name="nama_pelanggan" class="w-full border-2 border-gray-100 rounded-xl p-3 text-sm focus:border-red-500 focus:ring-0 transition font-bold" placeholder="Input Nama..." required>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Uang Cash (Rp)</label>
                                        <input type="number" name="bayar" class="w-full rounded-2xl border-2 border-gray-100 font-black text-3xl py-4 text-green-600 focus:border-green-500 focus:ring-0 text-center shadow-inner" placeholder="0" required min="{{ $total_akhir }}">
                                    </div>
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-5 rounded-2xl font-black shadow-xl shadow-red-200 transition transform active:scale-95 uppercase tracking-widest text-lg">
                                        CETAK STRUK & SELESAI
                                    </button>
                                </form>
                                <a href="{{ route('kasir.cart.clear') }}" class="block text-center text-xs font-bold text-gray-300 mt-4 hover:text-red-500 transition-colors uppercase tracking-widest">Batal / Hapus Semua</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS UTAMA --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fungsi Filter Kategori
        function filterMenu(category) {
            const buttons = document.querySelectorAll('.cat-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-red-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });

            const activeBtn = event.currentTarget;
            activeBtn.classList.add('bg-red-600', 'text-white', 'shadow-md');
            activeBtn.classList.remove('bg-gray-100', 'text-gray-600');

            const items = document.querySelectorAll('.menu-item');
            items.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'flex';
                    item.classList.add('animate-fade-in');
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Logic SweetAlert Struk Otomatis dengan Jam & Ucapan
        @if (session('struk'))
            document.addEventListener('DOMContentLoaded', function() {
                let struk = @json(session('struk'));
                let detailHtml = '';

                // Ambil waktu sekarang di sisi client
                const sekarang = new Date();
                const waktuString = sekarang.toLocaleString('id-ID', {
                    day: 'numeric', month: 'numeric', year: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                }).replace(/\./g, ':');

                if(struk.transaction_details) {
                    struk.transaction_details.forEach(item => {
                        detailHtml += `
                        <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px">
                            <span style="flex:1; text-align:left">${item.menu ? item.menu.nama_menu : 'Menu'}</span>
                            <span style="width:40px; text-align:center">x${item.jumlah}</span>
                            <span style="width:90px; text-align:right">Rp ${(parseInt(item.subtotal)).toLocaleString('id-ID')}</span>
                        </div>`;
                    });
                }

                Swal.fire({
                    title: '<span style="color:#e11d48; font-weight:900">HOTNOODLE 🍜</span>',
                    html: `
                    <div style="text-align:left; font-family:'Courier New', monospace; border: 2px solid #f1f1f1; padding: 20px; border-radius: 15px; color:#333">
                        <center><p style="margin:0; font-size:11px; letter-spacing:1px">STRUK PEMBAYARAN SELESAI</p></center>
                        <hr style="border-top:1px dashed #ccc; margin:15px 0">

                        <div style="font-size:12px">
                            <p style="margin:0">Waktu: <b>${waktuString}</b></p>
                            <p style="margin:0">No: <b>TRX-${struk.id}</b></p>
                            <p style="margin:0">Pelanggan: <b>${struk.nama_pelanggan}</b></p>
                        </div>

                        <hr style="border-top:1px dashed #ccc; margin:15px 0">
                        ${detailHtml}
                        <hr style="border-top:1px dashed #ccc; margin:15px 0">

                        <div style="display:flex; justify-content:space-between; font-weight:bold; font-size:15px">
                            <span>TOTAL:</span><span>Rp ${(parseInt(struk.total_harga)).toLocaleString('id-ID')}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:5px; color:#666; font-size:12px">
                            <span>Bayar:</span><span>Rp ${(parseInt(struk.bayar)).toLocaleString('id-ID')}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:17px; font-weight:900; color:#16a34a; border-top:1px solid #eee; margin-top:5px; padding-top:5px">
                            <span>KEMBALI:</span><span>Rp ${(parseInt(struk.kembalian)).toLocaleString('id-ID')}</span>
                        </div>

                        <hr style="border-top:1px dashed #ccc; margin:15px 0">
                        <center style="font-size:12px; font-style:italic; color:#888">
                            <p style="margin:0">Terima kasih!</p>
                            <p style="margin:0">Selamat Menikmati!</p>
                        </center>
                    </div>
                    `,
                    confirmButtonText: 'TRANSAKSI BARU',
                    confirmButtonColor: '#e11d48',
                    allowOutsideClick: false
                });
            });
        @endif
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #f3f4f6; border-radius: 10px; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    </style>
</x-app-layout>
