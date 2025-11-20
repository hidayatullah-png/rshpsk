<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kode Tindakan Terapi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* --- General Body & Font --- */
        body {
            margin: 0;
            background-color: #f4f7f6;
            color: #333;
            padding-top: 110px;
        }

        /* --- Main Content Container --- */
        .main-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .main-container h2 {
            font-size: 2rem;
            color: #3ea2c7;
            margin-top: 0;
            margin-bottom: 2rem;
            font-weight: 700;
            text-align: center;
        }

        /* --- Form Styling --- */
        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.4rem;
        }

        /* --- Form Buttons --- */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #3ea2c7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e8aa8;
        }

        .btn-secondary {
            background-color: #f1f1f1;
            color: #555;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #e9e9e9;
            border-color: #ccc;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .main-container {
                margin: 2rem auto;
                padding: 1.5rem;
                max-width: 90%;
            }

            .main-container h2 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                margin: 1rem auto;
                padding: 1rem;
            }

            .main-container h2 {
                font-size: 1.5rem;
            }

            .form-actions {
                flex-direction: column-reverse;
                gap: 0.5rem;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="main-container">
        <h2>Tambah Kode Tindakan Terapi</h2>

        <form action="{{ route('dashboard.admin.kode-tindakan-terapi.store') }}" method="POST" class="form-layout">
            @csrf

            <!-- Kode -->
            <div class="form-group">
                <label for="kode">Kode</label>
                <input type="text" id="kode" name="kode" value="{{ old('kode') }}" class="form-control"
                    placeholder="Masukkan kode tindakan terapi">
                @error('kode')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsi_tindakan_terapi">Deskripsi Tindakan Terapi</label>
                <input type="text" id="deskripsi_tindakan_terapi" name="deskripsi_tindakan_terapi"
                    value="{{ old('deskripsi_tindakan_terapi') }}" class="form-control"
                    placeholder="Masukkan deskripsi tindakan terapi">
                @error('deskripsi_tindakan_terapi')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="idkategori">Kategori</label>
                <select id="idkategori" name="idkategori" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->idkategori }}"
                            {{ old('idkategori') == $kategori->idkategori ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('idkategori')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori Klinis -->
            <div class="form-group">
                <label for="idkategori_klinis">Kategori Klinis</label>
                <select id="idkategori_klinis" name="idkategori_klinis" class="form-control">
                    <option value="">-- Pilih Kategori Klinis --</option>
                    @foreach($kategoriKlinisList as $klinis)
                        <option value="{{ $klinis->idkategori_klinis }}"
                            {{ old('idkategori_klinis') == $klinis->idkategori_klinis ? 'selected' : '' }}>
                            {{ $klinis->nama_kategori_klinis }}
                        </option>
                    @endforeach
                </select>
                @error('idkategori_klinis')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="form-actions">
                <a href="{{ route('dashboard.admin.kode-tindakan-terapi.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>
