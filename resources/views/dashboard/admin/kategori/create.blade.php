<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            color: #333;
            padding-top: 110px;
        }

        /* --- Main Content Container (Diadaptasi dari referensi) --- */
        .main-container {
            max-width: 600px;
            /* Lebar lebih sempit, cocok untuk form */
            margin: 3rem auto;
            padding: 2.5rem;
            /* Padding lebih besar untuk form */
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Judul (Diambil dari referensi) */
        .main-container h2 {
            font-size: 2rem;
            color: #3ea2c7;
            margin-top: 0;
            margin-bottom: 2rem;
            font-weight: 700;
            text-align: center;
            /* Judul form di tengah */
        }

        /* --- Form Styling (Baru) --- */
        .form-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            /* Jarak antar form group */
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
            /* Penting untuk padding agar pas */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        /* Pesan Error (Baru) */
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.4rem;
        }

        /* Tombol Aksi Form (Baru) */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            /* Tombol di kanan */
            gap: 0.75rem;
            margin-top: 1rem;
        }

        /* --- Button Styling (Diadaptasi dari referensi) --- */
        .btn {
            padding: 10px 18px;
            /* Sedikit lebih besar untuk form */
            border: none;
            border-radius: 8px;
            /* Lebih bulat agar konsisten */
            cursor: pointer;
            font-weight: 600;
            /* Lebih tebal */
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

        /* Style tombol sekunder baru untuk "Kembali" */
        .btn-secondary {
            background-color: #f1f1f1;
            color: #555;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #e9e9e9;
            border-color: #ccc;
        }

        /* --- Responsive Adjustments --- */
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

            /* Tombol full-width di mobile untuk aksesibilitas */
            .form-actions {
                flex-direction: column-reverse;
                /* Tombol utama (Simpan) di atas */
                gap: 0.5rem;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
                box-sizing: border-box;
            }
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="main-container">
        <h2>Tambah Kategori</h2>

        <form action="{{ route('dashboard.admin.kategori.store') }}" method="POST" class="form-layout">
            @csrf

            <!-- Form Group untuk Nama Kategori -->
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>

                <input type="text" id="nama_kategori" name="nama_kategori"
                    class="form-control @if($errors->has('nama_kategori')) is-invalid @endif"
                    value="{{ old('nama_kategori') }}" required autofocus>

                <!-- Menampilkan pesan error validasi -->
                @if ($errors->has('nama_kategori'))
                    <span class="error-message">
                        {{ $errors->first('nama_kategori') }}
                    </span>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.admin.kategori.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</body>

</html>
