<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pet Baru</title>
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
            max-width: 650px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 2.2rem;
            color: #3ea2c7;
            margin: 0.5rem auto 2rem auto;
            padding: 0.5rem;
            font-weight: 700;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-control,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-control:focus,
        select:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #3ea2c7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e8aa8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
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
        <h2>Registrasi Pet Baru</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin-top: 0.5rem; margin-bottom: 0;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.resepsionis.registrasi-pet.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">Nama Hewan</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                    class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                    class="form-control @error('tanggal_lahir') is-invalid @enderror">
                @error('tanggal_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="warna_tanda">Warna / Tanda Khusus</label>
                <input type="text" id="warna_tanda" name="warna_tanda" value="{{ old('warna_tanda') }}"
                    class="form-control @error('warna_tanda') is-invalid @enderror">
                @error('warna_tanda') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="M" {{ old('jenis_kelamin') == 'M' ? 'selected' : '' }}>Jantan (M)</option>
                    <option value="F" {{ old('jenis_kelamin') == 'F' ? 'selected' : '' }}>Betina (F)</option>
                </select>
                @error('jenis_kelamin') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="idjenis_hewan">Jenis Hewan</label>
                <select id="idjenis_hewan" name="idjenis_hewan"
                    class="form-control @error('idjenis_hewan') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis Hewan --</option>
                    @foreach($jenisList as $jenis)
                        <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                            {{ $jenis->nama_jenis_hewan }}
                        </option>
                    @endforeach
                </select>
                @error('idjenis_hewan') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="idras_hewan">Ras Hewan</label>
                <select id="idras_hewan" name="idras_hewan"
                    class="form-control @error('idras_hewan') is-invalid @enderror" required>
                    <option value="">-- Pilih Ras Hewan --</option>
                    @foreach($rasList as $ras)
                        <option value="{{ $ras->idras_hewan }}" {{ old('idras_hewan') == $ras->idras_hewan ? 'selected' : '' }}>
                            {{ $ras->nama_ras }}
                        </option>
                    @endforeach
                </select>
                @error('idras_hewan') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="idpemilik">Pemilik Hewan</label>
                <select id="idpemilik" name="idpemilik" class="form-control @error('idpemilik') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach($pemilikList as $pemilik)
                        <option value="{{ $pemilik->idpemilik }}" {{ old('idpemilik') == $pemilik->idpemilik ? 'selected' : '' }}>
                            {{ $pemilik->nama }}
                        </option>
                    @endforeach
                </select>
                @error('idpemilik') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.resepsionis.dashboard') }}" class="btn btn-secondary"> {{-- Mengarahkan ke
                    dashboard resepsionis --}}
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

</body>

</html>