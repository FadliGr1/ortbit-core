<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-amber-500">404</h1>
        <p class="text-2xl md:text-3xl font-light text-gray-800 mt-4">
            Maaf, halaman yang Anda cari tidak dapat ditemukan.
        </p>
        <p class="text-gray-600 mt-2">Mungkin halaman tersebut telah dihapus atau Anda salah mengetik URL.</p>
        <div class="mt-8">
            <a href="{{ auth()->check() ? route('filament.orbit.pages.dashboard') : url('/') }}"
                class="px-6 py-3 bg-amber-500 text-white font-semibold rounded-lg shadow-md hover:bg-amber-600 transition-colors duration-300">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>

</html>
