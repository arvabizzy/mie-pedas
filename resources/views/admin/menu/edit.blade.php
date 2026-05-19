<x-app-layout>
    <x-slot name="title">Edit Menu | HotNoodle 🍜</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             Edit Menu &mdash; <span class="text-red-600">{{ $menu->nama_menu }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Validasi --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                    <p class="font-bold text-red-700 mb-2">Oops! Ada kesalahan:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-2xl p-8">

                {{-- Preview Foto Sekarang --}}
                <div class="flex items-center gap-6 mb-8 pb-6 border-b border-gray-100">
                    <div class="relative">
                        @if ($menu->foto)
                            <img id="foto-preview"
                                 src="{{ asset('storage/' . $menu->foto) }}"
                                 alt="{{ $menu->nama_menu }}"
                                 class="w-28 h-28 object-cover rounded-2xl shadow-md border-4 border-red-100">
                        @else
                            <div id="foto-preview-placeholder"
                                 class="w-28 h-28 bg-gray-100 rounded-2xl flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px]">No Photo</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $menu->nama_menu }}</p>
                        <p class="text-red-600 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-gray-800 text-white text-[10px] uppercase font-bold rounded-full">{{ $menu->kategori }}</span>
                    </div>
                </div>

                {{-- Form Edit --}}
                <form action="{{ route('admin.menu.update', $menu->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="form-edit-menu">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama Menu --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">
                                Nama Menu <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_menu"
                                   value="{{ old('nama_menu', $menu->nama_menu) }}"
                                   required
                                   class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm font-semibold py-3 px-4">
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori" required
                                    class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm py-3 px-4">
                                @foreach (['mie' => 'Mie', 'snack' => 'Snack', 'minuman' => 'Minuman', 'paket' => 'Paket'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('kategori', $menu->kategori) == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="harga"
                                   value="{{ old('harga', $menu->harga) }}"
                                   min="0"
                                   required
                                   class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm py-3 px-4">
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="stok"
                                   value="{{ old('stok', $menu->stok) }}"
                                   min="0"
                                   required
                                   class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm py-3 px-4">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Deskripsi</label>
                            <input type="text"
                                   name="deskripsi"
                                   value="{{ old('deskripsi', $menu->deskripsi) }}"
                                   placeholder="Contoh: Level pedas 1–5..."
                                   class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500 text-sm py-3 px-4">
                        </div>

                        {{-- Ganti Foto --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Ganti Foto</label>

                            {{-- Dropzone / preview area --}}
                            <label for="foto-input"
                                   class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-red-300 rounded-2xl cursor-pointer bg-red-50 hover:bg-red-100 transition group">
                                <img id="new-foto-preview"
                                     src="#"
                                     alt="Preview"
                                     class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl opacity-90">
                                <div id="dropzone-text" class="flex flex-col items-center z-10 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-400 mb-2 group-hover:scale-110 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <p class="text-sm font-bold text-red-600">Klik atau drag foto baru</p>
                                    <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, JPEG, WEBP · Maks 2MB</p>
                                </div>
                                <input id="foto-input" type="file" name="foto" accept="image/*" class="hidden"
                                       onchange="previewFoto(this)">
                            </label>

                            {{-- Tombol hapus foto (hanya muncul jika ada foto) --}}
                            @if ($menu->foto)
                                <div class="mt-3 flex items-center gap-2">
                                    <input type="checkbox" name="hapus_foto" id="hapus_foto" value="1"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="hapus_foto" class="text-xs text-red-600 font-semibold cursor-pointer">
                                        Hapus foto tanpa menggantinya
                                    </label>
                                </div>
                            @endif
                        </div>

                    </div>{{-- end grid --}}

                    {{-- Tombol Aksi --}}
                    <div class="mt-8 flex items-center gap-4 pt-6 border-t border-gray-100">
                        <button type="submit"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl uppercase tracking-wider transition shadow-lg text-sm">
                             Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl uppercase tracking-wider transition text-sm">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewFoto(input) {
            const preview = document.getElementById('new-foto-preview');
            const dropText = document.getElementById('dropzone-text');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    dropText.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
