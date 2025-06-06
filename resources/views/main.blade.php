<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rid's Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tambahkan Tailwind CSS CDN (opsional) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">Rid's Hotel</h1>
            <div class="space-x-4">
                <a href="/" class="text-gray-700 hover:text-blue-500">Home</a>
                <a href="/menu" class="text-gray-700 hover:text-blue-500">Menu</a>
                <a href="/order" class="text-gray-700 hover:text-blue-500">Order</a>
                <a href="/login" class="text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-600 text-white text-center py-20">
        <h2 class="text-4xl font-bold mb-4">Selamat Datang di Rid's Hotel</h2>
        <p class="text-lg">Penginapan nyaman dan modern untuk liburan atau perjalanan bisnis Anda</p>
    </section>

    <!-- Fitur Hotel -->
    <section class="max-w-6xl mx-auto px-4 py-12">
        <h3 class="text-2xl font-semibold mb-6">Kenapa memilih kami?</h3>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h4 class="font-bold text-lg mb-2">Kamar Nyaman</h4>
                <p>Kamar luas dengan AC, Wi-Fi, dan view indah.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h4 class="font-bold text-lg mb-2">Restoran 24 Jam</h4>
                <p>Nikmati makanan khas lokal & internasional kapan saja.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h4 class="font-bold text-lg mb-2">Dekat Lokasi Wisata</h4>
                <p>Berada di pusat kota, hanya 5 menit dari stasiun dan mall.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white text-center text-sm py-4 border-t mt-12">
        &copy; 2025 Rid's Hotel. All rights reserved.
    </footer>

</body>
</html>
