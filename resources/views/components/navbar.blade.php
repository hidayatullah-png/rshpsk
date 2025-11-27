<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
            /* display: none; */
            /* Diganti dengan opacity */
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            /* Mulai 10px ke bawah */
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;

            position: absolute;
            background-color: #ffffff;
            /* Latar belakang putih */
            min-width: 230px;
            /* Sedikit lebih lebar */
            /* Shadow modern (Tailwind style) */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            z-index: 20;
            /* Pastikan di atas elemen lain */
            border-radius: 8px;
            /* Lebih bulat */
            top: 100%;
            /* Muncul tepat di bawah tombol */
            /* left: 0; */
            /* DIUBAH */
            right: 0;
            /* Menyejajarkan menu di sisi KANAN tombol */
            margin-top: 8px;
            /* Jarak dari tombol */
            max-height: 300px;
            /* Batas tinggi jika menu terlalu panjang */
            overflow-y: auto;
            /* Tambah scroll jika perlu */
            padding: 0.5rem 0;
            /* Padding internal */
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
            /* display: block; */
            /* Diganti dengan opacity */
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            /* Kembali ke posisi normal */
        }

        /* Pastikan tombol dropdown juga berganti warna saat di-hover */
        .dropdown:hover .dropdown-toggle {
            color: #ffdd57;
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
            <a href="{{ route('dashboard.admin.dashboard-admin') }}"
                class="{{ request()->is('dashboard/admin') ? 'active' : '' }}">
                Dashboard
            </a>
            <!-- DROPDOWN MENU -->
            <div class="dropdown">
                <a href="javascript:void(0)"
                    class="dropdown-toggle {{ request()->is('data-*') || request()->is('manajemen-role') || request()->is('jenis-hewan') || request()->is('ras-hewan') || request()->is('list-temu-dokter') || request()->is('rekam-medis') ? 'active' : '' }}">
                    Data Master
                </a>
                <div class="dropdown-menu">
                    <a href="{{ url('dashboard/admin/role-user') }}">Manajemen User</a>
                    <a href="{{ url('dashboard/admin/role') }}">Manajemen Role</a>
                    <a href="{{ url('dashboard/admin/jenis-hewan') }}">Jenis Hewan</a>
                    <a href="{{ url('dashboard/admin/ras-hewan') }}">Ras Hewan</a>
                    <a href="{{ url('dashboard/admin/kategori') }}">Data Kategori</a>
                    <a href="{{ url('dashboard/admin/kategori-klinis') }}">Data Kategori Klinis</a>
                    <a href="{{ url('dashboard/admin/kode-tindakan-terapi') }}">Data Kode Tindakan Terapi</a>
                    <a href="{{ url('dashboard/admin/rekam-medis') }}">Rekam Medis</a>
                    <a href="{{ url('dashboard/admin/pemilik') }}">Data Pemilik</a>
                    <a href="{{ url('dashboard/admin/pet') }}">Data Pet</a>
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

    {{ $slot }}

</body>

</html>