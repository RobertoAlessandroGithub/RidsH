<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pilihan Kamar - Rid's Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-black bg-opacity-30 text-white backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-sm uppercase tracking-widest">2025</div>
            <div class="hidden md:flex space-x-8 text-sm font-light">
                <a href="/" class="hover:underline">Home</a>
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
            <a href="/#restaurant" class="block hover:underline">Gastro</a>
            <a href="/#wellness" class="block hover:underline">Wellness</a>
            <a href="/#contact" class="block hover:underline">Contact</a>
            <a href="/#booking-form" class="block font-medium underline">Booking</a>
        </div>
    </nav>

    <!-- Room List Section -->
    <section class="pt-32 pb-20 px-4 max-w-6xl mx-auto" data-aos="fade-up">
        <h2 class="text-4xl font-bold text-blue-600 text-center mb-12">Pilihan Kamar</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            <!-- Kamar 1 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('images/379079771.jpg') }}" alt="Kamar Deluxe"
                     class="w-full h-60 object-cover rounded-t-xl">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold mb-2">Kamar Deluxe</h3>
                    <p class="text-gray-600 mb-4">Fasilitas lengkap, cocok untuk liburan keluarga.</p>
                     <a href="{{ url('/kamar-deluxe') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md transition relative group"
                        data-aos="fade-up"
                        data-aos-delay="200">
                            Lihat Detail
                            <span class="absolute left-1/2 -translate-x-1/2 -top-10 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                Klik untuk lihat detail Kamar Deluxe
                            </span>
                        </a>
                </div>
            </div>

            <!-- Kamar 2 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/379088455.jpg') }}" alt="Kamar Executive"
                     class="w-full h-60 object-cover rounded-t-xl">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold mb-2">Kamar Executive</h3>
                    <p class="text-gray-600 mb-4">Nikmati kenyamanan ekstra dan desain elegan.</p>
                        <a href="{{ url('/kamar-executive') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md transition relative group"
                            data-aos="fade-up"
                            data-aos-delay="200">
                                Lihat Detail
                                <span class="absolute left-1/2 -translate-x-1/2 -top-10 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    Klik untuk lihat detail Kamar Executive
                                </span>
                            </a>
                </div>
            </div>

            <!-- Kamar 3 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <img src="{{ asset('images/379084901.jpg') }}" alt="Kamar Suite"
                     class="w-full h-60 object-cover rounded-t-xl">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold mb-2">Kamar Suite</h3>
                    <p class="text-gray-600 mb-4">Untuk pengalaman menginap mewah dan eksklusif.</p>
                        <a href="{{ url('/kamar-suite') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md transition relative group"
                            data-aos="fade-up"
                            data-aos-delay="200">
                                Lihat Detail
                                <span class="absolute left-1/2 -translate-x-1/2 -top-10 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    Klik untuk lihat detail Kamar Suite
                                </span>
                            </a>
                </div>
            </div>
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
