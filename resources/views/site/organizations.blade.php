<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grup WA anjayy</title>

    <link rel="stylesheet" href="style.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
        }

        /* ------ Kontainer Utama (Sudah Disesuaikan) ------ */
        .org-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 1rem;
            text-align: center;
        }

        .org-container h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
            color: #3ea2c7;
        }

        .org-container p.subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 4rem;
            /* Jarak besar sebelum struktur dimulai */
        }

        /* ------ Penataan Kartu Anggota (Kotak) ------ */
        .org-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 220px;
            /* Dikecilkan dari 250px */
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .org-card:hover {
            transform: translateY(-8px);
            /* Efek terangkat saat disentuh kursor */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* ------ [BARU] Gambar di dalam Kartu (Lingkaran & Fill) ------ */
        .card-img {
            width: 120px;
            /* Dikecilkan dari 150px */
            height: 120px;
            /* Dikecilkan dari 150px */
            border-radius: 50%;
            overflow: hidden;
            margin: 20px auto 15px auto;
            border: 4px solid #ffb726;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Memastikan gambar mengisi penuh lingkaran */
            object-position: center;
            /* Fokus pada bagian tengah gambar */
        }

        /* ------ Info Teks di dalam Kartu ------ */
        .card-info {
            padding: 0 1.2rem 1.2rem 1.2rem;
            /* Padding disesuaikan */
        }

        .card-info h3 {
            margin: 0 0 5px 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-info p {
            margin: 0;
            color: #777;
            font-size: 0.9rem;
        }

        /* ------ Penataan Hirarki (Ketua & Anggota) ------ */
        .leader-section,
        .members-section {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            /* Dikecilkan dari 2rem */
        }

        .members-section {
            margin-top: 1.5rem;
            flex-wrap: wrap;
            /* Agar kartu turun ke bawah jika layar tidak cukup */
        }

        /* Garis pemisah */
        hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 2rem auto;
            /* Dikecilkan dari 3rem */
            width: 50%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .nav-utama,
            .nav-kuning {
                flex-direction: column;
                height: auto;
                padding: 0.5rem 1rem;
                width: calc(100% - 2rem);
            }

            .nav-utama {
                top: 0;
            }

            .nav-kuning {
                top: auto;
            }

            .nav-utama .nav-right {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.8rem;
                margin-top: 0.5rem;
            }

            .nav-kuning ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.8rem;
                margin-top: 0.5rem;
            }

            body {
                padding-top: 180px;
            }

            .org-container {
                padding: 1rem;
            }

            .org-card {
                width: 100%;
                max-width: 280px;
                /* Dikecilkan dari 300px */
            }

            .leader-section,
            .members-section {
                flex-direction: column;
                align-items: center;
                gap: 1.5rem;
                /* Menyamakan gap dengan desktop */
            }
        }
    </style>
</head>

<body>
    <x-layout>
        <div class="org-container">
            <h2>Struktur Organisasi</h2>
            <p class="subtitle">Tim Profesional di Balik Pelayanan Terbaik Kami</p>

            <div class="leader-section">
                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/1200x/84/07/9f/84079f3c89a991f5f01684faee1a4c28.jpg"
                            alt="Foto Ketua Organisasi">
                    </div>
                    <div class="card-info">
                        <h3>Kim Dokja</h3>
                        <p>Ketua Organisasi</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="members-section">
                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/736x/32/08/78/3208783a4ed85dfce393bbc6a6098585.jpg"
                            alt="Foto Anggota">
                    </div>
                    <div class="card-info">
                        <h3>Yoo Joonghyuk</h3>
                        <p>Wakil Ketua</p>
                    </div>
                </div>

                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/736x/36/1d/13/361d13abd5f2583f165f6644cc0bb381.jpg"
                            alt="Foto Anggota">
                    </div>
                    <div class="card-info">
                        <h3>Mydeimos Kremnoas</h3>
                        <p>Sekretaris</p>
                    </div>
                </div>

                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/736x/27/fe/3f/27fe3ff3796aceb94d354f31f9b7ab92.jpg"
                            alt="Foto Anggota">
                    </div>
                    <div class="card-info">
                        <h3>Phainon Khaslana</h3>
                        <p>Bendahara</p>
                    </div>
                </div>

                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/736x/63/f2/3a/63f23a27cf851a2ac510dabb8ab5d580.jpg"
                            alt="Foto Anggota">
                    </div>
                    <div class="card-info">
                        <h3>Dan Heng</h3>
                        <p>Kepala Divisi Medis</p>
                    </div>
                </div>

                <div class="org-card">
                    <div class="card-img">
                        <img src="https://i.pinimg.com/736x/ca/d1/1f/cad11f7b6198471d86826f844119cdad.jpg"
                            alt="Foto Anggota">
                    </div>
                    <div class="card-info">
                        <h3>Kang Chana</h3>
                        <p>Kepala Divisi Laboratorium</p>
                    </div>
                </div>
            </div>
        </div>
    </x-layout>
</body>

</html>