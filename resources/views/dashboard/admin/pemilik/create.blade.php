<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru</title>
    <!-- Tambahkan Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- STYLE DARI ANDA (Sama seperti create pemilik) --- */
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
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

        .action-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.2rem;
        }

        /* --- Button Styling --- */
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

        /* --- Alert Styling --- */
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
        
        /* --- Responsive Adjustments --- */
        @media (max-width: 768px) {
            body {
                padding-top: 100px;
            }
            .main-container {
                margin: 2rem auto;
                padding: 1rem;
                max-width: 95%;
            }
            .main-container h2 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
             body {
                padding-top: 80px;
            }
            .main-container {
                margin: 1rem auto;
                padding: 0.8rem;
            }
            .main-container h2 {
                font-size: 1.5rem;
            }
        }
        
        /* --- STYLE BARU UNTUK FORM (Sama seperti create pemilik) --- */
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
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.8rem;
            margin-top: 2rem;
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
        
        .form-control.is-invalid:focus {
             box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
        }

    </style>
</head>
<body>
    <x-navbar/>
    <div class="main-container">
        <h2>Tambah User Baru</h2>
        
        <!-- Menampilkan pesan error global (jika ada) -->
        @if (session('danger'))
            <div class="alert alert-danger">
                {{ session('danger') }}
            </div>
        @endif

        <!-- Menampilkan semua error validasi -->
        @if ($errors->any())
            <div class="alert alert-danger" style="text-align: left;">
                <strong>Oops! Terjadi kesalahan:</strong>
                <ul style="margin-top: 0.5rem; margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            {{-- Mengarah ke route 'store' dari UserController --}}
            <form action="{{ route('dashboard.admin.user.store') }}" method="POST">
                @csrf
                
                <!-- 1. Nama Lengkap -->
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" 
                           placeholder="Masukkan nama lengkap user"
                           required>
                           
                    @error('nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 2. Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="Contoh: user@gmail.com"
                           required>
                           
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 3. Password -->
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

                <!-- Tombol Aksi -->
                <div class="form-actions">
                    {{-- Redirect kembali ke index role-user, sesuai controller --}}
                    <a href="{{ route('dashboard.admin.role-user.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan User
                    </button>
                </div>

            </form>
        </div>
    </div>

</body>
</html>