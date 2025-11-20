<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
            margin: 0;
            padding-top: 110px;
        }

        .main-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #3ea2c7;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-weight: 600;
            color: #444;
            text-align: left;
            margin-bottom: 0.3rem;
        }

        input[type="text"],
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
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
        <h2>Tambah Pet Baru</h2>

        <form action="{{ route('dashboard.admin.pet.store') }}" method="POST">
            @csrf

            <div>
                <label for="nama">Nama Pet</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama hewan">
            </div>

            <div>
                <label for="idras">Ras Hewan</label>
                <select name="idras" id="idras" class="form-control">
                    <option value="">-- Pilih Ras --</option>
                    @foreach ($rasList as $ras)
                        <option value="{{ $ras->idras }}">
                            {{ $ras->nama_ras }} ({{ $ras->jenis->nama_jenis ?? 'Tanpa Jenis' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="idpemilik">Pemilik</label>
                <select name="idpemilik" id="idpemilik" class="form-control">
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach ($pemilikList as $p)
                        <option value="{{ $p->idpemilik }}">
                            {{ $p->user->nama ?? 'Tanpa Nama' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('dashboard.admin.pet.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

</body>

</html>