<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Hewan</title>

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
            max-width: 600px;
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
    </style>
</head>

<body>

    {{-- Navbar --}}
    <x-navbar />

    <div class="main-container">
        <h2>Edit Jenis Hewan</h2>

        {{-- Pastikan file ini dijalankan melalui Blade --}}
        <form action="{{ route('dashboard.admin.jenis-hewan.update', $jenis->idjenis_hewan) }}" method="POST"
            class="form-layout">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_jenis_hewan">Nama Jenis Hewan</label>
                <input type="text" id="nama_jenis_hewan" name="nama_jenis_hewan"
                    class="form-control @error('nama_jenis_hewan') is-invalid @enderror"
                    value="{{ old('nama_jenis_hewan', $jenis->nama_jenis_hewan) }}" required autofocus>

                @error('nama_jenis_hewan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.admin.jenis-hewan.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>

</html>
