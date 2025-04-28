<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #ffa502;
            --dark-color: #2f3542;
            --light-color: #f1f2f6;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .menu-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            background: white;
        }
        
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .menu-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }
        
        .card-text {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .btn-order {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        
        .badge-popular {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4757;
            color: white;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .menu-container {
            padding-bottom: 3rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1 class="text-center display-4 fw-bold">DAFTAR MENU KAMI</h1>
            <p class="text-center mb-0">Pilih menu favorit Anda</p>
        </div>
    </div>
    
    <div class="container menu-container">
        <div class="row g-4">
            @foreach($menus as $menu)
                <div class="col-lg-4 col-md-6">
                    <div class="menu-card position-relative">
                        <!-- Popular badge (conditional) -->
                        @if($menu->is_popular)
                            <span class="badge-popular"><i class="fas fa-star me-1"></i> POPULAR</span>
                        @endif
                        
                        <!-- Menu Image -->
                        <img src="{{ asset('storage/'.$menu->image) }}" class="card-img-top">
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text">{{ $menu->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                <button class="btn btn-order">
                                    <i class="fas fa-shopping-cart me-2"></i>Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Add animation when scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const menuCards = document.querySelectorAll('.menu-card');
            
            const animateOnScroll = () => {
                menuCards.forEach(card => {
                    const cardPosition = card.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.3;
                    
                    if(cardPosition < screenPosition) {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }
                });
            };
            
            // Set initial state
            menuCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `all 0.5s ease ${index * 0.1}s`;
            });
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on load
        });
    </script>
</body>
</html>