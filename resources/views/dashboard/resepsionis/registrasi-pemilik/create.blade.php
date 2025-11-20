<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pemilik Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .main-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .main-container h2 {
            font-size: 2.2rem;
            color: #3ea2c7;
            margin: 0.5rem auto 2rem auto;
            padding: 0.5rem;
            font-weight: 700;
            text-align: center;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #3ea2c7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e8aa8;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 0.95rem;
            text-align: left;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .form-container {
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.8rem;
            margin-top: 2rem;
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
                    <a href="{{ url('dashboard/resepsionis/registrasi-pemilik') }}">Registrasi Pemilik</a>
                    <a href="{{ url('dashboard/resepsionis/registrasi-pet') }}">Registrasi Pet</a>
                    <a href="{{ url('dashboard/resepsionis/temu-dokter') }}">Temu Dokter</a>
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

    <div class="main-container">
        <h2>Tambah Pemilik Baru</h2>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin-top: 0.5rem; margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route('dashboard.resepsionis.registrasi-pemilik.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama Pemilik</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" required>
                    @error('nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_wa">Nomor WhatsApp</label>
                    <input type="text" name="no_wa" id="no_wa" class="form-control @error('no_wa') is-invalid @enderror"
                        value="{{ old('no_wa') }}" required>
                    @error('no_wa')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email (Opsional)</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tambahkan input password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 6 karakter"
                        required>

                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('dashboard.resepsionis.dashboard') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>