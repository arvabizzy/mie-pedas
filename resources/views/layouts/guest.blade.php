<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'HotNoodle') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="mb-4">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-black text-gray-800">Hot<span class="text-red-600">Noodle</span></span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden sm:rounded-[2.5rem] border border-gray-100">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">© {{ date('Y') }} HotNoodle POS System</p>
        </div>
    </div>
</body>

</html>
