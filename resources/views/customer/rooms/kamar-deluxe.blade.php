<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kamar Deluxe - Rid's Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="text-white font-sans bg-gray-100">

    <!-- Navbar (copied from main page) -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-black bg-opacity-30 text-white backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-sm uppercase tracking-widest">2025</div>
            <div class="hidden md:flex space-x-8 text-sm font-light">
                <a href="/" class="hover:underline">Home</a>
                <a href="/rooms" class="hover:underline">Rooms</a>
                <a href="/#restaurant" class="hover:underline">Gastro</a>
                <a href="/#wellness" class="hover:underline">Wellness</a>
                <a href="/#contact" class="hover:underline">Contact</a>
                <a href="/#booking-form" class="font-medium underline">Booking</a>
            </div>
            <div class="md:hidden">
                <button onclick="toggleMenu()" class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobileMenu" class="hidden md:hidden px-4 pb-4 space-y-2 text-sm font-light bg-black bg-opacity-80">
            <a href="/" class="block hover:underline">Home</a>
            <a href="/rooms" class="block hover:underline">Rooms</a>
            <a href="/#restaurant" class="block hover:underline">Gastro</a>
            <a href="/#wellness" class="block hover:underline">Wellness</a>
            <a href="/#contact" class="block hover:underline">Contact</a>
            <a href="/#booking-form" class="block font-medium underline">Booking</a>
        </div>
    </nav>

    <!-- Kamar Deluxe Hero -->
    <section class="pt-24 pb-12 bg-white text-gray-900 px-4 md:px-8">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center" data-aos="fade-up">
            <img src="{{ asset('images/379084901.jpg') }}" alt="Kamar Deluxe" class="rounded-lg shadow-lg w-full h-auto">
            <div>
                <h1 class="text-4xl font-bold mb-4">Kamar Deluxe</h1>
                <p class="mb-4">Rasakan kemewahan dan kenyamanan di kamar Deluxe kami. Dilengkapi dengan kasur premium, area kerja, televisi layar datar, kamar mandi modern, dan akses Wi-Fi cepat.</p>
                <ul class="list-disc list-inside mb-4">
                    <li>Ukuran: 32 m²</li>
                    <li>Tempat Tidur: King size</li>
                    <li>Pemandangan kota / taman</li>
                    <li>AC, Minibar, TV, Brankas</li>
                    <li>Kamar mandi pribadi dengan shower & bath tub</li>
                </ul>
                <a href="/#booking-form" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md shadow transition">Pesan Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Galeri Tambahan -->
    <section class="bg-gray-100 text-gray-900 py-12 px-4 md:px-8">
        <div class="max-w-6xl mx-auto" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6 text-center">Galeri Kamar</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <img src="{{ asset('images/379084901.jpg') }}" alt="Foto 1" class="rounded-lg shadow">
                <img src="{{ asset('images/379084901.jpg') }}" alt="Foto 2" class="rounded-lg shadow">
                <img src="{{ asset('images/379084901.jpg') }}" alt="Foto 3" class="rounded-lg shadow">
            </div>
        </div>
    </section>

    <!-- Footer (sama seperti halaman utama) -->
    <footer class="bg-gray-900 text-white text-sm mt-10">
        <div class="max-w-6xl mx-auto py-10 px-4 grid md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-bold mb-4">Rid's Hotel</h4>
                <p>Jalan Bahagia No. 123<br>Palembang, Indonesia</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">About</h4>
                <ul>
                    <li><a href="/" class="hover:underline">Home</a></li>
                    <li><a href="/rooms" class="hover:underline">Rooms</a></li>
                    <li><a href="/facilities" class="hover:underline">Facilities</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Help</h4>
                <ul>
                    <li><a href="/contact" class="hover:underline">Contact Us</a></li>
                    <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                    <li><a href="#" class="hover:underline">Terms</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-purple-400">Facebook</a>
                    <a href="#" class="hover:text-purple-400">Instagram</a>
                    <a href="#" class="hover:text-purple-400">Twitter</a>
                </div>
            </div>
        </div>
        <div class="text-center py-4 border-t border-gray-700">© 2025 Rid's Hotel. All rights reserved.</div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
        function toggleMenu() {
            const menu = document.getElementById("mobileMenu");
            menu.classList.toggle("hidden");
        }
    </script>
</body>
</html>
