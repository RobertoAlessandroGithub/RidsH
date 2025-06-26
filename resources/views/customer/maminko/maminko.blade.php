    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Maminko - Menu</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f8f9fa;
            }
            #cartPopup {
                transition: transform 0.3s ease;
                max-height: 60vh;
                overflow-y: auto;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
            .menu-container {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
                justify-content: start;
            }
            .menu-card {
                border-radius: 15px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
                height: 100%;
                min-height: 360px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border-radius: 15px;
            }
            .menu-card:hover {
                transform: scale(1.03);
            }
            .menu-card img {
                width: 100%;
                height: 180px;
                object-fit: cover;
            }
            .menu-info {
                padding: 15px;
            }
            .category-button {
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 8px 16px;
                border-radius: 20px;
                background-color: #f1f1f1;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .category-button.active,
            .category-button:hover {
                background-color: #f97316;
                color: white;
            }
            .search-bar {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 10px;
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }
            .header-image {
                position: relative;
                text-align: center;
            }
            .header-image img {
                width: 100%;
                height: 280px;
                object-fit: cover;
                filter: brightness(0.6);
            }
            .header-title {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                font-size: 3rem;
                font-weight: bold;
            }

            /* Styling untuk animasi "fly to cart" */
            .fly-animation {
                animation: flyToCart 0.8s forwards; /* Durasi dan timing */
                position: fixed;
                z-index: 9999;
                pointer-events: none; /* Biarkan klik tembus ke elemen di bawahnya */
                border-radius: 50%; /* Bentuk bulat */
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                /* Pastikan gambar tidak pecah */
                object-fit: cover;
                width: 50px; /* Ukuran awal yang lebih kecil */
                height: 50px;
                border: 2px solid #f97316; /* Border oranye */
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

            /* Notifikasi */
            #customNotification {
                position: fixed;
                bottom: 40px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                z-index: 10000;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            #customNotification.show {
                opacity: 1;
                visibility: visible;
            }
        </style>
    </head>
    <body class="bg-gray-50">

        <div class="header-image" data-aos="fade-up">
            <img src="/images/makanan.jpg" alt="Maminko">
            <div class="header-title">MaMinKo</div>
        </div>

        <div class="text-center my-6">
            <input type="text" id="searchInput" class="search-bar" placeholder="Search food...">
        </div>

        <div class="text-center mb-6">
            <button class="category-button active" data-category="all" onclick="filterCategory('all', this)">All Products</button>
@foreach($categories as $category)
    <button class="category-button" data-category="{{ $category->slug }}" onclick="filterCategory('{{ $category->slug }}', this)">{{ $category->name }}</button>
@endforeach
        </div>

        <div class="menu-container px-6 max-w-7xl mx-auto" id="menuList">
            {{-- LOOPING DATA MENU DARI DATABASE --}}
            @forelse($menus as $menu)
                <div
                    class="menu-card flex flex-col justify-between min-h-[360px] rounded-2xl cursor-pointer"
                    style="height: 100%;"
                    data-category="{{ $menu->category->slug ?? 'uncategorized' }}" {{-- Menggunakan slug kategori --}}
                    data-name="{{ strtolower($menu->name) }}" {{-- Tambahkan data-name untuk pencarian --}}
                    {{-- Perbaikan di sini: Tambahkan pengecekan $menu->slug agar tidak kosong --}}
                    onclick="window.location.href='{{ !empty($menu->slug) ? route('menu.detail', ['slug' => $menu->slug]) : '#' }}'"
                >
                    <img src="{{ asset('storage/' . ($menu->image ?? 'images/makanan.jpg')) }}" alt="{{ $menu->name }}" class="rounded-t-2xl">

                    <div class="menu-info p-4 flex-1">
                        <h3 class="text-lg font-semibold">{{ $menu->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $menu->description }}</p>
                        <p class="text-orange-500 font-bold mt-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="px-4 pb-4">
                        <button
                            onclick="event.stopPropagation(); addToCart('{{ $menu->name }}', {{ $menu->price }}, '{{ asset('storage/' . ($menu->image ?? 'images/makanan.jpg')) }}')"
                            class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-1 rounded-full text-sm w-full">
                            Pesan
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-center col-span-full">Belum ada menu yang tersedia.</p>
            @endforelse
        </div>

        <!-- Tombol toggle cart -->
        <button onclick="toggleCart()"
            class="fixed bottom-24 right-4 z-50 bg-orange-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-1">
            🛒
            <span id="cartCount" class="bg-red-600 text-white px-2 py-0.5 rounded-full text-xs ml-1">0</span>
        </button>

        <!-- Popup Keranjang -->
        <div id="cartPopup" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 border-t border-gray-200 hidden z-50 max-h-[60vh] overflow-y-auto rounded-t-2xl">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold">Keranjang</h3>
                <button onclick="toggleCart()" class="text-gray-500 hover:text-red-500 text-xl leading-none font-bold">&times;</button>
            </div>
            <div id="cartItems" class="max-h-60 overflow-y-auto mb-6">
                <p class="text-gray-500 text-center">Keranjang Anda kosong.</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="font-semibold">Total: <span id="cartTotal">Rp 0</span></p>
                <a href="{{ route('checkout.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Checkout</a>
            </div>
        </div>

        {{-- Notifikasi Kustom --}}
        <div id="customNotification" class="hidden">
            <span id="notificationMessage"></span>
        </div>

        <script>
            AOS.init();
            
            let cart = {}; // Objek keranjang global

            // Fungsi helper untuk format Rupiah
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

            // Fungsi untuk menampilkan notifikasi kustom (pengganti alert)
            function showNotification(message, type = 'success') {
                const notificationDiv = document.getElementById('customNotification');
                const notificationMessageSpan = document.getElementById('notificationMessage');

                notificationMessageSpan.textContent = message;
                notificationDiv.classList.remove('bg-green-500', 'bg-red-500'); // Bersihkan kelas sebelumnya

                if (type === 'success') {
                    notificationDiv.classList.add('bg-green-500');
                } else if (type === 'error') {
                    notificationDiv.classList.add('bg-red-500');
                }

                notificationDiv.classList.add('show'); // Tampilkan notifikasi
                notificationDiv.classList.remove('hidden');

                setTimeout(() => {
                    notificationDiv.classList.remove('show'); // Sembunyikan setelah 3 detik
                    notificationDiv.classList.add('hidden');
                }, 3000);
            }

            // Memuat keranjang dari localStorage saat DOM siap
            document.addEventListener('DOMContentLoaded', () => {
                const savedCart = localStorage.getItem('cart');
                if (savedCart) {
                    try {
                        cart = JSON.parse(savedCart);
                    } catch (e) {
                        console.error("Error parsing cart from localStorage:", e);
                        cart = {}; // Reset jika JSON tidak valid
                    }
                }
                renderCart(); // Render keranjang saat halaman dimuat
            });

            function addToCart(name, price, image, event) {
    // Pastikan event tidak bubbling
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    // Periksa apakah ini double click
    if (this.clicked) return;
    this.clicked = true;
    
    // Animasi fly to cart
    const flyImg = document.createElement('img');
    flyImg.src = image;
    flyImg.className = 'fly-animation';
    document.body.appendChild(flyImg);

    // Posisi animasi
    const startX = event ? event.clientX : 0;
    const startY = event ? event.clientY : 0;
    flyImg.style.left = startX + 'px';
    flyImg.style.top = startY + 'px';

    const targetButton = document.querySelector('.fixed.bottom-24.right-4');
    const targetRect = targetButton.getBoundingClientRect();
    const targetX = targetRect.left + (targetRect.width / 2);
    const targetY = targetRect.top + (targetRect.height / 2);

    flyImg.style.setProperty('--start-x', `${startX}px`);
    flyImg.style.setProperty('--start-y', `${startY}px`);
    flyImg.style.setProperty('--target-x', `${targetX}px`);
    flyImg.style.setProperty('--target-y', `${targetY}px`);

    // Tambahkan ke keranjang (hanya 1x)
    if (!cart[name]) {
        cart[name] = { qty: 1, price: parseInt(price), image: image };
    } else {
        cart[name].qty += 1;
    }
    localStorage.setItem('cart', JSON.stringify(cart));

    setTimeout(() => {
        flyImg.remove();
        showNotification(`Berhasil menambahkan ${name} ke keranjang!`, 'success');
        renderCart();
        this.clicked = false; // Reset flag
    }, 800);
}


            // Merender isi keranjang di sidebar popup
            function renderCart() {
                const cartItems = document.getElementById('cartItems');
                const cartTotal = document.getElementById('cartTotal');
                const cartCount = document.getElementById('cartCount');

                cartItems.innerHTML = ''; // Kosongkan dulu
                let total = 0;
                let totalQty = 0;

                if (Object.keys(cart).length === 0) {
                    cartItems.innerHTML = '<p class="text-gray-500 text-center">Keranjang Anda kosong.</p>';
                } else {
                    Object.entries(cart).forEach(([name, item]) => {
                        const itemTotal = item.qty * item.price;
                        total += itemTotal;
                        totalQty += item.qty;

                        cartItems.innerHTML += `
                            <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-gray-100">
                                <div class="flex items-center">
                                    <img src="${item.image || '/images/makanan.jpg'}" alt="${name}" class="w-12 h-12 rounded-md object-cover mr-3">
                                    <div>
                                        <p class="font-medium text-gray-800">${name}</p>
                                        <p class="text-sm text-gray-500">Rp ${formatRupiah(item.price)}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQty('${name}', -1)" class="p-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <span class="font-medium text-gray-800">${item.qty}</span>
                                    <button onclick="updateQty('${name}', 1)" class="p-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                    <p class="font-semibold ml-2 text-gray-800">Rp ${formatRupiah(itemTotal)}</p>
                                </div>
                            </div>
                        `;
                    });
                }

                cartTotal.innerText = `Rp ${formatRupiah(total)}`;
                cartCount.innerText = totalQty;

                localStorage.setItem('cart', JSON.stringify(cart)); // Simpan perubahan ke localStorage
            }

            // Fungsi untuk update kuantitas dari dalam popup keranjang
            window.updateQty = function(name, change) {
                if (cart[name]) {
                    cart[name].qty += change;
                    if (cart[name].qty <= 0) {
                        delete cart[name];
                        showNotification(`Item ${name} dihapus dari keranjang.`, 'error');
                    } else {
                        showNotification(`Kuantitas ${name} diperbarui.`, 'success');
                    }
                    renderCart(); // Render ulang keranjang
                }
            }

            // Toggle visibilitas popup keranjang
            function toggleCart() {
                document.getElementById('cartPopup').classList.toggle('hidden');
            }

            // Filter kategori menu
            function filterCategory(category, buttonElement) {
                const cards = document.querySelectorAll('.menu-card');
                // Hapus kelas 'active' dari semua tombol kategori
                document.querySelectorAll('.category-button').forEach(btn => btn.classList.remove('active'));
                // Tambahkan kelas 'active' ke tombol yang diklik
                buttonElement.classList.add('active');

                cards.forEach(card => {
                    const cardCategory = card.dataset.category;
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'flex'; // Tampilkan menu
                    } else {
                        card.style.display = 'none'; // Sembunyikan menu
                    }
                });
            }

            // Pencarian menu
           document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const cards = document.querySelectorAll('.menu-card');
    const activeCategory = document.querySelector('.category-button.active')?.dataset.category || 'all';

    cards.forEach(card => {
        const name = card.querySelector('h3').innerText.toLowerCase();
        const descElements = card.querySelectorAll('.menu-info p');
        let desc = "";
        descElements.forEach(p => {
            desc += p.innerText.toLowerCase() + " ";
        });
        const cardCategory = card.dataset.category;

        const matchesSearch = name.includes(keyword) || desc.includes(keyword);
        const matchesCategory = (activeCategory === 'all' || cardCategory === activeCategory);

        if (matchesSearch && matchesCategory) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
});


        </script>

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

    </body>
    </html>
