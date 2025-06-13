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
                ['name' => 'Red Dragon', 'desc' => 'Salmon, Philadelphia cheese, cucumber, avocado', 'price' => '17 GEL', 'category' => 'sushi'],
                ['name' => 'Japanese Salad With Shrimps', 'desc' => 'Shrimp, greens, sesame dressing', 'price' => '20 GEL', 'category' => 'sashimi'],
                ['name' => 'Maki Tuna', 'desc' => 'Tuna, rice, nori seaweed', 'price' => '15 GEL', 'category' => 'maki'],
                ['name' => 'Salmon Sashimi', 'desc' => 'Fresh salmon slices', 'price' => '22 GEL', 'category' => 'sashimi'],
            ];
        @endphp

        @foreach($menus as $menu)
            <a href="{{ route('menu.detail', ['name' => urlencode($menu['name'])]) }}" style="text-decoration: none; color: inherit;">
                <div class="menu-card" data-category="{{ $menu['category'] }}">
                    <img src="/images/makanan.jpg" alt="{{ $menu['name'] }}">
                    <div class="menu-info">
                        <h3 class="text-lg font-semibold">{{ $menu['name'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $menu['desc'] }}</p>
                        <p class="text-orange-500 font-bold mt-2">{{ $menu['price'] }}</p>
                        <button onclick="addToCart('{{ $menu['name'] }}', '{{ $menu['price'] }}')" class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-1 rounded-full text-sm">
                        Pesan
                    </button>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div id="cartPopup" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4 border-t border-gray-200 hidden z-50">
        <h3 class="text-lg font-bold mb-2">Keranjang</h3>
        <div id="cartItems" class="max-h-60 overflow-y-auto mb-2"></div>
        <div class="flex justify-between items-center">
            <p class="font-semibold">Total: <span id="cartTotal">0 GEL</span></p>
            <button onclick="checkout()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Checkout</button>
        </div>
    </div>

    <script>
        AOS.init();
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

    <script>
        AOS.init();

        let cart = {};

        function addToCart(name, price) {
            if (!cart[name]) {
                cart[name] = { qty: 1, price: parseFloat(price) };
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
                            <p class="text-sm text-gray-500">${item.qty} x ${item.price} GEL</p>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="updateQty('${name}', -1)" class="px-2 bg-gray-200 rounded">-</button>
                            <button onclick="updateQty('${name}', 1)" class="px-2 bg-gray-200 rounded">+</button>
                        </div>
                    </div>
                `;
            });

            cartTotal.innerText = `${total.toFixed(2)} GEL`;
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
            alert('Fitur checkout belum dibuat!');
        }

        // Search dan Filter tetap sama
    </script>

</body>
</html>
