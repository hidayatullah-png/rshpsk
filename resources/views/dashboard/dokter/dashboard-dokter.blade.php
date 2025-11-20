<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWAS ADMIN</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f4f7f6;
            padding-top: 100px;
        }

        body {
            margin: 0;
            background-color: #f4f7f6;
        }

        .nav-utama {
            background-color: #3ea2c7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 2rem;
            height: 60px;
            position: absolute;
            width: calc(100% - 4rem);
            top: 0;
            left: 0;
            z-index: 10;
        }

        .nav-utama .nav-left,
        .nav-utama .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-utama img {
            height: 50px;
            width: auto;
        }

        .nav-utama .nav-left .text-bold {
            font-weight: bold;
            line-height: 1.2;
            color: black;
            /* Diubah menjadi hitam */
        }

        .nav-utama a {
            text-decoration: none;
            color: white;
            /* Link tetap putih */
            font-weight: 500;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .nav-utama a:hover {
            color: #ffdd57;
            transform: scale(1.05);
        }

        .nav-utama a.active {
            color: #ffdd57;
            /* Warna sama seperti hover */
            border-bottom: 1px solid #ffdd57;
            /* Garis bawah indikator aktif */
            transform: scale(1.1);
            /* Sedikit membesar saat aktif */
        }

        .divider {
            width: 2px;
            background-color: black;
            /* Divider juga diubah hitam agar serasi */
            height: 40px;
            opacity: 0.7;
        }

        .footer {
            background-color: #3ea2c7;
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            margin-top: 1rem;
        }

        .footer-container {
            max-width: 1000px;
            margin: 0 auto;
            line-height: 1;
            font-size: 15px;
        }

        .footer p {
            margin: 0.3rem 0;
        }

        .footer a {
            color: #ffdd57;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* === CSS BARU UNTUK DROPDOWN (VERSI MODERN) === */
        .dropdown {
            position: relative;
            /* Penting untuk positioning menu */
            display: inline-block;
        }

        /* Style untuk tombol dropdown agar mirip link nav lain */
        .dropdown-toggle {
            cursor: pointer;
            /* Menerapkan style 'active' jika request()->is('data-master*') */
        }

        /* Opsi: Tambahkan panah kecil ke tombol dropdown */
        .dropdown-toggle::after {
            content: ' ▼';
            /* Panah ke bawah */
            font-size: 0.7em;
            vertical-align: middle;
            margin-left: 5px;
        }

        /* Kontainer menu dropdown (tersembunyi) */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;

            position: absolute;
            background-color: #ffffff;
            min-width: 230px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            z-index: 20;
            border-radius: 8px;
            top: 100%;
            right: 0;
            margin-top: 8px;
            max-height: 300px;
            overflow-y: auto;
            padding: 0.5rem 0;
        }

        /* Link di dalam menu dropdown */
        .dropdown-menu a {
            color: black !important;
            /* Paksa warna jadi hitam */
            padding: 10px 16px;
            /* Padding Y/X */
            text-decoration: none;
            display: block;
            font-size: 14px;
            font-weight: 500;
            /* Reset style dari .nav-utama a */
            transform: none !important;

            margin: 0 0.5rem;
            /* Margin di dalam menu */
            border-radius: 4px;
            /* Radius untuk item */
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-menu a:last-child {
            border-bottom: none !important;
            /* Tidak ada border */
        }

        /* Warna link dropdown saat di-hover */
        .dropdown-menu a:hover {
            background-color: #f3f4f6;
            /* Abu-abu muda */
            color: #3ea2c7 !important;
            /* Warna hover */
            transform: none !important;
        }

        /* Tampilkan menu saat .dropdown di-hover */
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            /* Kembali ke posisi normal */
        }

        .dropdown:hover .dropdown-toggle {
            color: #ffdd57;
        }

        /* Style untuk header box dan elemen tambahan */
        .header {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem;
            color: black;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .header h2 {
            margin: 0 0 0.5rem 0;
        }

        .header p {
            margin: 0;
        }

        .badge {
            display: inline-block;
            background: #3ea2c7;
            color: white;
            font-weight: bold;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            margin-left: 0.3rem;
        }

        /* Divider hitam */
        .divider {
            width: 2px;
            background-color: #000000ff;
            height: 40px;
        }
    </style>
</head>

<body>
    <nav class="nav-utama">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo UNAIR">
            <span class="text-bold">UNIVERSITAS<br>AIRLANGGA</span>
            <div class="divider"></div>
            <span class="text-bold">RUMAH SAKIT HEWAN PENDIDIKAN</span>
            <img src="{{ asset('images/images.png') }}" alt="Logo RSHP">
        </div>

        <div class="nav-right">
            <!-- DROPDOWN MENU -->
            <div class="dropdown">
                <a href="javascript:void(0)"
                    class="dropdown-toggle {{ request()->is('data-*') || request()->is('manajemen-role') || request()->is('jenis-hewan') || request()->is('ras-hewan') || request()->is('list-temu-dokter') || request()->is('rekam-medis') ? 'active' : '' }}">
                    Data Master
                </a>
                <div class="dropdown-menu">
                    <a href="{{ url('dashboard/dokter/rekam-medis') }}">Rekam Medis</a>
                </div>
            </div>

            <!-- ✅ LOGOUT LINK DENGAN FORM POST (AMAN) -->
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </nav>

    <div class="header">
        <h2>Selamat datang di halaman khusus dokter</h2>
        @php
            $nama_user = session('user_name', 'User');
            $nama_role = session('user_role_name', 'Tidak diketahui');
        @endphp

        <p>
            Halo <b>{{ $nama_user }}</b>, anda login sebagai
            <span class="badge">{{ $nama_role }}</span>
        </p>
    </div>

</body>

</html>