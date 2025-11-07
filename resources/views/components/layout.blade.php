<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSHP - Selamat Datang</title>
    <link rel="stylesheet" href="style.css">

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

        .nav-kuning {
            background-color: #ffb726;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0.5rem 2rem;
            height: 30px;
            position: absolute;
            width: calc(100% - 4rem);
            top: 70px;
            left: 0;
            z-index: 10;
        }

        .nav-kuning ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        .nav-kuning ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .nav-kuning ul li a:hover {
            color: #444;
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
            <x-nav href="/login" :active="request()->is('login')">Login</x-nav>
            <x-nav href="/" :active="request()->is('/')">Home</x-nav>
            <x-nav href="/organizations" :active="request()->is('organizations')">Struktur Organisasi</x-nav>
            <x-nav href="/layanan" :active="request()->is('layanan')">Layanan Umum</x-nav>
            <x-nav href="/visi" :active="request()->is('visi')">Visi Misi & Tujuan</x-nav>
        </div>
    </nav>

    <nav class="nav-kuning">
        <ul>
            <li><a href="https://unair.ac.id" target="_blank">UNAIR</a></li>
            <li><a href="https://fkh.unair.ac.id" target="_blank">FKH UNAIR</a></li>
            <li><a href="https://cybercampus.unair.ac.id" target="_blank">Cyber Campus</a></li>
        </ul>
    </nav>

    {{ $slot }}

    <x-footer />
</body>

</html>