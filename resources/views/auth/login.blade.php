<x-guest-layout>
    <x-slot name="title">
        Login | HotNoodle 🍜
    </x-slot>

    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-red-600 to-orange-500 rounded-3xl shadow-xl shadow-red-200 mb-6 transform -rotate-6">
            <i class="fa-solid fa-fire-burner text-white text-4xl"></i>
        </div>

        <h2 class="text-3xl font-black text-gray-900 tracking-tight">
            Selamat Datang di <span class="text-red-600">HotNoodle</span>
        </h2>
        <p class="text-sm text-gray-500 mt-2 font-medium">Masuk ke sistem untuk mulai mengelola pesanan.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email / Username" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" class="font-bold text-gray-700 text-xs uppercase tracking-wider" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-200 rounded-xl focus:border-red-500 focus:ring-red-500 transition-all p-3"
                type="password"
                name="password"
                required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-md border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                <span class="ms-2 text-xs text-gray-600 font-semibold italic">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-bold text-red-600 hover:text-red-800 transition underline decoration-2 underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-red-700 active:scale-95 transition-all shadow-lg shadow-red-200">
                {{ __('Masuk Sekarang') }}
            </button>
        </div>
    </form>

    <div class="mt-10 p-5 bg-orange-50 rounded-2xl border border-orange-100 shadow-sm">
        <div class="flex items-center gap-2 mb-3 text-orange-700 border-b border-orange-200 pb-2">
            <i class="fa-solid fa-circle-info text-xs"></i>
            <span class="text-[10px] font-black uppercase tracking-widest italic">Akses Akun Demo</span>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-gray-400 uppercase font-bold">Admin</span>
                <span class="text-gray-700 font-mono bg-white px-2 py-0.5 rounded border border-orange-200 shadow-sm">admin@gmail.com</span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-gray-400 uppercase font-bold">Kasir</span>
                <span class="text-gray-700 font-mono bg-white px-2 py-0.5 rounded border border-orange-200 shadow-sm">kasir@gmail.com</span>
            </div>
        </div>
        <p class="text-[9px] text-orange-400 mt-3 font-bold italic text-center uppercase tracking-widest">Password: password123</p>
    </div>
</x-guest-layout>
