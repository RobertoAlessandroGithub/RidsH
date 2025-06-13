<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background-color: #f9f9f9;
}

.header {
    padding: 30px 0;
    background: linear-gradient(135deg, #ffa500, #ff7f50);
    color: white;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    margin-bottom: 30px;
}

.header h1 {
    font-size: 2.5rem;
    color: white;
}

.header p {
    font-size: 1.1rem;
    margin-top: 10px;
    color: #f8f8f8;
}

.menu-card {
    background-color: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease-in-out;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.menu-card img {
    height: 220px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.card-body {
    padding: 20px;
}

.card-title {
    font-weight: 600;
    font-size: 1.3rem;
    color: #333;
}

.card-text {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 15px;
}

.price {
    font-size: 1.2rem;
    font-weight: 600;
    color: #ffa500;
}

.input-group button {
    border-radius: 10px;
    border: 1px solid #ccc;
}

.input-group input {
    border: none;
    background: #f2f2f2;
    border-radius: 10px;
}

.add-to-cart {
    background: #ffa500;
    border: none;
    color: white;
    border-radius: 10px;
    padding: 8px 15px;
    font-weight: 500;
    transition: background 0.3s ease;
}

.add-to-cart:hover {
    background: #ff7f50;
}

.badge-popular {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ff7f50;
    color: white;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
}

.toast-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.toast {
    border-radius: 10px;
}

    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-center display-4 fw-bold">DAFTAR MENU KAMI</h1>
                <div>
                    <a href="/cart" class="btn btn-warning position-relative">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('cart', [])) }}
                        </span>
                    </a>
                </div>
            </div>
            <p class="text-center mb-0">Pilih menu favorit Anda</p>
        </div>
    </div>
    
    <div class="container menu-container">
        <div class="row g-4">
            @foreach($menus as $menu)
                <div class="col-lg-4 col-md-6">
                    <div class="menu-card position-relative">
                        @if($menu->is_popular)
                            <span class="badge-popular"><i class="fas fa-star me-1"></i> POPULAR</span>
                        @endif
                        
                        <img src="{{ asset('storage/'.$menu->image) }}" class="card-img-top" alt="{{ $menu->name }}">
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text">{{ $menu->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                <div class="input-group" style="width: 120px;">
                                    <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                                    <input type="number" class="form-control text-center quantity-input" value="1" min="1" 
                                           data-id="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                                    <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                                </div>
                                <button class="btn btn-order add-to-cart"
        data-id="{{ $menu->id }}"
        data-name="{{ $menu->name }}"
        data-price="{{ $menu->price }}"
        data-image="{{ asset('storage/'.$menu->image) }}">
    <i class="fas fa-shopping-cart me-2"></i>Pesan
</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tombol plus/minus quantity
            document.querySelectorAll('.plus-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                });
            });

            document.querySelectorAll('.minus-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.quantity-input');
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                });
            });

            // Add to cart
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = this.dataset.price;
                    const quantityInput = this.closest('.card-body').querySelector('.quantity-input');
                    const quantity = quantityInput.value;

                    fetch('/add-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id: id,
                            name: name,
                            price: price,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update cart count
                        document.getElementById('cart-count').textContent = data.cart_count;
                        
                        // Show success message
                        const toast = document.createElement('div');
                        toast.className = 'position-fixed bottom-0 end-0 p-3';
                        toast.style.zIndex = '11';
                        toast.innerHTML = `
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Sukses</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    ${data.message}
                                </div>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        
                        // Remove toast after 3 seconds
                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
                    });
                });
            });
        });
    </script>
</body>
</html>