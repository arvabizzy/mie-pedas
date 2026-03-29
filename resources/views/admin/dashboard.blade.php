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

            <div class="flex gap-2 mb-6">
                @foreach (['all' => 'Semua', 'today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ route('admin.dashboard', ['range' => $key]) }}"
                        class="px-4 py-2 rounded-xl font-bold text-sm transition shadow-sm {{ $range == $key ? 'bg-red-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-red-600">
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Penjualan ({{ ucfirst($range) }})</p>
                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-orange-500">
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Menu Terlaris</p>
                    <p class="text-2xl font-black text-gray-900">{{ $nama_menu_terlaris }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-gray-800">
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Stok Menipis</p>
                    <p class="text-3xl font-black text-red-600">{{ $stok_menipis }} <span class="text-sm text-gray-400 font-normal">Menu</span></p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-black mb-6 text-gray-800 flex items-center gap-2">Manajemen Toko</h3>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-8">
                    <h4 class="font-bold mb-4 text-gray-700 text-sm uppercase italic">Tambah Menu Baru</h4>
                    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Nama Menu</label>
                                <input type="text" name="nama_menu" placeholder="Mie Iblis" required class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Kategori</label>
                                <select name="kategori" required class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                                    <option value="mie">Mie</option>
                                    <option value="snack">Snack</option>
                                    <option value="minuman">Minuman</option>
                                    <option value="paket">Paket</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Harga</label>
                                <input type="number" name="harga" placeholder="15000" required class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Stok Awal</label>
                                <input type="number" name="stok" placeholder="50" required class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Foto</label>
                                <input type="file" name="foto" class="w-full text-[10px] text-gray-500 pt-2">
                            </div>
                        </div>
                        <button type="submit" class="mt-6 bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg text-sm uppercase tracking-wider">Simpan Menu Baru</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white text-xs uppercase tracking-widest">
                                <th class="p-4 rounded-tl-xl">Menu / Kategori</th>
                                <th class="p-4 text-center">Stok</th>
                                <th class="p-4 text-center">Update Stok</th>
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
                                                    <img src="{{ asset('storage/' . $menu->foto) }}" class="w-12 h-12 object-cover rounded-xl shadow-sm">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center text-[10px] text-gray-400">N/A</div>
                                                @endif
                                                <span class="absolute -top-2 -left-2 bg-white px-1.5 py-0.5 rounded shadow text-[8px] border uppercase font-bold">{{ $menu->kategori }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 leading-none">{{ $menu->nama_menu }}</p>
                                                <p class="text-xs text-red-600 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $menu->stok < 10 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                            {{ $menu->stok }} Porsi
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <form action="{{ route('admin.menu.updateStok', $menu->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                            @csrf @method('PATCH')
                                            <input type="number" name="stok" value="{{ $menu->stok }}" class="w-16 rounded-lg border-gray-300 text-[10px] py-1 text-center font-bold">
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase hover:bg-blue-700 transition">Update</button>
                                        </form>
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-300 hover:text-red-600 transition text-xl">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 mt-8">
                <h3 class="text-xl font-black mb-6 text-gray-800 flex items-center gap-2 italic uppercase">Riwayat Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b">
                                <th class="p-4">Waktu</th>
                                <th class="p-4">Total Bayar</th>
                                <th class="p-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 text-sm text-gray-600">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                    <td class="p-4 font-bold text-gray-800">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">Berhasil Pay</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-4 text-center text-gray-400 italic">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
