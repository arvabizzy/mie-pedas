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
                            <h3
                                class="text-lg font-black text-gray-800 uppercase italic flex-shrink-0 flex items-center gap-2">
                                <span class="bg-red-600 w-2 h-6 rounded-full"></span>
                                Menu HotNoodle
                            </h3>

                            <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar">
                                <button onclick="filterMenu('all')"
                                    class="cat-btn active bg-red-600 text-white px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap shadow-md">Semua</button>
                                <button onclick="filterMenu('mie')"
                                    class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Mie</button>
                                <button onclick="filterMenu('snack')"
                                    class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Snack</button>
                                <button onclick="filterMenu('minuman')"
                                    class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Minuman</button>
                                <button onclick="filterMenu('paket')"
                                    class="cat-btn bg-gray-100 text-gray-600 hover:bg-red-100 px-5 py-2 rounded-full text-xs font-bold uppercase transition whitespace-nowrap">Paket</button>
                            </div>
                        </div>

                        <div id="menu-container"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[65vh] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-gray-200">
                            @foreach ($menus as $menu)
                                <div class="menu-item border-2 border-transparent p-4 rounded-3xl text-center bg-gray-50 hover:border-red-500 hover:bg-white hover:shadow-xl transition-all group flex flex-col justify-between"
                                    data-category="{{ strtolower($menu->kategori) }}">
                                    <div>
                                        <div class="relative w-full h-36 overflow-hidden rounded-2xl mb-3 shadow-inner">
                                            @if ($menu->foto)
                                                <img src="{{ asset('storage/' . $menu->foto) }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                            @else
                                                <div
                                                    class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">
                                                    No Image</div>
                                            @endif
                                            <span
                                                class="absolute top-2 right-2 bg-white/80 backdrop-blur-md px-2 py-1 rounded-lg text-[9px] font-black text-gray-600 border border-white">STOK:
                                                {{ $menu->stok }}</span>
                                        </div>

                                        <p
                                            class="font-black text-gray-800 line-clamp-1 group-hover:text-red-600 transition">
                                            {{ $menu->nama_menu }}</p>
                                        <p class="text-red-600 font-black text-lg mb-1">Rp
                                            {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        <p class="text-[11px] text-gray-500 mb-4 line-clamp-2 min-h-[2.75rem]">
                                            {{ $menu->deskripsi ?? 'Nikmati kelezatan menu spesial dari HotNoodle.' }}
                                        </p>
                                    </div>

                                    <form action="{{ route('kasir.cart.add', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-600 text-white py-3 rounded-2xl hover:bg-red-700 w-full font-black shadow-lg shadow-red-100 transition transform active:scale-95 text-xs uppercase tracking-widest">
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
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Struk Order
                            </h3>
                        </div>

                        <div class="space-y-4 mb-6 max-h-[30vh] overflow-y-auto pr-2 scrollbar-thin">
                            @php $subtotal = 0 @endphp
                            @forelse (session('cart', []) as $id => $details)
                                @php $subtotal += ($details['harga'] ?? 0) * ($details['jumlah'] ?? 0) @endphp
                                <div
                                    class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-gray-800">{{ $details['nama'] ?? 'Item' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Rp
                                            {{ number_format($details['harga'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex items-center bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                            <form action="{{ route('kasir.cart.decrease', $id) }}" method="POST">
                                                @csrf<button
                                                    class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:bg-red-50 hover:text-red-600 transition">-</button>
                                            </form>
                                            <span
                                                class="w-6 text-center font-black text-sm text-gray-800">{{ $details['jumlah'] ?? 0 }}</span>
                                            <form action="{{ route('kasir.cart.add', $id) }}" method="POST">
                                                @csrf<button
                                                    class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:bg-green-50 hover:text-green-600 transition">+</button>
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
                                <div
                                    class="flex justify-between text-2xl font-black mt-4 py-4 border-y border-gray-100 text-gray-900 bg-gray-50/50 -mx-6 px-6">
                                    <span>TOTAL</span>
                                    <span class="text-red-600">Rp {{ number_format($total_akhir, 0, ',', '.') }}</span>
                                </div>

                                <form
                                    action="{{ Route::has('kasir.transaction.store') ? route('kasir.transaction.store') : (Route::has('admin.transaksi.store') ? route('admin.transaksi.store') : '#') }}"
                                    method="POST" class="mt-6 space-y-4">
                                    @csrf
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama
                                            Customer</label>
                                        <input type="text" name="nama_pelanggan"
                                            class="w-full border-2 border-gray-100 rounded-xl p-3 text-sm focus:border-red-500 focus:ring-0 transition font-bold"
                                            placeholder="Input Nama..." required>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Uang
                                            Cash (Rp)</label>
                                        <input type="number" name="bayar"
                                            class="w-full rounded-2xl border-2 border-gray-100 font-black text-3xl py-4 text-green-600 focus:border-green-500 focus:ring-0 text-center shadow-inner"
                                            placeholder="0" required min="{{ $total_akhir }}">
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-5 rounded-2xl font-black shadow-xl shadow-red-200 transition transform active:scale-95 uppercase tracking-widest text-lg">
                                        CETAK STRUK & SELESAI
                                    </button>
                                </form>
                                <a href="{{ route('kasir.cart.clear') }}"
                                    class="block text-center text-xs font-bold text-gray-300 mt-4 hover:text-red-500 transition-colors uppercase tracking-widest">Batal
                                    / Hapus Semua</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS UTAMA --}}
    {{-- html2canvas untuk fitur simpan gambar --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    {{-- MODAL STRUK CUSTOM --}}
    <div id="struk-overlay"
        style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.65); backdrop-filter:blur(6px); align-items:center; justify-content:center;">
        <div style="position:relative; max-width:400px; width:95%; max-height:92vh; overflow-y:auto;">
            {{-- Kertas Struk --}}
            <div id="struk-card"
                style="background:#fff; font-family:'Courier New', Courier, monospace; color:#111; padding:32px 28px; border-radius:4px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); position:relative;">

                {{-- Gigi gergaji atas --}}
                <div
                    style="position:absolute; top:-10px; left:0; right:0; height:20px; background: repeating-linear-gradient(90deg, transparent, transparent 14px, rgba(0,0,0,0.55) 14px, rgba(0,0,0,0.55) 15px); clip-path: polygon(0 100%, 3% 0, 6% 100%, 9% 0, 12% 100%, 15% 0, 18% 100%, 21% 0, 24% 100%, 27% 0, 30% 100%, 33% 0, 36% 100%, 39% 0, 42% 100%, 45% 0, 48% 100%, 51% 0, 54% 100%, 57% 0, 60% 100%, 63% 0, 66% 100%, 69% 0, 72% 100%, 75% 0, 78% 100%, 81% 0, 84% 100%, 87% 0, 90% 100%, 93% 0, 96% 100%, 100% 0, 100% 100%);">
                </div>

                {{-- Header Struk --}}
                <div style="text-align:center; margin-bottom:12px;">
                    <div style="font-size:28px; line-height:1;">🍜</div>
                    <div
                        style="font-size:22px; font-weight:900; letter-spacing:3px; color:#dc2626; font-family:'Courier New', monospace;">
                        HOTNOODLE</div>
                    <div style="font-size:10px; letter-spacing:2px; color:#555; margin-top:2px;">MIE PEDAS NUSANTARA
                    </div>
                    <div style="font-size:9px; color:#888; margin-top:4px;">Jl. Kuliner Seru No.88, Indonesia</div>
                    <div style="font-size:9px; color:#888;">Telp: 0812-3456-7890</div>
                </div>

                <div
                    style="border-top:2px dashed #ccc; border-bottom:2px dashed #ccc; padding:6px 0; text-align:center; font-size:10px; letter-spacing:2px; color:#555; margin-bottom:12px;">
                    ✦ STRUK PEMBAYARAN ✦
                </div>

                {{-- Info Transaksi --}}
                <div id="struk-info" style="font-size:11px; margin-bottom:12px; line-height:1.7;">
                </div>

                <div style="border-top:1px dashed #bbb; margin:12px 0;"></div>

                {{-- Detail Item --}}
                <div
                    style="font-size:10px; font-weight:700; letter-spacing:1px; color:#888; display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span>ITEM</span>
                    <span>QTY</span>
                    <span>SUBTOTAL</span>
                </div>
                <div id="struk-items" style="font-size:12px;"></div>

                <div style="border-top:1px dashed #bbb; margin:12px 0;"></div>

                {{-- Ringkasan Harga --}}
                <div id="struk-summary" style="font-size:12px; line-height:2.0;"></div>

                <div style="border-top:2px dashed #ccc; margin:14px 0;"></div>

                {{-- Footer --}}
                <div style="text-align:center; font-size:10px; color:#888; line-height:1.8;">
                    <div>★ TERIMA KASIH ★</div>
                    <div>Selamat menikmati!</div>
                    <div style="margin-top:6px; font-size:9px; letter-spacing:1px; color:#bbb;">Struk ini adalah bukti
                        pembayaran yang sah</div>
                    <div style="margin-top:8px; font-size:16px; letter-spacing:4px;">▮▮▮ ▮▮ ▮▮▮▮ ▮</div>
                </div>

                {{-- Gigi gergaji bawah --}}
                <div
                    style="position:absolute; bottom:-10px; left:0; right:0; height:20px; background:#fff; clip-path: polygon(0 0, 3% 100%, 6% 0, 9% 100%, 12% 0, 15% 100%, 18% 0, 21% 100%, 24% 0, 27% 100%, 30% 0, 33% 100%, 36% 0, 39% 100%, 42% 0, 45% 100%, 48% 0, 51% 100%, 54% 0, 57% 100%, 60% 0, 63% 100%, 66% 0, 69% 100%, 72% 0, 75% 100%, 78% 0, 81% 100%, 84% 0, 87% 100%, 90% 0, 93% 100%, 96% 0, 100% 0, 100% 0%);">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div style="margin-top:20px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                <button onclick="simpanGambarStruk()"
                    style="background:#1d4ed8; color:#fff; border:none; padding:12px 22px; border-radius:12px; font-weight:900; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 15px rgba(29,78,216,0.4); transition:all .2s;">
                    Simpan Gambar
                </button>
                <button onclick="cetakStruk()"
                    style="background:#16a34a; color:#fff; border:none; padding:12px 22px; border-radius:12px; font-weight:900; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 15px rgba(22,163,74,0.4); transition:all .2s;">
                    Cetak Struk
                </button>
                <button onclick="tutupStruk()"
                    style="background:#dc2626; color:#fff; border:none; padding:12px 22px; border-radius:12px; font-weight:900; font-size:13px; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 15px rgba(220,38,38,0.3); transition:all .2s;">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

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

        // ─── STRUK FUNCTIONS ────────────────────────────────────────────
        function tutupStruk() {
            document.getElementById('struk-overlay').style.display = 'none';
            window.location.reload();
        }

        function simpanGambarStruk() {
            const card = document.getElementById('struk-card');
            // Sembunyikan gigi sementara agar tidak terpotong
            html2canvas(card, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
                y: 10,
                height: card.scrollHeight - 20
            }).then(canvas => {
                const link = document.createElement('a');
                const trxId = document.getElementById('struk-overlay').dataset.trxId || 'struk';
                link.download = `STRUK-TRX-${trxId}.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();
            });
        }

        function cetakStruk() {
            const strukcontent = document.getElementById('struk-card').outerHTML;
            const printWin = window.open('', '_blank', 'width=400,height=700');
            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Struk HotNoodle</title>
                    <style>
                        * { margin:0; padding:0; box-sizing:border-box; }
                        body {
                            background: #fff;
                            display: flex;
                            justify-content: center;
                            padding: 20px 0;
                        }
                        #struk-card {
                            font-family: 'Courier New', Courier, monospace;
                            background: #fff;
                            color: #111;
                            padding: 28px 22px;
                            width: 80mm;
                            max-width: 100%;
                            position: relative;
                        }
                        @media print {
                            body { padding:0; }
                            @page { margin: 0; size: 80mm auto; }
                        }
                    </style>
                </head>
                <body>
                    ${strukcontent}
                    <script>
                        window.onload = function() {
                            setTimeout(function() { window.print(); window.close(); }, 300);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWin.document.close();
        }

        // ─── RENDER STRUK OTOMATIS SETELAH TRANSAKSI ────────────────────
        @if (session('struk'))
            document.addEventListener('DOMContentLoaded', function () {
                const struk = @json(session('struk'));

                // Waktu client-side
                const sekarang = new Date();
                const waktuStr = sekarang.toLocaleString('id-ID', {
                    weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                // Set data TRX ID di overlay untuk nama file download
                document.getElementById('struk-overlay').dataset.trxId = struk.id;

                // Render Info Transaksi
                document.getElementById('struk-info').innerHTML = `
                    <div style="display:flex; justify-content:space-between;"><span>No. Transaksi</span><span><b>TRX-${String(struk.id).padStart(5, '0')}</b></span></div>
                    <div style="display:flex; justify-content:space-between;"><span>Waktu</span><span>${waktuStr}</span></div>
                    <div style="display:flex; justify-content:space-between;"><span>Kasir</span><span>{{ Auth::user()->name ?? 'Kasir' }}</span></div>
                    <div style="display:flex; justify-content:space-between;"><span>Pelanggan</span><span><b>${struk.nama_pelanggan}</b></span></div>
                `;

                // Render Item Detail
                let itemsHtml = '';
                let subtotal = 0;
                if (struk.transaction_details) {
                    struk.transaction_details.forEach(item => {
                        const sub = parseInt(item.subtotal);
                        subtotal += sub;
                        const namaMenu = item.menu ? item.menu.nama_menu : 'Menu';
                        itemsHtml += `
                            <div style="margin-bottom:8px;">
                                <div style="font-weight:700; word-break:break-word;">${namaMenu}</div>
                                <div style="display:flex; justify-content:space-between; color:#555; font-size:11px; padding-left:10px;">
                                    <span>x${item.jumlah}</span>
                                    <span>Rp ${sub.toLocaleString('id-ID')}</span>
                                </div>
                            </div>`;
                    });
                }
                document.getElementById('struk-items').innerHTML = itemsHtml;

                // Render Summary
                const pajak = parseInt(struk.pajak ?? 0);
                const total = parseInt(struk.total_harga);
                const bayar = parseInt(struk.bayar);
                const kembali = parseInt(struk.kembalian);

                document.getElementById('struk-summary').innerHTML = `
                    <div style="display:flex; justify-content:space-between;"><span>Subtotal</span><span>Rp ${subtotal.toLocaleString('id-ID')}</span></div>
                    <div style="display:flex; justify-content:space-between;"><span>PPN (10%)</span><span>Rp ${pajak.toLocaleString('id-ID')}</span></div>
                    <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:900; border-top:1px solid #ddd; padding-top:8px; margin-top:4px;">
                        <span>TOTAL</span><span style="color:#dc2626;">Rp ${total.toLocaleString('id-ID')}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; color:#555;"><span>Bayar</span><span>Rp ${bayar.toLocaleString('id-ID')}</span></div>
                    <div style="display:flex; justify-content:space-between; font-weight:900; font-size:14px; color:#16a34a;">
                        <span>KEMBALIAN</span><span>Rp ${kembali.toLocaleString('id-ID')}</span>
                    </div>
                `;

                // Tampilkan overlay
                const overlay = document.getElementById('struk-overlay');
                overlay.style.display = 'flex';

                // Animasi masuk
                const card = overlay.querySelector('div');
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
                requestAnimationFrame(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            });
        @endif
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #f3f4f6;
            border-radius: 10px;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        #struk-overlay {
            animation: overlay-in 0.3s ease;
        }

        @keyframes overlay-in {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #struk-overlay,
            #struk-overlay * {
                visibility: visible;
            }

            #struk-overlay {
                position: fixed;
                inset: 0;
                background: white !important;
                display: flex !important;
            }
        }
    </style>
</x-app-layout>