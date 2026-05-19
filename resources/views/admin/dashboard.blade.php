<x-app-layout>
    <x-slot name="title">
        Dashboard Admin | HotNoodle 🍜
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin - HotNoodle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ===== FILTER SECTION ===== --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 mb-6 border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3"> Filter Periode Pendapatan</p>

                {{-- Quick Range Buttons --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach (['all' => 'Semua', 'today' => 'Hari Ini', 'yesterday' => 'Kemarin', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                        <a href="{{ route('admin.dashboard', ['range' => $key]) }}"
                            class="px-4 py-2 rounded-xl font-bold text-xs transition shadow-sm
                            {{ ($range == $key && !$date_from && !$date_to) ? 'bg-red-600 text-white shadow-red-200 shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                {{-- Custom Date Range --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from"
                            value="{{ $date_from ?? '' }}"
                            max="{{ date('Y-m-d') }}"
                            class="border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-bold text-gray-700 focus:border-red-500 focus:ring-0 transition cursor-pointer">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to"
                            value="{{ $date_to ?? '' }}"
                            max="{{ date('Y-m-d') }}"
                            class="border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-bold text-gray-700 focus:border-red-500 focus:ring-0 transition cursor-pointer">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl font-bold text-sm transition shadow-md shadow-red-100 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            Filter
                        </button>
                        @if($date_from || $date_to)
                            <a href="{{ route('admin.dashboard') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-500 px-4 py-2 rounded-xl font-bold text-sm transition flex items-center gap-2">
                                ✕ Reset
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Active Filter Label --}}
                @if($date_from || $date_to)
                    <div class="mt-3 inline-flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-xl text-xs font-bold">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        Menampilkan:
                        {{ $date_from ? \Carbon\Carbon::parse($date_from)->translatedFormat('d F Y') : '...' }}
                        —
                        {{ $date_to ? \Carbon\Carbon::parse($date_to)->translatedFormat('d F Y') : 'Sekarang' }}
                    </div>
                @endif
            </div>

            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-red-600 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-full -translate-y-8 translate-x-8 opacity-60"></div>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Total Pendapatan</p>
                    <p class="text-[10px] text-red-500 font-bold mb-2">
                        @if($range == 'custom' && ($date_from || $date_to))
                             {{ $date_from ? \Carbon\Carbon::parse($date_from)->format('d/m/Y') : '...' }} — {{ $date_to ? \Carbon\Carbon::parse($date_to)->format('d/m/Y') : 'Sekarang' }}
                        @elseif($range == 'today')
                             Hari Ini
                        @elseif($range == 'yesterday')
                             Kemarin
                        @elseif($range == 'week')
                             Minggu Ini
                        @elseif($range == 'month')
                             Bulan Ini
                        @else
                             Semua Waktu
                        @endif
                    </p>
                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($total_transaksi, 0) }} transaksi</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-orange-500 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-full -translate-y-8 translate-x-8 opacity-60"></div>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Menu Terlaris</p>
                    <p class="text-2xl font-black text-gray-900 mt-2">{{ $nama_menu_terlaris }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-gray-800 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-full -translate-y-8 translate-x-8 opacity-60"></div>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Stok Menipis</p>
                    <p class="text-3xl font-black text-red-600 mt-2">{{ $stok_menipis }} <span class="text-sm text-gray-400 font-normal">Menu</span></p>
                </div>
            </div>

            {{-- Manajemen Toko --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-black mb-6 text-gray-800 flex items-center gap-2">Manajemen Toko</h3>

                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-8">
                    <h4 class="font-bold mb-4 text-gray-700 text-sm uppercase italic">Tambah Menu Baru</h4>

                    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Menu</label>
                                <input type="text" name="nama_menu" placeholder="Mie Iblis" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Kategori</label>
                                <select name="kategori" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                                    <option value="mie">Mie</option>
                                    <option value="snack">Snack</option>
                                    <option value="minuman">Minuman</option>
                                    <option value="paket">Paket</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Harga</label>
                                <input type="number" name="harga" placeholder="15000" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>

                            {{-- INPUT DESKRIPSI --}}
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Deskripsi</label>
                                <input type="text" name="deskripsi" placeholder="Level pedas..."
                                    class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Stok Awal</label>
                                <input type="number" name="stok" placeholder="50" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Foto</label>
                                <input type="file" name="foto" class="w-full text-[10px] text-gray-500 pt-2">
                            </div>
                        </div>
                        <button type="submit"
                            class="mt-6 bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg text-sm uppercase tracking-wider">
                            Simpan Menu Baru
                        </button>
                    </form>
                </div>

                {{-- Tabel Menu --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white text-xs uppercase tracking-widest">
                                <th class="p-4 rounded-tl-xl">Menu / Kategori</th>
                                <th class="p-4 text-center">Stok</th>
                                <th class="p-4 rounded-tr-xl text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($menus as $menu)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                @if ($menu->foto)
                                                    <img src="{{ asset('storage/' . $menu->foto) }}"
                                                        class="w-12 h-12 object-cover rounded-xl shadow-sm">
                                                @else
                                                    <div
                                                        class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center text-[10px] text-gray-400">
                                                        N/A</div>
                                                @endif
                                                <span
                                                    class="absolute -top-2 -left-2 bg-white px-1.5 py-0.5 rounded shadow text-[8px] border uppercase font-bold">{{ $menu->kategori }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 leading-none">{{ $menu->nama_menu }}</p>
                                                {{-- Menampilkan Deskripsi di Tabel --}}
                                                @if($menu->deskripsi)
                                                    <p class="text-[10px] text-gray-400 italic line-clamp-1">
                                                        {{ $menu->deskripsi }}</p>
                                                @endif
                                                <p class="text-xs text-red-600 font-bold">Rp
                                                    {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black {{ $menu->stok < 10 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                            {{ $menu->stok }} Porsi
                                        </span>
                                    </td>

                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.menu.edit', $menu->id) }}"
                                                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase transition">
                                                 Edit
                                            </a>
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus menu ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-100 hover:bg-red-600 hover:text-white text-red-500 px-3 py-1 rounded-lg text-[10px] font-black uppercase transition">
                                                     Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Transaksi --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 mt-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-gray-800 flex items-center gap-2 italic uppercase">
                        Riwayat Transaksi
                    </h3>
                    @if($date_from || $date_to || $range != 'all')
                        <span class="bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border border-red-200">
                             Difilter
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white text-xs uppercase tracking-widest">
                                <th class="p-4 rounded-tl-xl">#</th>
                                <th class="p-4">Waktu</th>
                                <th class="p-4">Nama Pembeli</th>
                                <th class="p-4">Kasir</th>
                                <th class="p-4 text-right">Total Bayar</th>
                                <th class="p-4 rounded-tr-xl text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $index => $trx)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="p-4 text-xs font-black text-gray-400">
                                        TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">
                                        <div class="font-bold text-gray-700">{{ $trx->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $trx->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-red-400 to-orange-400 flex items-center justify-center text-white font-black text-[10px] flex-shrink-0">
                                                {{ strtoupper(substr($trx->nama_pelanggan ?? 'G', 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-800 text-sm">
                                                {{ $trx->nama_pelanggan ?? '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-500">
                                        {{ $trx->user->name ?? 'Kasir' }}
                                    </td>
                                    <td class="p-4 text-right font-black text-gray-800">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                            ✓ Berhasil
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center">
                                        <div class="text-4xl mb-3 grayscale opacity-30">🧾</div>
                                        <p class="text-gray-400 italic text-sm">Belum ada transaksi pada periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->count() > 0)
                    <div class="mt-4 text-right text-xs text-gray-400 font-bold">
                        Menampilkan {{ $transactions->count() }} transaksi terbaru
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        // Pastikan date_to tidak bisa lebih kecil dari date_from
        document.getElementById('date_from').addEventListener('change', function () {
            const dateFrom = this.value;
            const dateTo = document.getElementById('date_to');
            if (dateFrom) {
                dateTo.min = dateFrom;
                if (dateTo.value && dateTo.value < dateFrom) {
                    dateTo.value = dateFrom;
                }
            }
        });

        // Pastikan date_from tidak bisa lebih besar dari date_to
        document.getElementById('date_to').addEventListener('change', function () {
            const dateTo = this.value;
            const dateFrom = document.getElementById('date_from');
            if (dateTo) {
                dateFrom.max = dateTo;
            }
        });
    </script>
</x-app-layout>