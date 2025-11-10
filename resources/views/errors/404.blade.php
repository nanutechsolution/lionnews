<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | LionNews</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=merriweather-sans:400,700" rel="stylesheet" />

    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-brand-base dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="min-h-screen flex flex-col items-center justify-center text-center px-4">

        <h2 class="text-5xl md:text-7xl font-bold text-brand-accent font-heading">
            404
        </h2>

        <h1 class="mt-4 text-3xl md:text-5xl font-bold text-brand-primary dark:text-white font-heading">
            Halaman Tidak Ditemukan
        </h1>

        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
            Maaf, halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau tidak pernah ada.
        </p>

        <div class="mt-8">
            <a href="{{ route('home') }}"
               class="inline-flex items-center px-6 py-3 bg-brand-primary border border-transparent
                      rounded-md font-semibold text-sm text-white uppercase tracking-widest
                      hover:bg-brand-primary/80 focus:outline-none
                      focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                      dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Kembali ke Beranda
            </a>
        </div>

    </div>

</body>
</html>
