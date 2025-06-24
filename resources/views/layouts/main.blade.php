<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rid's Hotel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
    </style>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
</script>

</head>
<body class="text-white font-sans">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-black bg-opacity-30 text-white backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-sm uppercase tracking-widest">2025</div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 text-sm font-light">
                <a href="#rooms" class="hover:underline">Rooms</a>
                <a href="#maminko" class="hover:underline">Restaurant</a>
                <a href="#wellness" class="hover:underline">Ballroom</a>
                <a href="#contact" class="hover:underline">Contact</a>
                <a href="#booking-form" class="font-medium underline">Booking</a>
            </div>

            <!-- Mobile Menu Button -->
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

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden px-4 pb-4 space-y-2 text-sm font-light bg-black bg-opacity-80">
            <a href="#rooms" class="block hover:underline">Rooms</a>
            <a href="#maminko" class="block hover:underline">Restaurant</a>
            <a href="#ballroom" class="block hover:underline">Ballroom</a>
            <a href="#contact" class="block hover:underline">Contact</a>
            <a href="#booking-form" class="block font-medium underline">Booking</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="h-screen bg-fixed bg-cover bg-center flex items-center justify-center relative"
            style="background-image: url('{{ asset('images/2_280971_02.webp') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="z-10 text-center px-4" data-aos="fade-down" data-aos-delay="200">
            <p class="text-lg tracking-wider mb-2">Comfort Hotel</p>
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Rid's Hotel</h1>
            <p class="text-base md:text-lg">Your comfort, our priority.</p>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="bg-white text-gray-900 py-16 px-4 md:px-8">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center" data-aos="fade-right">
            <img src="images/379079771.jpg" alt="Rooms"
                class="rounded-lg shadow-lg w-full h-[300px] object-cover object-center">
            <div>
                <h2 class="text-3xl font-bold mb-4">Elegant Rooms</h2>
                <p class="mb-4">Nikmati kenyamanan luar biasa di kamar kami yang luas, lengkap dengan fasilitas modern, dan pemandangan menawan dari jendela Anda.</p>
                <a href="/rooms" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md shadow transition">Lihat Detail</a>
            </div>
        </div>
    </section>

    <!-- Restaurant Section -->
    <section id="maminko" class="bg-gray-100 text-gray-900 py-16 px-4 md:px-8">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center md:flex-row-reverse" data-aos="fade-left">
            <img src="images/379088455.jpg" alt="Restaurant"
                class="rounded-lg shadow-lg w-full h-[300px] object-cover object-center">
            <div>
                <h2 class="text-3xl font-bold mb-4">MaMinKo Restaurant</h2>
                <p class="mb-4">Rasakan kelezatan hidangan lokal dan internasional dari chef kami, tersedia 24 jam untuk memenuhi selera Anda kapan saja.</p>
                <a href="/maminko" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Lihat Restoran</a>
            </div>
        </div>
    </section>

    <!-- Ballroom Section -->
    <section id="wellness" class="bg-white text-gray-900 py-16 px-4 md:px-8">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center" data-aos="fade-right">
            <img src="images/379084901.jpg" alt="Ballroom"
                class="rounded-lg shadow-lg w-full h-[300px] object-cover object-center">
            <div>
                <h2 class="text-3xl font-bold mb-4">Ballroom</h2>
                <p class="mb-4">Manjakan diri Anda dengan layanan spa, sauna, dan perawatan kesehatan lainnya di pusat wellness kami yang tenang dan profesional.</p>
                <a href="#" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Lihat Ballroom</a>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section id="booking-form" class="bg-gray-100 text-gray-900 py-16 px-4 md:px-8">
        <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8" data-aos="zoom-in">
            <h2 class="text-3xl font-bold mb-4 text-center">Form Booking</h2>
            <form action="/booking" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nama Anda" class="w-full border rounded px-4 py-2" required>
                <input type="email" name="email" placeholder="Email" class="w-full border rounded px-4 py-2" required>
                <input type="date" name="checkin" class="w-full border rounded px-4 py-2" required>
                <input type="date" name="checkout" class="w-full border rounded px-4 py-2" required>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Kirim Booking</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
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
                    <li><a href="/maminko" class="hover:underline">Restaurant</a></li>
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
