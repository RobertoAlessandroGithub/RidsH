<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Maminko</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="container max-w-2xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

    <form id="checkoutForm" action="{{ route('order.store') }}" method="POST">
        @csrf

        <input type="hidden" id="cartDataInput" name="cart_data">
        <input type="hidden" id="finalTotalInput" name="final_total">

        {{-- Informasi Dine-In --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Informasi Pemesan</h2>
            <div class="space-y-4">
                <div>
                    <label>Nama Pemesan</label>
                    <input type="text" name="customer_name" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label>No Telepon</label>
                    <input type="text" name="customer_phone" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label>Nomor Meja</label>
                    <input type="text" name="table_number" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label>Catatan Tambahan (Opsional)</label>
                    <textarea name="order_notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                </div>
            </div>
        </div>

        {{-- Daftar Pesanan --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Pesanan Anda</h2>
            <div id="checkoutItems"></div>

            <div class="border-t pt-4 mt-4 space-y-2">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span id="subtotalDisplay">Rp 0</span>
                </div>
                <div class="flex justify-between text-green-600">
                    <span>Voucher</span>
                    <span>- Rp 10.000</span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span id="totalDisplay">Rp 0</span>
                </div>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Metode Pembayaran</h2>
            <div class="space-y-2">
                <label>
                    <input type="radio" name="payment_method" value="cash" checked> Bayar di Kasir
                </label>
                <label>
                    <input type="radio" name="payment_method" value="qr"> QRIS
                </label>
            </div>
        </div>

        <button type="submit" id="placeOrderButton" class="w-full bg-orange-500 text-white font-bold py-3 rounded">
            Pesan Sekarang (Rp 0)
        </button>

    </form>
</div>

<script>
    const VOUCHER_AMOUNT = 10000;
    const cartDataInput = document.getElementById('cartDataInput');
    const finalTotalInput = document.getElementById('finalTotalInput');
    const checkoutItems = document.getElementById('checkoutItems');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const placeOrderButton = document.getElementById('placeOrderButton');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function renderCheckout() {
        let cart = JSON.parse(localStorage.getItem('cart') || '{}');
        checkoutItems.innerHTML = '';
        let subtotal = 0;

        if (Object.keys(cart).length === 0) {
            checkoutItems.innerHTML = '<p class="text-gray-500 text-center">Keranjang kosong</p>';
            placeOrderButton.disabled = true;
            return;
        }

        Object.entries(cart).forEach(([name, item]) => {
            let itemTotal = item.qty * item.price;
            subtotal += itemTotal;

            checkoutItems.innerHTML += `
                <div class="flex justify-between mb-2">
                    <span>${item.qty}x ${name}</span>
                    <span>${formatRupiah(itemTotal)}</span>
                </div>
            `;
        });

        let finalTotal = subtotal - VOUCHER_AMOUNT;
        if (finalTotal < 0) finalTotal = 0;

        subtotalDisplay.textContent = formatRupiah(subtotal);
        totalDisplay.textContent = formatRupiah(finalTotal);
        placeOrderButton.textContent = `Pesan Sekarang (${formatRupiah(finalTotal)})`;

        cartDataInput.value = JSON.stringify(cart);
        finalTotalInput.value = finalTotal;
    }

    renderCheckout();
</script>

</body>
</html>
