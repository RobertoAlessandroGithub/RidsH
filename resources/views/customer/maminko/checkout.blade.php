<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Maminko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        /* Style untuk tombol pembayaran yang aktif */
        .payment-btn.active {
            background-color: #f97316; /* Warna oranye solid */
            color: white;
            border-color: #f97316;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    {{-- PENTING: Pindahkan definisi fungsi formatRupiah() keluar dari komentar agar di-parse oleh PHP --}}
    @php
        $voucher_amount = 10000;

        // Fungsi formatRupiah untuk digunakan di dalam Blade (PHP)
        if (!function_exists('formatRupiah')) {
            function formatRupiah($number) {
                return 'Rp ' . number_format($number, 0, ',', '.');
            }
        }
    @endphp

    <div class="container max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">

        <a href="/maminko" class="text-gray-600 hover:text-orange-500 mb-6 inline-block">&larr; Kembali ke Menu</a>
        <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

        <form id="checkoutForm" action="{{-- route('order.store') --}}" method="POST">
            @csrf

            <input type="hidden" id="cartDataInput" name="cart_data">
            <input type="hidden" id="finalTotalInput" name="final_total">

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up">
                <h2 class="text-xl font-semibold mb-4">Informasi Dine-In</h2>
                <div class="space-y-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                        <input type="text" id="customer_name" name="customer_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" id="customer_phone" name="customer_phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 081234567890" required>
                    </div>
                    <div>
                        <label for="table_number" class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                        <input type="text" id="table_number" name="table_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 12" required>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-xl font-semibold mb-4">Pesanan Anda</h2>
                <div class="space-y-4" id="checkoutItems">
                    <p class="text-gray-500 text-center" id="loadingCartMessage">Memuat item keranjang...</p>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Voucher</span>
                        {{-- Gunakan formatRupiah() dari PHP di sini --}}
                        <span>- {{ formatRupiah($voucher_amount) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-black mt-2">
                        <span>Total</span>
                        <span id="totalDisplay">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-xl font-semibold mb-4">Metode Pembayaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" id="payment-cash" class="payment-btn active w-full border-2 border-gray-300 rounded-lg p-3 text-center transition-all duration-200">
                        Bayar di Kasir (Cash)
                    </button>
                    <button type="button" id="payment-qr" class="payment-btn w-full border-2 border-gray-300 rounded-lg p-3 text-center transition-all duration-200">
                        QRIS
                    </button>
                </div>
                <input type="hidden" id="payment_method" name="payment_method" value="cash">

                <div id="qr-section" class="hidden text-center mt-6 p-4 bg-gray-100 rounded-lg">
                    <p class="mb-4 text-gray-700">Silakan scan atau unduh QRIS di bawah ini untuk melakukan pembayaran.</p>
                    <img src="/images/qris-placeholder.png" alt="QRIS Payment" class="w-48 h-48 mx-auto rounded-md shadow-lg">
                    <a href="/images/qris-placeholder.png" download="MaMinKo_QRIS.png" class="mt-4 inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        Unduh QR
                    </a>
                </div>
            </div>

            <div class="mt-8" data-aos="fade-up" data-aos-delay="300">
                <button type="submit" id="placeOrderButton" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg py-3 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    Pesan Sekarang (Rp 0)
                </button>
            </div>
        </form>

    </div>

    <script>
        AOS.init();

        const VOUCHER_AMOUNT = {{ $voucher_amount }};

        // Fungsi helper untuk format Rupiah (JavaScript)
        function formatRupiah(angka) {
            if (typeof angka !== 'number') {
                angka = parseFloat(angka); // Gunakan parseFloat jika mungkin ada angka desimal, atau parseInt jika selalu integer
            }
            if (isNaN(angka)) {
                return 'Rp 0'; // Handle kasus bukan angka
            }
            const formatted = angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return `Rp ${formatted}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cashBtn = document.getElementById('payment-cash');
            const qrBtn = document.getElementById('payment-qr');
            const paymentMethodInput = document.getElementById('payment_method');
            const qrSection = document.getElementById('qr-section');
            const checkoutItemsContainer = document.getElementById('checkoutItems');
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const totalDisplay = document.getElementById('totalDisplay');
            const placeOrderButton = document.getElementById('placeOrderButton');
            const cartDataInput = document.getElementById('cartDataInput');
            const finalTotalInput = document.getElementById('finalTotalInput');
            const loadingCartMessage = document.getElementById('loadingCartMessage');

            // Event listener untuk tombol Cash
            cashBtn.addEventListener('click', function() {
                cashBtn.classList.add('active');
                qrBtn.classList.remove('active');
                paymentMethodInput.value = 'cash';
                qrSection.classList.add('hidden');
            });

            // Event listener untuk tombol QR
            qrBtn.addEventListener('click', function() {
                qrBtn.classList.add('active');
                cashBtn.classList.remove('active');
                paymentMethodInput.value = 'qr';
                qrSection.classList.remove('hidden');
            });

            // Fungsi untuk mengupdate kuantitas item di cart dan me-render ulang tampilan
            window.updateQty = function(name, change) { // Membuat fungsi global agar bisa diakses dari onclick HTML
                let cart = JSON.parse(localStorage.getItem('cart') || '{}');
                if (!cart[name]) return;

                cart[name].qty += change;
                if (cart[name].qty <= 0) {
                    delete cart[name];
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCheckout(); // Render ulang setelah update
            }

            // Fungsi utama untuk me-render semua detail checkout
            function renderCheckout() {
                const savedCart = localStorage.getItem('cart');
                const cart = savedCart ? JSON.parse(savedCart) : {};

                checkoutItemsContainer.innerHTML = '';
                loadingCartMessage.classList.add('hidden');

                let currentSubtotal = 0;

                if (Object.keys(cart).length === 0) {
                    checkoutItemsContainer.innerHTML = '<p class="text-gray-600 text-center">Keranjang belanja Anda kosong.</p>';
                    placeOrderButton.disabled = true;
                    placeOrderButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    placeOrderButton.disabled = false;
                    placeOrderButton.classList.remove('opacity-50', 'cursor-not-allowed');

                    Object.entries(cart).forEach(([name, item]) => {
                        const itemTotal = item.qty * item.price;
                        currentSubtotal += itemTotal;

                        checkoutItemsContainer.innerHTML += `
                            <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-gray-100">
                                <div class="flex items-center">
                                    <img src="${item.image || '/images/makanan.jpg'}" alt="${name}" class="w-16 h-16 rounded-md object-cover mr-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">${item.qty}x ${name}</p>
                                        <p class="text-sm text-gray-500">${formatRupiah(item.price)}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="updateQty('${name}', -1)" class="p-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <span class="font-medium text-gray-800">${item.qty}</span>
                                    <button type="button" onclick="updateQty('${name}', 1)" class="p-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                    <p class="font-semibold ml-2 text-gray-800">${formatRupiah(itemTotal)}</p>
                                </div>
                            </div>
                        `;
                    });
                }

                subtotalDisplay.textContent = formatRupiah(currentSubtotal);
                let finalTotal = currentSubtotal - VOUCHER_AMOUNT;
                if (finalTotal < 0) finalTotal = 0;
                totalDisplay.textContent = formatRupiah(finalTotal);
                placeOrderButton.innerHTML = `Pesan Sekarang (${formatRupiah(finalTotal)})`;

                cartDataInput.value = JSON.stringify(cart);
                finalTotalInput.value = finalTotal;
            }

            renderCheckout();
        });
    </script>
</body>
</html>
