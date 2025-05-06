<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .cart-container {
            padding: 40px 0;
        }
        .cart-item {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .item-name {
            font-weight: 600;
        }
        .item-price {
            color: #ffa500;
            font-weight: 500;
        }
        .btn-remove {
            background: #dc3545;
            color: white;
        }
        .btn-remove:hover {
            background: #c82333;
        }
        .total {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
        }
        .btn-checkout {
            background: #ffa500;
            color: white;
            font-weight: 500;
        }
        .btn-checkout:hover {
            background: #ff7f50;
        }
    </style>
</head>
<body>

<div class="container cart-container">
    <h1 class="text-center mb-5 fw-bold">Keranjang Belanja</h1>

    @if(count($cartItems) > 0)
    @foreach($cartItems as $id => $item)
<tr>
    <td>{{ $item['name'] }}</td>
    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
    <td>{{ $item['quantity'] }}</td>
    <td>
        <form action="{{ url('/remove-from-cart/'.$id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </td>
</tr>
@endforeach


        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="total">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
            <a href="/checkout" class="btn btn-checkout">Lanjut Checkout</a>
        </div>
    @else
        <div class="alert alert-info text-center">
            Keranjang Anda kosong. <a href="/" class="text-decoration-none fw-bold">Belanja dulu yuk!</a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
