
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maminko - Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tambahkan ini jika pakai Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Tambahkan ini jika pakai AOS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
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
        <button class="category-button active" onclick="filterCategory('all')">All Products</button>
        <button class="category-button" onclick="filterCategory('sushi')">Sushi</button>
        <button class="category-button" onclick="filterCategory('maki')">Maki</button>
        <button class="category-button" onclick="filterCategory('sashimi')">Sashimi</button>
        <button class="category-button" onclick="filterCategory('soups')">Soups</button>
    </div>

    <div class="menu-container px-6 max-w-7xl mx-auto" id="menuList">
        @php
            $menus = [
                ['name' => 'Red Dragon', 'desc' => 'Salmon, Philadelphia cheese, cucumber, avocado', 'price' => 17000, 'category' => 'sushi'],
                ['name' => 'Japanese Salad With Shrimps', 'desc' => 'Shrimp, greens, sesame dressing', 'price' => 20000, 'category' => 'sashimi'],
                ['name' => 'Maki Tuna', 'desc' => 'Tuna, rice, nori seaweed', 'price' => 15000, 'category' => 'maki'],
                ['name' => 'Salmon Sashimi', 'desc' => 'Fresh salmon slices', 'price' => 22000, 'category' => 'sashimi'],
            ];
        @endphp

        @foreach($menus as $menu)
            <div
                class="menu-card flex flex-col justify-between min-h-[360px] rounded-2xl cursor-pointer"
                style="height: 100%;"
                data-category="{{ $menu['category'] }}"
                onclick="window.location.href='{{ route('menu.detail', ['name' => urlencode($menu['name'])]) }}'"
            >
                <img src="/images/makanan.jpg" alt="{{ $menu['name'] }}" class="rounded-t-2xl">

                <div class="menu-info p-4 flex-1">
                    <h3 class="text-lg font-semibold">{{ $menu['name'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $menu['desc'] }}</p>
                    <p class="text-orange-500 font-bold mt-2">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                </div>

                <div class="px-4 pb-4">
                    <button
                        onclick="event.stopPropagation(); addToCart('{{ $menu['name'] }}', {{ $menu['price'] }})"
                        class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-1 rounded-full text-sm w-full">
                        Pesan
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="cartPopup" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 border-t border-gray-200 hidden z-50 max-h-[60vh] overflow-y-auto rounded-t-2xl">
        <h3 class="text-lg font-bold">Keranjang</h3>
        <div id="cartItems" class="max-h-60 overflow-y-auto mb-6"></div>
        <div class="flex justify-between items-center">
            <p class="font-semibold">Total: <span id="cartTotal">Rp .0</span></p>
            <a href="/checkout" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Checkout</a>
        </div>
    </div>

    <form id="cartForm" action="{{ route('checkout.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="cart_data" id="cartDataInput">
    </form>

    <script>
        AOS.init();

        let cart = {};

        function addToCart(name, price) {
            if (!cart[name]) {
                cart[name] = { qty: 1, price: parseInt(price) };
            } else {
                cart[name].qty += 1;
            }
            renderCart();
        }

        function renderCart() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            const cartPopup = document.getElementById('cartPopup');

            cartItems.innerHTML = '';
            let total = 0;

            Object.entries(cart).forEach(([name, item]) => {
                const itemTotal = item.qty * item.price;
                total += itemTotal;

                cartItems.innerHTML += `
                    <div class="flex justify-between items-center mb-1">
                        <div>
                            <p class="font-medium">${name}</p>
                            <p class="text-sm text-gray-500">${item.qty} x Rp ${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="updateQty('${name}', -1)" class="px-2 bg-gray-200 rounded">-</button>
                            <button onclick="updateQty('${name}', 1)" class="px-2 bg-gray-200 rounded">+</button>
                        </div>
                    </div>
                `;
            });

            cartTotal.innerText = `Rp ${formatRupiah(total)}`;
            cartPopup.classList.remove('hidden');
        }

        function updateQty(name, change) {
            if (cart[name]) {
                cart[name].qty += change;
                if (cart[name].qty <= 0) {
                    delete cart[name];
                }
                renderCart();
            }
        }

        function checkout() {
            const form = document.getElementById('cartForm');
            const cartDataInput = document.getElementById('cartDataInput');
            cartDataInput.value = JSON.stringify(cart);
            form.submit();
        }

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function filterCategory(category) {
            const cards = document.querySelectorAll('.menu-card');
            document.querySelectorAll('.category-button').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            const cards = document.querySelectorAll('.menu-card');

            cards.forEach(card => {
                const name = card.querySelector('h3').innerText.toLowerCase();
                const desc = card.querySelector('p').innerText.toLowerCase();
                if (name.includes(keyword) || desc.includes(keyword)) {
                    card.style.display = 'block';
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
