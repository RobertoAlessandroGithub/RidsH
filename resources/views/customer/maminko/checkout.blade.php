<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Maminko</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Style untuk menyembunyikan radio button asli */
        input[type="radio"].payment-radio {
            display: none;
        }
        /* Style untuk label yang berfungsi sebagai tombol */
        .payment-label {
            border: 1px solid #d1d5db; /* border-gray-300 */
            padding: 0.75rem 1rem;
            border-radius: 0.5rem; /* rounded-lg */
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease-in-out;
            display: block;
        }
        /* Style untuk label saat radio button di dalamnya terpilih */
        input[type="radio"].payment-radio:checked + .payment-label {
            border-color: #f97316; /* border-orange-500 */
            background-color: #f97316; /* bg-orange-500 */
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<div class="container max-w-2xl mx-auto p-4 sm:p-6">

    <div class="mb-6">
        <a href="{{ route('maminko.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Kembali ke Menu
        </a>
    </div>

    <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

    {{-- Tampilkan pesan error jika ada --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form id="checkoutForm" action="{{ route('order.store') }}" method="POST">
        @csrf
        <input type="hidden" id="cartDataInput" name="cart_data">
        <input type="hidden" id="finalTotalInput" name="final_total">
        <input type="hidden" id="paymentMethodInput" name="payment_method" value="cash"> {{-- Default value --}}

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4 text-center">Informasi Pemesan</h2>
            <div class="space-y-4">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                    <input type="text" id="customer_name" name="customer_name" placeholder="Masukkan nama Anda" required class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">No Telepon</label>
                    <input type="text" id="customer_phone" name="customer_phone" placeholder="Contoh: 081234567890" required class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label for="table_number" class="block text-sm font-medium text-gray-700">Nomor Meja atau Nomor Kamar</label>
                    <input type="text" id="table_number" name="table_number" placeholder="Contoh: 12 atau 305" required class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label for="order_notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                    <textarea id="order_notes" name="order_notes" rows="3" placeholder="Contoh: Tanpa bawang, tidak pedas, dll." class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4 text-center">Pesanan Anda</h2>
            <div id="checkoutItems" class="space-y-4"></div>
            <div class="border-t pt-4 mt-4 space-y-2">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span id="totalDisplay">Rp 0</span>
                </div>
            </div>
        </div>

        {{-- <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-6 text-center">Metode Pembayaran</h2>
            <div class="flex space-x-4 mb-4">
                <div class="flex-1">
                    <input type="radio" id="payCash" name="payment_method_option" value="cash" class="payment-radio" checked>
                    <label for="payCash" class="payment-label">Bayar di Kasir (Cash)</label>
                </div>
                <div class="flex-1">
                    <input type="radio" id="payQris" name="payment_method_option" value="qr" class="payment-radio">
                    <label for="payQris" class="payment-label">QRIS</label>
                </div>
            </div>

            <div id="qrisDisplay" class="hidden text-center border-t pt-6 mt-2">
                <p class="mb-2 text-gray-600">Silakan scan atau unduh QRIS di bawah ini.</p>
                <div class="relative inline-block">
                    <img id="qrisImage" src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=ContohDataQR" alt="QRIS Payment" class="mx-auto my-4 border-2 border-gray-200 rounded-lg p-1">
                    <button type="button" onclick="downloadQRIS()" class="absolute bottom-6 right-2 bg-black bg-opacity-60 text-white text-xs font-bold py-1 px-3 rounded-md">
                        Unduh QR
                    </button>
                </div>
            </div>
        </div> --}}

        <button type="submit" id="placeOrderButton" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg text-lg">
            Pesan Sekarang (Rp 0)
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartDataInput = document.getElementById('cartDataInput');
    const finalTotalInput = document.getElementById('finalTotalInput');
    const checkoutItems = document.getElementById('checkoutItems');
    const totalDisplay = document.getElementById('totalDisplay');
    const placeOrderButton = document.getElementById('placeOrderButton');
    const qrisDisplay = document.getElementById('qrisDisplay');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method_option"]');
    const paymentMethodInput = document.getElementById('paymentMethodInput');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateQuantity(itemName, change) {
        let cart = JSON.parse(localStorage.getItem('cart') || '{}');
        if (cart[itemName]) {
            cart[itemName].qty += change;
            if (cart[itemName].qty <= 0) {
                delete cart[itemName];
            }
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCheckout();
    }

    function downloadQRIS() {
        const qrisImage = document.getElementById('qrisImage');
        const imageUrl = qrisImage.src;
        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = 'QRIS_Pembayaran_Maminko.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ===================================================
    // FUNGSI BARU: Menambahkan event listener ke tombol
    // ===================================================
    function addEventListenersToCartItems() {
        document.querySelectorAll('.btn-decrease').forEach(button => {
            button.addEventListener('click', function() {
                const itemName = this.getAttribute('data-item-name');
                updateQuantity(itemName, -1);
            });
        });

        document.querySelectorAll('.btn-increase').forEach(button => {
            button.addEventListener('click', function() {
                const itemName = this.getAttribute('data-item-name');
                updateQuantity(itemName, 1);
            });
        });
    }

    function renderCheckout() {
        let cart = JSON.parse(localStorage.getItem('cart') || '{}');
        checkoutItems.innerHTML = '';
        let subtotal = 0;

        if (Object.keys(cart).length === 0) {
            checkoutItems.innerHTML = '<p class="text-gray-500 text-center py-4">Keranjang Anda kosong.</p>';
            placeOrderButton.disabled = true;
            placeOrderButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            placeOrderButton.disabled = false;
            placeOrderButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        Object.entries(cart).forEach(([name, item]) => {
            let itemTotal = item.qty * item.price;
            subtotal += itemTotal;
            const imageUrl = item.image || 'https://via.placeholder.com/100';

            // ===================================================
            // PERBAIKAN DI SINI: Menghapus 'onclick' dan menambahkan atribut data
            // ===================================================
            checkoutItems.innerHTML += `
                <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                    <div class="flex items-center">
                        <img src="${imageUrl}" alt="${name}" class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <p class="font-semibold">${name}</p>
                            <p class="text-sm text-gray-600">${formatRupiah(item.price)}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <button type="button" data-item-name="${name}" class="btn-decrease w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full font-bold text-xl text-gray-600 hover:bg-gray-100">-</button>
                        <span class="font-semibold w-5 text-center">${item.qty}</span>
                        <button type="button" data-item-name="${name}" class="btn-increase w-8 h-8 flex items-center justify-center bg-orange-500 text-white rounded-full font-bold text-xl hover:bg-orange-600">+</button>
                    </div>
                </div>
            `;
        });

        let finalTotal = subtotal;

        totalDisplay.textContent = formatRupiah(finalTotal);
        placeOrderButton.textContent = `Pesan Sekarang (${formatRupiah(finalTotal)})`;

        cartDataInput.value = JSON.stringify(cart);
        finalTotalInput.value = finalTotal;

        const selectedPayment = document.querySelector('input[name="payment_method_option"]:checked').value;
        paymentMethodInput.value = selectedPayment;

        // Panggil fungsi untuk menambahkan listener setelah HTML di-render
        addEventListenersToCartItems();
    }

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', (event) => {
            const selectedValue = event.target.value;
            if (selectedValue === 'qr') {
                qrisDisplay.classList.remove('hidden');
            } else {
                qrisDisplay.classList.add('hidden');
            }
            paymentMethodInput.value = selectedValue;
        });
    });

    renderCheckout();
});
</script>

</body>
</html>
