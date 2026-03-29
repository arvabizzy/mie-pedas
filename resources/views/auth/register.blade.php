<x-guest-layout>
    <x-slot name="title">
        Daftar Akun | HotNoodle 🍜
    </x-slot>

    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-red-600 to-orange-500 rounded-3xl shadow-xl shadow-red-200 mb-6 transform -rotate-6">
            <i class="fa-solid fa-fire-burner text-white text-4xl"></i>
        </div>

        <h2 class="text-3xl font-black text-gray-900 tracking-tight">
            Gabung <span class="text-red-600">HotNoodle</span>
        </h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">Buat akun baru untuk mulai berjualan.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Contoh: Andi Kasir" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 items-center justify-end mt-6">
            <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-red-700 active:scale-95 transition-all shadow-lg shadow-red-200">
                {{ __('Daftar Sekarang') }}
            </button>

            <a class="text-xs font-bold text-gray-500 hover:text-red-600 transition underline decoration-2 underline-offset-4" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Login di sini') }}
            </a>
        </div>
    </form>
</x-guest-layout>
