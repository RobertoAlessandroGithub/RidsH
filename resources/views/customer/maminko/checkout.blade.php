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
<body class="bg-gray-50">

    {{-- Contoh Data Cart (Anda bisa mengganti ini dengan data dari Controller) --}}
    @php
        $cartItems = [
            ['name' => 'Red Dragon', 'qty' => 2, 'price' => 17000, 'image' => '/images/makanan.jpg'],
            ['name' => 'Salmon Sashimi', 'qty' => 1, 'price' => 22000, 'image' => '/images/makanan.jpg'],
        ];

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        $voucher = 10000; // Contoh diskon voucher
        $total = $subtotal - $voucher;

        // Fungsi format Rupiah untuk digunakan di dalam blade
        function formatRupiah($number) {
            return 'Rp ' . number_format($number, 0, ',', '.');
        }
    @endphp

    <div class="container max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">

        <a href="maminko" class="text-gray-600 hover:text-orange-500 mb-6 inline-block">&larr; Kembali ke Menu</a>
        <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

        {{-- Gunakan tag form untuk membungkus semua input --}}
        <form action="{{-- route('order.store') --}}" method="POST">
            @csrf

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up">
                <h2 class="text-xl font-semibold mb-4">Informasi Dine-In</h2>
                <div class="space-y-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                        <input type="text" id="customer_name" name="customer_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div>
                        <label for="table_number" class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                        <input type="number" id="table_number" name="table_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 12" required>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-xl font-semibold mb-4">Pesanan Anda</h2>
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-md object-cover mr-4">
                            <div>
                                <p class="font-semibold">{{ $item['qty'] }}x {{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ formatRupiah($item['price']) }}</p>
                            </div>
                        </div>
                        <p class="font-semibold">{{ formatRupiah($item['price'] * $item['qty']) }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Detail Kalkulasi --}}
                <div class="border-t border-gray-200 mt-6 pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ formatRupiah($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Voucher</span>
                        <span>- {{ formatRupiah($voucher) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-black mt-2">
                        <span>Total</span>
                        <span>{{ formatRupiah($total) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-xl font-semibold mb-4">Metode Pembayaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Tombol Cash --}}
                    <button type="button" id="payment-cash" class="payment-btn active w-full border-2 border-gray-300 rounded-lg p-3 text-center transition-all duration-200">
                        Bayar di Kasir (Cash)
                    </button>
                    {{-- Tombol QRIS --}}
                    <button type="button" id="payment-qr" class="payment-btn w-full border-2 border-gray-300 rounded-lg p-3 text-center transition-all duration-200">
                        QRIS
                    </button>
                </div>
                <input type="hidden" id="payment_method" name="payment_method" value="cash">

                {{-- Bagian QRIS (muncul saat tombol QRIS diklik) --}}
                <div id="qr-section" class="hidden text-center mt-6 p-4 bg-gray-100 rounded-lg">
                    <p class="mb-4 text-gray-700">Silakan scan atau unduh QRIS di bawah ini untuk melakukan pembayaran.</p>
                    <img src="/images/qris-placeholder.png" alt="QRIS Payment" class="w-48 h-48 mx-auto rounded-md shadow-lg">
                    <a href="/images/qris-placeholder.png" download="MaMinKo_QRIS.png" class="mt-4 inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        Unduh QR
                    </a>
                </div>
            </div>

            <div class="mt-8" data-aos="fade-up" data-aos-delay="300">
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg py-3 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    Pesan Sekarang ({{ formatRupiah($total) }})
                </button>
            </div>
        </form>

    </div>

    <script>
        AOS.init();

        document.addEventListener('DOMContentLoaded', function () {
            const cashBtn = document.getElementById('payment-cash');
            const qrBtn = document.getElementById('payment-qr');
            const paymentMethodInput = document.getElementById('payment_method');
            const qrSection = document.getElementById('qr-section');

            // Event listener untuk tombol Cash
            cashBtn.addEventListener('click', function() {
                // Atur style tombol
                cashBtn.classList.add('active');
                qrBtn.classList.remove('active');

                // Atur value input
                paymentMethodInput.value = 'cash';

                // Sembunyikan bagian QR
                qrSection.classList.add('hidden');
            });

            // Event listener untuk tombol QR
            qrBtn.addEventListener('click', function() {
                // Atur style tombol
                qrBtn.classList.add('active');
                cashBtn.classList.remove('active');

                // Atur value input
                paymentMethodInput.value = 'qr';

                // Tampilkan bagian QR
                qrSection.classList.remove('hidden');
            });
        });
    </script>

</body>
</html>
