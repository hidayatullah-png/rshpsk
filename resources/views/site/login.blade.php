<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RSHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - RSHP</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

        <style>

            /* ------ Pengaturan Dasar Halaman ------ */
            body {
                margin: 0;
                font-family: 'Poppins', sans-serif;
                background-image: url("https://i.pinimg.com/736x/9e/c0/09/9ec0091f0ddf52970d8240c50855ad9d.jpg");
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat
                background-attachment: fixed;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }

            /* ------ Kartu Login Utama (Disesuaikan) ------ */
            .login-card {
                background-color: white;
                padding: 2rem 2.5rem;
                /* Mengurangi padding untuk membuat kartu lebih kecil */
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                width: 100%;
                max-width: 360px;
                /* Mengurangi lebar maksimum kartu */
                text-align: center;
                opacity: 0;
                /* Mulai dengan opacity 0 untuk animasi fadeIn */
                transform: scale(0.95);
                animation: fadeIn 0.5s ease-out forwards;
            }

            @keyframes fadeIn {
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .login-card .logo {
                width: 70px;
                /* Sedikit dikecilkan */
                margin-bottom: 0.8rem;
            }

            .login-card h1 {
                margin-top: 0;
                margin-bottom: 0.4rem;
                /* Disesuaikan */
                font-size: 1.6rem;
                /* Sedikit dikecilkan */
                font-weight: 700;
            }

            .login-card .subtitle {
                margin-bottom: 1.8rem;
                /* Disesuaikan */
                color: #888;
                font-size: 0.95rem;
                /* Sedikit dikecilkan */
            }

            /* ------ Penataan Form ------ */
            .form-group {
                position: relative;
                margin-bottom: 1.3rem;
                /* Sedikit dikurangi */
                text-align: left;
            }

            .form-group .icon {
                position: absolute;
                left: 12px;
                /* Disesuaikan */
                top: 50%;
                transform: translateY(-50%);
                color: #aaa;
                font-size: 0.9rem;
                /* Sedikit dikecilkan */
            }

            .form-control {
                width: 100%;
                padding: 10px 12px 10px 40px;
                /* Padding disesuaikan, padding kiri untuk ikon */
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 0.95rem;
                /* Sedikit dikecilkan */
                font-family: 'Poppins', sans-serif;
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
                box-sizing: border-box;
                /* PERBAIKAN PENTING: padding & border dihitung di dalam lebar */
            }

            .form-control:focus {
                outline: none;
                border-color: #3ea2c7;
                box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
            }

            /* ------ Opsi Tambahan (Ingat Saya & Lupa Password) ------ */
            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.3rem;
                /* Sedikit dikurangi */
                font-size: 0.85rem;
                /* Sedikit dikecilkan */
            }

            .form-options .checkbox-group {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                /* Sedikit dikurangi */
            }

            .form-options a {
                color: #3ea2c7;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .form-options a:hover {
                color: #3388a8;
            }

            /* ------ Tombol Login ------ */
            .btn-login {
                width: 100%;
                padding: 12px;
                /* Sedikit dikurangi */
                background-color: #3ea2c7;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                /* Sedikit dikecilkan */
                font-weight: 500;
                font-family: 'Poppins', sans-serif;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease;
                box-sizing: border-box;
                /* Penting juga untuk tombol */
            }

            .btn-login:hover {
                background-color: #3388a8;
                transform: translateY(-2px);
            }

            /* ------ Link Daftar ------ */
            .signup-link {
                margin-top: 1.8rem;
                /* Sedikit dikurangi */
                font-size: 0.85rem;
                /* Sedikit dikecilkan */
                color: #666;
            }

            .signup-link a {
                color: #ffb726;
                font-weight: 500;
                text-decoration: none;
            }

            .signup-link a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

<body>

    <div class="login-card">
        <img src="{{ asset('images/images.png') }}" alt="Logo RSHP" class="logo">
        <h1>Selamat Datang Kembali</h1>
        <p class="subtitle">Silakan masuk untuk melanjutkan</p>

        <form action="#" method="POST">
            @csrf

            <div class="form-group">
                <i class="fas fa-envelope icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="form-group">
                <i class="fas fa-lock icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="form-options">
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat Saya</label>
                </div>
                <a href="#">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-login">Login</button>

        </form>

        <div class="signup-link">
            Belum punya akun? <a href="#">Daftar sekarang</a>
        </div>
    </div>

</body>

</html>