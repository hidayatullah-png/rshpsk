<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWAS ADMIN</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            background-color: white;
        }

        /* Style untuk header box dan elemen tambahan */
        .header {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem;
            color: black;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .header h2 {
            margin: 0 0 0.5rem 0;
        }

        .header p {
            margin: 0;
        }

        .badge {
            display: inline-block;
            background: #3ea2c7;
            color: white;
            font-weight: bold;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            margin-left: 0.3rem;
        }

        /* Divider hitam */
        .divider {
            width: 2px;
            background-color: #000000ff;
            height: 40px;
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="header">
        <h2>Selamat datang di halaman khusus admin</h2>
        @php
            $nama_user = session('user_name', 'User');
            $nama_role = session('user_role_name', 'Tidak diketahui');
        @endphp

        <p>
            Halo <b>{{ $nama_user }}</b>, anda login sebagai
            <span class="badge">{{ $nama_role }}</span>
        </p>
    </div>

</body>

</html>