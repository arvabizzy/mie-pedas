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

            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-xl shadow-md font-bold text-center mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between border-b pb-4 mb-4 gap-4">
                            <h3 class="text-lg font-black text-gray-800 uppercase italic flex-shrink-0">Menu HotNoodle</h3>

                            <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar">
                                <button onclick="filterMenu('all')" class="cat-btn active bg-red-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Semua</button>
                                <button onclick="filterMenu('mie')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Mie</button>
                                <button onclick="filterMenu('snack')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Snack</button>
                                <button onclick="filterMenu('minuman')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Minuman</button>
                                <button onclick="filterMenu('paket')" class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Paket</button>
                            </div>
                        </div>

                        <div id="menu-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[70vh] overflow-y-auto p-2">
                            @foreach ($menus as $menu)
                                <div class="menu-item border p-4 rounded-2xl text-center bg-gray-50 hover:border-red-500 hover:shadow-md transition-all group flex flex-col justify-between"
                                     data-category="{{ strtolower($menu->kategori) }}">
                                    <div>
                                        <div class="w-full h-32 overflow-hidden rounded-xl mb-3">
                                            @if ($menu->foto)
                                                <img src="{{ asset('storage/' . $menu->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                            @endif
                                        </div>

                                        <p class="font-bold text-gray-800 line-clamp-1">{{ $menu->nama_menu }}</p>
                                        <p class="text-red-600 font-black mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                    </div>

                                    <form action="{{ route('kasir.cart.add', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 w-full font-bold shadow-md shadow-red-100 transition transform active:scale-95 text-xs">
                                            + Tambah
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="md:w-1/3">
                    <div class="bg-white shadow-xl sm:rounded-2xl p-6 border-t-8 border-red-600 sticky top-6">
                        <h3 class="text-xl font-black mb-4 flex items-center gap-2 text-gray-800">Pesanan</h3>

                        <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2">
                            @php $subtotal = 0 @endphp
                            @forelse (session('cart', []) as $id => $details)
                                @php $subtotal += $details['harga'] * $details['jumlah'] @endphp
                                <div class="flex justify-between items-center border-b pb-3">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-gray-800">{{ $details['nama'] }}</p>
                                        <p class="text-xs text-gray-500">@ Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('kasir.cart.decrease', $id) }}" method="POST">
                                            @csrf<button class="w-6 h-6 bg-gray-100 rounded-md font-bold text-gray-600 hover:bg-red-500 hover:text-white">-</button>
                                        </form>
                                        <span class="font-bold text-sm">{{ $details['jumlah'] }}</span>
                                        <form action="{{ route('kasir.cart.add', $id) }}" method="POST">
                                            @csrf<button class="w-6 h-6 bg-gray-100 rounded-md font-bold text-gray-600 hover:bg-green-500 hover:text-white">+</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-4xl mb-2">?</p>
                                    <p class="text-gray-400 italic text-sm">Belum ada pesanan.</p>
                                </div>
                            @endforelse
                        </div>

                        @if (count(session('cart', [])) > 0)
                            <div class="border-t-2 border-dashed pt-4 space-y-2">
                                @php
                                    $pajak = $subtotal * 0.1;
                                    $total_akhir = $subtotal + $pajak;
                                @endphp
                                <div class="flex justify-between text-sm text-gray-500 font-medium">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500 font-medium">
                                    <span>Pajak (10%)</span>
                                    <span>Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-xl font-black mt-2 pt-2 border-t text-gray-900">
                                    <span>TOTAL</span>
                                    <span class="text-red-600">Rp {{ number_format($total_akhir, 0, ',', '.') }}</span>
                                </div>

                                <form action="{{ route('kasir.transaction.store') }}" method="POST" class="mt-6">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Pembeli</label>
                                        <input type="text" name="nama_pelanggan" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Budi" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Uang Bayar (Cash)</label>
                                        <input type="number" name="bayar" class="w-full rounded-lg border-gray-200 font-black text-2xl py-3 text-green-600 focus:border-green-500 focus:ring-green-500" placeholder="0" required min="{{ $total_akhir }}">
                                    </div>
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-black shadow-lg transition transform active:scale-95 uppercase">
                                        Proses Pembayaran
                                    </button>
                                </form>
                                <a href="{{ route('kasir.cart.clear') }}" class="block text-center text-xs text-gray-400 mt-4 hover:text-red-500 transition">Kosongkan Keranjang</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterMenu(category) {
            // Ubah warna tombol filter
            const buttons = document.querySelectorAll('.cat-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            event.currentTarget.classList.add('bg-red-600', 'text-white');
            event.currentTarget.classList.remove('bg-gray-100', 'text-gray-600');

            // Filter item menu
            const items = document.querySelectorAll('.menu-item');
            items.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('struk'))
        <script>
            let struk = @json(session('struk'));
            let detailHtml = '';
            struk.transaction_details.forEach(item => {
                detailHtml += `<div style="display:flex; justify-content:space-between; font-size:14px;">
                <span>${item.menu.nama_menu} x${item.jumlah}</span>
                <span>Rp ${parseInt(item.subtotal).toLocaleString()}</span>
            </div>`;
            });

            Swal.fire({
                title: '<span style="color:#e11d48">HotNoodle Struk</span>',
                html: `
                <div style="text-align:left; font-family:'Courier New', monospace; border: 1px solid #eee; padding: 10px; border-radius: 8px;">
                    <p style="margin:0">Waktu: ${new Date(struk.created_at).toLocaleString('id-ID')}</p>
                    <p style="margin:0">Pembeli: <b>${struk.nama_pelanggan}</b></p>
                    <hr style="border-top:1px dashed #ccc; margin:10px 0">
                    ${detailHtml}
                    <hr style="border-top:1px dashed #ccc; margin:10px 0">
                    <div style="display:flex; justify-content:space-between; font-weight:bold">
                        <span>Total Tagihan:</span><span>Rp ${parseInt(struk.total_harga).toLocaleString()}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:12px; color:#666">
                        <span>Pajak (10%):</span><span>Rp ${parseInt(struk.pajak).toLocaleString()}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:5px; border-top: 1px solid #eee; padding-top:5px">
                        <span>Bayar:</span><span>Rp ${parseInt(struk.bayar).toLocaleString()}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:900; color:#16a34a">
                        <span>Kembali:</span><span>Rp ${parseInt(struk.kembalian).toLocaleString()}</span>
                    </div>
                    <br><center><i>Terima kasih!<br>Selamat Menikmati!</i></center>
                </div>
            `,
                confirmButtonText: 'OKE',
                confirmButtonColor: '#e11d48',
            });
        </script>
    @endif
</x-app-layout>
