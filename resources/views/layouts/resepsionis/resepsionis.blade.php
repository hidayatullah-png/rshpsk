<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Resepsionis')</title>

    {{-- 1. FONT AWESOME (Agar ikon centang dan menu muncul) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- 2. GOOGLE FONTS (Opsional, agar lebih modern) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* === GLOBAL STYLE === */
        body {
            background-color: #f4f7f6;
            margin: 0;
            padding-top: 80px;
            /* Sesuaikan dengan tinggi navbar */
        }

        /* === NAVBAR STYLE === */
        .nav-utama {
            background-color: #3ea2c7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 70px;
            position: fixed;
            /* Fixed agar navbar diam saat scroll */
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            /* Penting agar padding tidak melebarkan width */
        }

        .nav-utama .nav-left,
        .nav-utama .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-utama img {
            height: 45px;
            width: auto;
        }

        .nav-utama .text-bold {
            font-weight: 700;
            line-height: 1.2;
            font-size: 0.9rem;
        }
        /* Divider hitam */
        .divider {
            width: 2px;
            background-color: #000000ff;
            height: 40px;
        }

        /* === DROPDOWN STYLE === */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            cursor: pointer;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .dropdown-toggle:hover {
            color: #ffdd57;
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            position: absolute;
            background-color: #ffffff;
            min-width: 240px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 2000;
            border-radius: 10px;
            top: 100%;
            right: 0;
            margin-top: 15px;
            padding: 10px 0;
            border: 1px solid #f0f0f0;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            color: #333;
            padding: 12px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
            gap: 10px;
        }

        .dropdown-menu a:hover {
            background-color: #f0f9fc;
            color: #3ea2c7;
            padding-left: 25px;
            /* Efek geser */
        }

        .dropdown-menu i {
            width: 20px;
            text-align: center;
            color: #3ea2c7;
        }


        /* === ALERT / FLASH MESSAGE (INI YANG SEBELUMNYA HILANG) === */
        .alert-container {
            position: fixed;
            top: 90px;
            /* Muncul sedikit di bawah navbar */
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: auto;
            min-width: 350px;
            text-align: center;
        }

        .alert {
            padding: 12px 25px;
            border-radius: 50px;
            /* Bentuk kapsul */
            margin-bottom: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            animation: slideInDown 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            background-color: white;
            /* Default background */
        }

        /* Warna Sukses (Hijau) */
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        /* Warna Error (Merah) */
        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
                visibility: hidden;
            }
        }

        /* === CONTAINER KONTEN === */
        .main-content {
            padding: 0 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>

    @yield('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="nav-utama">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" alt="UNAIR">
            <span class="text-bold">UNIVERSITAS<br>AIRLANGGA</span>
            <div class="divider"></div>
            <span class="text-bold">RUMAH SAKIT HEWAN PENDIDIKAN</span>
            <img src="{{ asset('images/images.png') }}" alt="Logo RSHP">
        </div>

        <div class="nav-right">
            {{-- Dropdown Data Master --}}
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle">
                    Data Master <i class="fas fa-chevron-down"
                    style="font-size: 0.7em;"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ url('dashboard/resepsionis/registrasi-pemilik/create') }}">
                        <i class="fas fa-user-plus"></i> Registrasi Pemilik
                    </a>
                    <a href="{{ url('dashboard/resepsionis/registrasi-pet/create') }}">
                        <i class="fas fa-paw"></i> Registrasi Pet
                    </a>
                    <a href="{{ url('dashboard/resepsionis/temu-dokter/create') }}">
                        <i class="fas fa-stethoscope"></i> Temu Dokter
                    </a>
                </div>
            </div>

            {{-- Logout --}}
            <a href="{{ route('logout') }}" class="dropdown-toggle"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    <div class="alert-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || session('danger'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <span>{{ session('error') ?? session('danger') }}</span>
            </div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Auto remove alert after 4 seconds
        setTimeout(function () {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                alert.style.animation = "fadeOut 0.6s forwards";
                setTimeout(function () { alert.remove(); }, 600);
            });
        }, 4000); 
    </script>

    @yield('scripts')

</body>

</html>