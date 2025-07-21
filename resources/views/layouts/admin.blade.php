<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
        }
        #page-content-wrapper {
            width: 100%;
            min-height: 100vh;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
        }
        .card {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }
        .card .text-xs {
            font-size: 0.7rem;
        }
        .card .h5 {
            font-size: 1.25rem;
        }
        .border-left-primary { border-left: 0.25rem solid #007bff !important; }
        .border-left-success { border-left: 0.25rem solid #28a745 !important; }
        .border-left-info { border-left: 0.25rem solid #17a2b8 !important; }
        .border-left-warning { border-left: 0.25rem solid #ffc107 !important; }

        /* PERUBAHAN: Styling untuk Modal Navigasi */
        .modal-sidebar .modal-dialog {
            margin: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            transform: translateX(-100%);
            transition: transform 0.3s ease-out;
        }
        .modal-sidebar.show .modal-dialog {
            transform: translateX(0);
        }
        .modal-sidebar .modal-content {
            height: 100%;
            border-radius: 0;
            background-color: #2c3e50;
            color: white;
            min-width: 250px;
            max-width: 250px;
        }
        .modal-sidebar .modal-header {
            border-bottom: 1px solid #4a6572;
            color: #f8f9fa;
        }
        .modal-sidebar .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .modal-sidebar .modal-body {
            padding: 0;
        }
        .modal-sidebar .list-group-item {
            background-color: transparent;
            border: none;
            color: #ecf0f1;
            padding: 15px 20px;
            font-size: 1rem;
            transition: background-color 0.3s, color 0.3s;
        }
        .modal-sidebar .list-group-item:hover,
        .modal-sidebar .list-group-item.active {
            background-color: #34495e;
            color: #ffffff;
        }
        .modal-sidebar .list-group-item .fas {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- PERUBAHAN: Tombol ini sekarang membuka Modal -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#navigationModal">
                    <i class="fas fa-bars"></i>
                </button>

                <h5 class="ms-auto me-2 mb-0 d-none d-md-block">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h5>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/30/007bff/FFFFFF?text={{ substr(Auth::user()->name ?? 'A', 0, 1) }}" alt="Profil" class="rounded-circle me-1">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <!-- PERUBAHAN: Modal Navigasi (Pengganti Sidebar) -->
    <div class="modal modal-sidebar fade" id="navigationModal" tabindex="-1" aria-labelledby="navigationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="navigationModalLabel">Hotel Maminko Kitchen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item">
                            <i class="fas fa-fw fa-tachometer-alt"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.menu.index') }}" class="list-group-item">
                            <i class="fas fa-fw fa-utensils"></i>Manajemen Menu
                        </a>
                        <a href="{{ route('admin.menu.create') }}" class="list-group-item">
                            <i class="fas fa-fw fa-plus-circle"></i>Tambah Menu
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item">
                            <i class="fas fa-fw fa-tags"></i>Manajemen Kategori
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item">
                            <i class="fas fa-fw fa-clipboard-list"></i>Manajemen Pesanan
                        </a>
                        <a href="{{ route('admin.cashier.payments') }}" class="list-group-item">
                            <i class="fas fa-fw fa-clipboard-list"></i>Manajemen Pembayaran
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="list-group-item">
                            <i class="fas fa-fw fa-chart-line"></i>Laporan
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="list-group-item text-start w-100">
                                <i class="fas fa-fw fa-sign-out-alt"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        // Script untuk menandai link aktif di sidebar modal
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            document.querySelectorAll('#navigationModal .list-group-item').forEach(item => {
                const linkHref = item.getAttribute('href');
                if (linkHref && currentUrl.includes(linkHref)) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
