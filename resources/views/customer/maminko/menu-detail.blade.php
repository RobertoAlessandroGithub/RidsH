<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu - Hotel Maminko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .carousel-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 1rem; /* rounded-xl */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-item {
            min-width: 100%;
            flex-shrink: 0;
        }
        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 9999px; /* rounded-full */
            cursor: pointer;
            z-index: 10;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .carousel-button:hover {
            opacity: 1;
        }
        .carousel-button.left { left: 1rem; }
        .carousel-button.right { right: 1rem; }
        .carousel-dots {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }
        .carousel-dot {
            width: 0.75rem;
            height: 0.75rem;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .carousel-dot.active {
            background-color: white;
        }
        .card-recommendation {
            border-radius: 0.75rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
            cursor: pointer;
        }
        .card-recommendation:hover {
            transform: translateY(-5px);
        }
        .fly-animation {
            animation: flyToCart 0.8s forwards;
            position: fixed;
            z-index: 9999;
            pointer-events: none; /* Make it not interfere with clicks */
        }

        @keyframes flyToCart {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 1;
            }
            70% {
                transform: translate(calc(var(--target-x) - var(--start-x)), calc(var(--target-y) - var(--start-y))) scale(0.3);
                opacity: 0.7;
            }
            100% {
                transform: translate(calc(var(--target-x) - var(--start-x)), calc(var(--target-y) - var(--start-y))) scale(0);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Data Dummy untuk Dish dan Rekomendasi Menu --}}
    {{-- Dalam aplikasi Laravel nyata, data ini akan dikirim dari MenuController@showDetail --}}
    @php
        $dish = (object)[
            'id' => 1,
            'name' => 'Pizza Margherita Classic',
            'description' => 'Pizza klasik Italia dengan topping sederhana namun lezat yang terdiri dari saus tomat San Marzano, mozzarella segar, daun basil segar, garam, dan minyak zaitun extra virgin. Dipanggang dalam oven batu untuk kesempurnaan renyah di luar dan lembut di dalam. Sebuah pilihan abadi yang menunjukkan keindahan kesederhanaan.',
            'detailed_description' => 'Pizza Margherita klasik kami adalah mahakarya kuliner yang merayakan tradisi Italia. Dibuat dengan adonan yang difermentasi perlahan, memberikan tekstur renyah di bagian luar dan kenyal di bagian dalam. Saus tomat San Marzano kami yang manis dan sedikit asam dioleskan dengan murah hati, menciptakan dasar rasa yang kaya. Kemudian dilapisi dengan irisan mozzarella Fior di Latte yang lembut, meleleh sempurna saat dipanggang, dan ditaburi dengan daun basil segar yang aromatik. Sentuhan akhir minyak zaitun extra virgin berkualitas tinggi menambah kilau dan kedalaman rasa. Setiap gigitan adalah perjalanan ke jantung Napoli, tempat pizza legendaris ini lahir.',
            'price' => 95000,
            'images' => [
                'https://placehold.co/800x600/FF5733/FFFFFF?text=Pizza+1', // Gambar utama
                'https://placehold.co/800x600/4CAF50/FFFFFF?text=Pizza+2',
                'https://placehold.co/800x600/2196F3/FFFFFF?text=Pizza+3',
                'https://placehold.co/800x600/FFC107/FFFFFF?text=Pizza+4',
            ],
            'average_rating' => 4.8,
            'total_ratings' => 125,
            'reviews' => [
                (object)['user_name' => 'Caolan', 'rating' => 5, 'comment' => 'Makanan lezat, pengiriman cepat, pengemudi ramah, saya sangat sangat sangat sangat sangat puas.', 'created_at' => now()->subDays(2)],
                (object)['user_name' => 'Budi Santoso', 'rating' => 5, 'comment' => 'Pizza terbaik yang pernah saya makan! Mozzarellanya meleleh sempurna.', 'created_at' => now()->subDays(5)],
                (object)['user_name' => 'Dewi Rahayu', 'rating' => 4, 'comment' => 'Enak sekali, tapi sedikit kurang asin untuk selera saya. Namun tetap rekomendasi!', 'created_at' => now()->subWeeks(1)],
            ],
            'nutritional_info' => 'Per porsi: Kalori 850, Lemak 35g, Karbohidrat 90g, Protein 40g.'
        ];

        $recommendedMenus = [
            (object)['id' => 101, 'name' => 'Spaghetti Bolognese', 'desc' => 'Pasta klasik dengan saus daging sapi cincang.', 'price' => 75000, 'image' => 'https://placehold.co/300x200/9C27B0/FFFFFF?text=Spaghetti'],
            (object)['id' => 102, 'name' => 'Caesar Salad', 'desc' => 'Salad segar dengan ayam panggang dan dressing Caesar.', 'price' => 50000, 'image' => 'https://placehold.co/300x200/00BCD4/FFFFFF?text=Caesar+Salad'],
            (object)['id' => 103, 'name' => 'Tiramisu', 'desc' => 'Dessert Italia klasik dengan kopi dan mascarpone.', 'price' => 45000, 'image' => 'https://placehold.co/300x200/FF9800/FFFFFF?text=Tiramisu'],
            (object)['id' => 104, 'name' => 'Mocktail Sunset', 'desc' => 'Minuman segar campuran jeruk, grenadine, dan soda.', 'price' => 35000, 'image' => 'https://placehold.co/300x200/795548/FFFFFF?text=Mocktail'],
        ];

        // Fungsi format Rupiah untuk PHP di Blade (jika diperlukan untuk data awal)
        if (!function_exists('formatRupiah')) {
            function formatRupiah($number) {
                return number_format($number, 0, ',', '.');
            }
        }
    @endphp

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Tombol Kembali -->
        <div class="mb-4">
            <a href="/maminko" class="text-gray-600 hover:text-orange-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Menu
            </a>
        </div>

        <!-- Bagian Detail Menu Utama -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up">
            <!-- Image Carousel -->
            <div class="carousel-container relative">
                <div class="carousel-inner" id="imageCarousel">
                    @foreach($dish->images as $image)
                        <div class="carousel-item">
                            <img src="{{ $image }}" alt="{{ $dish->name }} - Gambar {{ $loop->index + 1 }}" class="w-full h-80 object-cover">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-button left" id="carouselPrev">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="carousel-button right" id="carouselNext">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div class="carousel-dots" id="carouselDots"></div>
            </div>

            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $dish->name }}</h1>
                <div class="flex items-center mb-4">
                    {{-- Star ratings --}}
                    <div class="flex text-yellow-500 mr-2">
                        @for ($i = 0; $i < floor($dish->average_rating); $i++)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.785.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path></svg>
                        @endfor
                        @if ($dish->average_rating - floor($dish->average_rating) >= 0.5)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.785.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" opacity=".5"></path></svg>
                        @endif
                    </div>
                    <span class="text-gray-600">({{ $dish->total_ratings }} ulasan)</span>
                </div>

                <p class="text-2xl font-bold text-orange-600 mb-4">Rp {{ formatRupiah($dish->price) }}</p>

                <!-- Quantity Selector -->
                <div class="flex items-center justify-end mb-6">
                    <button id="decrement-quantity" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-l-lg hover:bg-gray-300 transition-colors duration-200">-</button>
                    <input type="text" id="quantity" value="1" class="w-16 text-center border-t border-b border-gray-300 py-2 text-lg" readonly>
                    <button id="increment-quantity" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-r-lg hover:bg-gray-300 transition-colors duration-200">+</button>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-2">Deskripsi Lengkap</h3>
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $dish->detailed_description }}
                </p>

                @if ($dish->nutritional_info)
                    <p class="text-sm text-gray-500 mb-4">Informasi Nutrisi: {{ $dish->nutritional_info }}</p>
                @endif
            </div>
        </div>

        <!-- Bagian Review -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Ulasan Pelanggan</h2>

            @forelse ($dish->reviews as $review)
                <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                    <div class="flex items-center mb-2">
                        <p class="font-semibold text-gray-800 mr-2">{{ $review->user_name }}</p>
                        <div class="flex text-yellow-500 mr-2">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.785.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $review->created_at->diffForHumans() }})</span>
                    </div>
                    <p class="text-gray-700">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-600 text-center">Belum ada ulasan untuk menu ini. Jadilah yang pertama!</p>
            @endforelse

            {{-- Formulir Tambah Ulasan (opsional) --}}
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Tulis Ulasan Anda</h3>
                <form action="#" method="POST"> {{-- Sesuaikan action route di sini --}}
                    @csrf
                    <div class="mb-3">
                        <label for="review_rating" class="block text-sm font-medium text-gray-700">Rating:</label>
                        <select name="rating" id="review_rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                            <option value="5">5 Bintang - Sangat Baik</option>
                            <option value="4">4 Bintang - Baik</option>
                            <option value="3">3 Bintang - Cukup</option>
                            <option value="2">2 Bintang - Buruk</option>
                            <option value="1">1 Bintang - Sangat Buruk</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="review_comment" class="block text-sm font-medium text-gray-700">Komentar:</label>
                        <textarea name="comment" id="review_comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Bagikan pengalaman Anda..."></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">Kirim Ulasan</button>
                </form>
            </div>
        </div>

        <!-- Bagian Recommended Menu Lainnya -->
        <!-- <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Rekomendasi Menu Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($recommendedMenus as $recMenu)
                        <img src="{{ $recMenu->image }}" alt="{{ $recMenu->name }}" class="w-full h-32 object-cover rounded-t-lg">
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $recMenu->name }}</h3>
                            <p class="text-sm text-gray-600 truncate">{{ $recMenu->desc }}</p>
                            <p class="text-lg font-bold text-orange-600 mt-2">Rp {{ formatRupiah($recMenu->price) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full text-center">Tidak ada rekomendasi menu saat ini.</p>
                @endforelse
            </div>
        </div>
    </div> -->

    <!-- Fixed Bottom Bar for Add to Cart -->
    <div class="fixed bottom-0 left-0 right-0 bg-white p-4 shadow-xl flex justify-between items-center z-50 rounded-t-xl">
        <div>
            <span class="text-gray-600 text-sm">Total Harga:</span>
            <span id="total-price" class="text-2xl font-bold text-gray-900">Rp {{ formatRupiah($dish->price) }}</span>
        </div>
        <button id="add-to-cart-button" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-full shadow-lg flex items-center transition-colors duration-200 transform active:scale-95">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
            Tambah ke Keranjang
        </button>
    </div>

    <script>
        AOS.init();

        // Variabel global untuk cart, diinisialisasi saat DOM siap
        let cart = {};

        // Fungsi helper untuk format Rupiah (JavaScript)
        function formatRupiah(angka) {
            if (typeof angka !== 'number') {
                angka = parseFloat(angka);
            }
            if (isNaN(angka)) {
                return '0';
            }
            const formatted = Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return `${formatted}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const pricePerItem = {{ $dish->price }};
            const quantityInput = document.getElementById('quantity');
            const incrementButton = document.getElementById('increment-quantity');
            const decrementButton = document.getElementById('decrement-quantity');
            const totalPriceSpan = document.getElementById('total-price');
            const addToCartButton = document.getElementById('add-to-cart-button');

            let currentQuantity = parseInt(quantityInput.value);

            // Inisialisasi cart dari localStorage
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                try {
                    cart = JSON.parse(savedCart);
                } catch (e) {
                    console.error("Error parsing cart from localStorage:", e);
                    cart = {}; // Reset cart jika ada error parsing
                }
            }

            function updateTotalPriceDisplay() {
                const total = pricePerItem * currentQuantity;
                totalPriceSpan.textContent = 'Rp ' + formatRupiah(total);
            }

            incrementButton.addEventListener('click', function () {
                currentQuantity++;
                quantityInput.value = currentQuantity;
                updateTotalPriceDisplay();
            });

            decrementButton.addEventListener('click', function () {
                if (currentQuantity > 1) {
                    currentQuantity--;
                    quantityInput.value = currentQuantity;
                    updateTotalPriceDisplay();
                }
            });

            // LOGIKA ADD TO CART
            addToCartButton.addEventListener('click', function(event) {
                const dishName = '{{ $dish->name }}';
                const dishPrice = {{ $dish->price }};
                const dishImage = '{{ $dish->images[0] ?? '/images/makanan.jpg' }}'; // Ambil gambar pertama atau default
                const qtyToAdd = currentQuantity;

                // Animasi "fly-to-cart"
                const flyImg = document.createElement('img');
                flyImg.src = dishImage;
                flyImg.className = 'fly-animation fixed w-16 h-16 rounded-full object-cover';
                flyImg.style.top = event.clientY + 'px';
                flyImg.style.left = event.clientX + 'px';
                document.body.appendChild(flyImg);

                // Hitung posisi target tombol cart di maminko.blade.php (simulasi)
                // Ini adalah perkiraan posisi tombol cart yang tetap di kanan bawah
                // Anda mungkin perlu menyesuaikan nilai ini jika tata letak tombol cart di maminko.blade.php berubah.
                const targetX = window.innerWidth - 70; // Perkiraan posisi X tombol cart
                const targetY = window.innerHeight - 100; // Perkiraan posisi Y tombol cart

                flyImg.style.setProperty('--start-x', event.clientX + 'px');
                flyImg.style.setProperty('--start-y', event.clientY + 'px');
                flyImg.style.setProperty('--target-x', targetX + 'px');
                flyImg.style.setProperty('--target-y', targetY + 'px');

                // Tambahkan ke cart logic
                if (!cart[dishName]) {
                    cart[dishName] = { qty: qtyToAdd, price: dishPrice, image: dishImage };
                } else {
                    cart[dishName].qty += qtyToAdd;
                }
                localStorage.setItem('cart', JSON.stringify(cart)); // Simpan ke localStorage

                // Hapus animasi setelah selesai
                setTimeout(() => {
                    flyImg.remove();
                    alert(`Berhasil menambahkan ${qtyToAdd}x ${dishName} ke keranjang!`);
                    // Opsional: Redirect atau update UI lainnya
                }, 800); // Sesuaikan dengan durasi animasi

                // Reset kuantitas di halaman detail setelah ditambahkan
                currentQuantity = 1;
                quantityInput.value = currentQuantity;
                updateTotalPriceDisplay();
            });


            // LOGIKA IMAGE CAROUSEL
            const carousel = document.getElementById('imageCarousel');
            const prevButton = document.getElementById('carouselPrev');
            const nextButton = document.getElementById('carouselNext');
            const dotsContainer = document.getElementById('carouselDots');
            const items = carousel.querySelectorAll('.carousel-item');
            let currentIndex = 0;

            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
                updateDots();
            }

            function updateDots() {
                dotsContainer.innerHTML = ''; // Clear existing dots
                items.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.classList.add('carousel-dot');
                    if (index === currentIndex) {
                        dot.classList.add('active');
                    }
                    dot.addEventListener('click', () => {
                        currentIndex = index;
                        updateCarousel();
                    });
                    dotsContainer.appendChild(dot);
                });
            }

            prevButton.addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : items.length - 1;
                updateCarousel();
            });

            nextButton.addEventListener('click', () => {
                currentIndex = (currentIndex < items.length - 1) ? currentIndex + 1 : 0;
                updateCarousel();
            });

            // Initial setup
            updateCarousel();
        });
    </script>
</body>
</html>
