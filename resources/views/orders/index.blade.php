<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --secondary-color: #dc3545;
            --secondary-dark: #b02a37;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --text-color: #343a40;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Arial', sans-serif;
            color: var(--text-color);
        }

        h1 {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .table {
            background-color: var(--white);
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        th {
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
            vertical-align: middle;
        }

        td {
            text-align: center;
            vertical-align: middle;
        }

        .card {
            margin-top: 20px;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .order-item {
            margin-bottom: 5px;
            font-size: 0.95rem;
        }

        .btn-custom {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--secondary-dark);
        }

        .alert {
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .table-hover tbody tr:hover {
            background-color: #e9f5ff;
            cursor: pointer;
        }

        .list-unstyled li {
            padding: 5px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .list-unstyled li:last-child {
            border-bottom: none;
        }

        /* Style baris jika sudah diantar */
        .delivered {
            background-color: #d4edda !important;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Daftar Pesanan</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pemesan</th>
                        <th>Nomor Meja</th>
                        <th>Item Keranjang</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr id="order-row-{{ $order->id }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->table_number }}</td>
                            <td>
                                @php
                                    $cartItems = json_decode($order->cart_items);
                                @endphp
                                <ul class="list-unstyled">
                                    @foreach($cartItems as $item)
                                        <li class="order-item">
                                            <strong>{{ $item->name }}</strong> ({{ $item->quantity }}) - Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                <button class="btn btn-sm btn-success checklist-btn" data-order-id="{{ $order->id }}">✔ Menunggu Pesanan </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Nama Restoran. Semua hak dilindungi.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.checklist-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const row = document.getElementById('order-row-' + orderId);

                // Tambahkan class delivered ke baris
                row.classList.add('delivered');

                // Ubah tombol
                this.textContent = '✔ Sudah Diantar';
                this.disabled = true;
                this.classList.remove('btn-success');
                this.classList.add('btn-secondary');
            });
        });
    });
</script>
</body>
</html>
