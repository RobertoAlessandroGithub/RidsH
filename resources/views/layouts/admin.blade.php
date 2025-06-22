<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5; /* Latar belakang abu-abu muda */
        }
        #wrapper {
            display: flex;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background-color: #2c3e50; /* Warna gelap untuk sidebar */
            color: white;
            transition: all 0.3s;
        }
        #sidebar.toggled {
            margin-left: -250px; /* Sembunyikan sidebar */
        }
        #sidebar .sidebar-heading {
            font-size: 1.2rem;
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #4a6572;
            color: #f8f9fa;
        }
        #sidebar .list-group-item {
            background-color: transparent;
            border: none;
            color: #ecf0f1;
            padding: 15px 20px;
            font-size: 1rem;
            transition: background-color 0.3s, color 0.3s;
        }
        #sidebar .list-group-item:hover,
        #sidebar .list-group-item.active {
            background-color: #34495e; /* Hover/active color */
            color: #ffffff;
            border-radius: 0; /* Remove border-radius on list items */
        }
        #sidebar .list-group-item .fas {
            width: 25px; /* Fixed width for icons */
            text-align: center;
            margin-right: 10px;
        }
        #page-content-wrapper {
            width: 100%;
            overflow-x: hidden;
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

        @media (min-width: 768px) {
            #sidebar {
                margin-left: 0;
            }
            #sidebar.toggled {
                margin-left: -250px;
            }
            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar">
            <div class="sidebar-heading">Hotel Maminko Admin</div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item">
                    <i class="fas fa-fw fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('menu.index') }}" class="list-group-item">
                    <i class="fas fa-fw fa-utensils"></i>Manajemen Menu
                </a>
                <a href="{{ route('menu.create') }}" class="list-group-item">
                    <i class="fas fa-fw fa-plus-circle"></i>Tambah Menu
                </a>
                {{-- Anda perlu membuat route dan controller untuk kategori --}}
                <a href="#" class="list-group-item">
                    <i class="fas fa-fw fa-tags"></i>Manajemen Kategori
                </a>
                <a href="{{ route('orders.index') }}" class="list-group-item">
                    <i class="fas fa-fw fa-clipboard-list"></i>Manajemen Pesanan
                </a>
                {{-- Contoh link lain --}}
                <a href="#" class="list-group-item">
                    <i class="fas fa-fw fa-chart-line"></i>Laporan
                </a>
                <a href="#" class="list-group-item">
                    <i class="fas fa-fw fa-cogs"></i>Pengaturan
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="list-group-item text-start w-100">
                        <i class="fas fa-fw fa-sign-out-alt"></i>Logout
                    </button>
                </form>
            </div>
        </div>
        <!-- /#sidebar -->

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    {{-- Tombol toggle sidebar untuk tampilan mobile --}}
                    <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <h5 class="ms-auto me-2 mb-0 d-none d-md-block">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h5>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/30/007bff/FFFFFF?text=A" alt="Profil" class="rounded-circle me-1">
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
                {{-- Lokasi konten utama dari halaman yang diperluas --}}
                @yield('content')
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- PENTING: PASTIKAN BARIS INI ADA --}}
    <script>
        // Script untuk toggle sidebar (untuk tampilan mobile)
        var sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                document.getElementById('wrapper').classList.toggle('toggled');
            });
        }

        // Script untuk menandai link aktif di sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('#sidebar .list-group-item').forEach(item => {
                const linkHref = item.getAttribute('href');
                if (linkHref && currentPath.startsWith(linkHref) && linkHref !== '#') {
                    // Cek jika link adalah root, hanya aktif jika path persis
                    if (linkHref === '{{ route('admin.dashboard') }}' && currentPath === '{{ route('admin.dashboard') }}') {
                        item.classList.add('active');
                    } else if (linkHref !== '{{ route('admin.dashboard') }}') {
                        item.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>
