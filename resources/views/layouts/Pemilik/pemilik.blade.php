<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pemilik')</title>

    {{-- 1. FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- 2. FONT POPPINS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- 3. CSS GLOBAL --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* === GLOBAL STYLE (Sesuai Request) === */
        body {
            background-color: #f4f7f6;
            margin: 0;
            padding-top: 100px; /* Disesuaikan agar navbar tidak menutupi konten */
        }

        /* === NAVBAR STYLE === */
        .nav-utama {
            background-color: #3ea2c7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 70px;
            position: fixed; /* Fixed agar tetap di atas saat scroll */
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .nav-utama .nav-left,
        .nav-utama .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-utama img { height: 45px; width: auto; }
        .nav-utama .text-bold { font-weight: 700; line-height: 1.2; font-size: 0.9rem; }
        .divider {width: 2px; background-color: #000000ff; height: 40px;}

        /* === DROPDOWN STYLE === */
        .dropdown { position: relative; }
        .dropdown-toggle { cursor: pointer; color: white; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        .dropdown-toggle:hover { color: #ffdd57; }

        /* Style khusus untuk tombol kembali agar terlihat konsisten */
        .btn-nav-kembali {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            padding: 5px 10px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
        }
        .btn-nav-kembali:hover {
            background-color: rgba(255,255,255,0.1);
            color: #ffdd57;
            border-color: #ffdd57;
        }

        .dropdown-menu {
            opacity: 0; visibility: hidden; transform: translateY(10px);
            transition: all 0.3s ease; position: absolute; background-color: #ffffff;
            min-width: 240px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 2000; border-radius: 10px; top: 100%; right: 0;
            margin-top: 15px; padding: 10px 0; border: 1px solid #f0f0f0;
        }

        .dropdown:hover .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu a { color: #333; padding: 12px 20px; text-decoration: none; display: flex; align-items: center; font-size: 14px; font-weight: 500; transition: 0.2s; gap: 10px; }
        .dropdown-menu a:hover { background-color: #f0f9fc; color: #3ea2c7; padding-left: 25px; }
        .dropdown-menu i { width: 20px; text-align: center; color: #3ea2c7; }

        /* === ALERT / FLASH MESSAGE === */
        .alert-container { position: fixed; top: 90px; left: 50%; transform: translateX(-50%); z-index: 9999; width: auto; min-width: 350px; text-align: center; }
        .alert { padding: 12px 25px; border-radius: 50px; margin-bottom: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 12px; font-weight: 500; font-size: 0.95rem; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15); animation: slideInDown 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; background-color: white; border: 1px solid transparent; }
        .alert-success { background-color: #d1e7dd; color: #0f5132; border-color: #badbcc; }
        .alert-danger { background-color: #f8d7da; color: #842029; border-color: #f5c2c7; }
        @keyframes slideInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeOut { to { opacity: 0; transform: translateY(-20px); visibility: hidden; } }

        /* === BUTTONS GLOBAL === */
        .btn { padding: 8px 16px; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem; transition: 0.2s; white-space: nowrap; }
        .btn:hover { transform: translateY(-2px); }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
        .btn-primary { background-color: #3ea2c7; color: white; }
        .btn-primary:hover { background-color: #2e8aa8; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #5a6268; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-info { background-color: #17a2b8; color: white; }
        .btn-outline { border: 1px solid #3ea2c7; color: #3ea2c7; background: white; }
        .btn-outline:hover { background-color: #f0f9fc; }
        .btn-outline.active { background-color: #3ea2c7; color: white; }

        /* === TABLE STYLES === */
        .table-container { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-top: 1rem; max-width: 1200px; margin-left: auto; margin-right: auto; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .table-responsive { overflow-x: auto; }
        .custom-table, .data-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 1rem; border-radius: 8px; overflow: hidden; }
        .custom-table th, .data-table th { background-color: #3ea2c7; color: white; font-weight: 600; padding: 12px 15px; text-align: left; text-transform: uppercase; font-size: 0.9rem; }
        .custom-table td, .data-table td { padding: 12px 15px; border-bottom: 1px solid #eee; color: #555; vertical-align: middle; font-size: 0.95rem; }
        .custom-table tr:nth-child(even), .data-table tr:nth-child(even) { background-color: #f9f9f9; }
        .custom-table tr:hover, .data-table tr:hover { background-color: #eaf6fc; }
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: #e3f2fd; color: #0d47a1; }

        /* === FORM STYLES (Agar create/edit tetap rapi) === */
        .form-container, .main-container { max-width: 800px; margin: 2rem auto; background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .form-header { text-align: center; margin-bottom: 2rem; }
        .form-header h2 { color: #3ea2c7; margin: 0; font-weight: 700; font-size: 1.8rem; }
        .form-header p { color: #777; margin-top: 5px; font-size: 0.95rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Poppins', sans-serif; box-sizing: border-box; transition: 0.3s; font-size: 1rem; }
        .form-control:focus { border-color: #3ea2c7; outline: none; box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2); }
        .is-invalid { border-color: #dc3545 !important; }
        .error-text { color: #dc3545; font-size: 0.85rem; margin-top: 5px; display: block; }
        .divider-dashed { border-top: 1px dashed #ddd; margin: 2rem 0; }
        .row-group { display: flex; gap: 1.5rem; }
        .half { flex: 1; }
        .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem; }

        /* === DETAIL/SHOW STYLES === */
        .detail-container { max-width: 1000px; margin: 2rem auto; }
        .detail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: white; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .detail-header h2 { margin: 0; color: #3ea2c7; font-weight: 700; }
        .date-badge { background: #f0f9fc; color: #3ea2c7; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; margin-top: 5px; display: inline-block; }
        .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .info-card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #444; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.95rem; }
        .info-row label { color: #777; }
        .info-row span { font-weight: 500; color: #333; }
        .doctor-profile { display: flex; align-items: center; gap: 15px; margin-top: 1rem; }
        .avatar-circle { width: 50px; height: 50px; background: #3ea2c7; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; }
        .medical-data-box { background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .medical-item { margin-bottom: 1.5rem; }
        .medical-item .label { display: block; font-size: 0.85rem; font-weight: 600; color: #3ea2c7; text-transform: uppercase; margin-bottom: 5px; }
        .medical-item .content-text { background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid #3ea2c7; margin: 0; line-height: 1.6; }

        @media (max-width: 600px) {
            .row-group { flex-direction: column; gap: 0; }
            .form-actions, .header-actions { flex-direction: column-reverse; }
            .btn { width: 100%; justify-content: center; }
            .data-table { min-width: 600px; } /* Agar bisa scroll horizontal */
        }
    </style>

    @yield('styles')
</head>

<body>

    {{-- NAVBAR PEMILIK --}}
    <nav class="nav-utama">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" alt="UNAIR">
            <span class="text-bold">UNIVERSITAS<br>AIRLANGGA</span>
            <div class="divider"></div>
            <span class="text-bold">RUMAH SAKIT HEWAN PENDIDIKAN</span>
            <img src="{{ asset('images/images.png') }}" alt="Logo RSHP">
        </div>

        <div class="nav-right">
            {{-- TOMBOL KEMBALI DIPERBAIKI (MENGGUNAKAN NAMED ROUTE) --}}
            <a href="{{ route('dashboard.pemilik.dashboard-pemilik') }}" class="btn-nav-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- Dropdown Data Master PEMILIk --}}
            <div class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle">
                    Menu Dokter <i class="fas fa-chevron-down" style="font-size: 0.7em;"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('dashboard.pemilik.daftar-pet.index') }}">
                        <i class="fas fa-file-medical"></i> Daftar Pet
                    </a>
                    <a href="{{ route('dashboard.pemilik.profil.index') }}">
                        <i class="fas fa-user-injured"></i> Profil Pemilik
                    </a>
                    <a href="{{ route('dashboard.pemilik.rekam-medis.index') }}">
                        <i class="fas fa-id-card"></i> Rekam Medis
                    </a>
                    <a href="{{ route('dashboard.pemilik.reservasi.index') }}">
                        <i class="fas fa-id-card"></i> Reservasi
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