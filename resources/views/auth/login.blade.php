<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RSHP</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url("https://i.pinimg.com/736x/9e/c0/09/9ec0091f0ddf52970d8240c50855ad9d.jpg");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .login-card {
            background-color: white;
            padding: 2rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 360px;
            text-align: center;
            opacity: 0;
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
            margin-bottom: 0.8rem;
        }

        .login-card h1 {
            margin-top: 0;
            margin-bottom: 0.4rem;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .login-card .subtitle {
            margin-bottom: 1.8rem;
            color: #888;
            font-size: 0.95rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.3rem;
            text-align: left;
        }

        .form-group .icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #3ea2c7;
            box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.3rem;
            font-size: 0.85rem;
        }

        .form-options .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .form-options a {
            color: #3ea2c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .form-options a:hover {
            color: #3388a8;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #3ea2c7;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-sizing: border-box;
        }

        .btn-login:hover {
            background-color: #3388a8;
            transform: translateY(-2px);
        }

        .signup-link {
            margin-top: 1.8rem;
            font-size: 0.85rem;
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

        .invalid-feedback {
            color: red;
            font-size: 0.8rem;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="{{ asset('images/images.png') }}" alt="Logo RSHP" class="logo">
        <h1>Selamat Datang Kembali</h1>
        <p class="subtitle">Silakan masuk untuk melanjutkan</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <i class="fas fa-envelope icon"></i>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <i class="fas fa-lock icon"></i>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="Password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <div class="checkbox-group">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                        {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat Saya</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Login</button>

            <div class="signup-link">
                Belum punya akun? <a href="#">Daftar sekarang</a>
            </div>
        </form>
    </div>
</body>
</html>
