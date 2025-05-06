<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .checkout-container {
            padding: 40px 0;
        }
        .cart-item {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .item-name {
            font-weight: 600;
        }
        .item-price {
            color: #ffa500;
            font-weight: 500;
        }
        .total {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h1 class="text-center mb-5 fw-bold">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($cartItems) && is_array($cartItems) && count($cartItems) > 0)
        <div class="mb-4">
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="item-name">{{ $item['name'] }}</h5>
                            <p class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</p>
                        </div>
                        <div>
                            <p class="item-price">Subtotal: Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="total">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
        </div>

        <form action="{{ url('/process-checkout') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Pemesan</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="table_number" class="form-label">Nomor Meja</label>
                <input type="text" class="form-control" id="table_number" name="table_number" required>
            </div>
            <button type="submit" class="btn btn-warning">Pesan Sekarang</button>
        </form>
    @else
        <div class="alert alert-info text-center">
            Keranjang Anda kosong. <a href="/" class="text-decoration-none fw-bold">Belanja dulu yuk!</a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>