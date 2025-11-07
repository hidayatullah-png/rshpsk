<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSHP - Selamat Datang</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
        }

        .hero-section {
            height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-top: 30px;

            background-image: url('https://i.pinimg.com/1200x/b2/73/a0/b273a07ace1d814142a1e954da738e27.jpg');
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            /* Pertahankan efek parallax */
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 4;
            max-width: 800px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .hero-content .description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .hero-content .btn {
            display: inline-block;
            padding: 12px 28px;
            font-weight: bold;
            border-radius: 50px;
            text-decoration: none;
            background-color: #42b6e9;
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .hero-content .btn:hover {
            background-color: #3aa8d8;
            transform: translateY(-3px);
        }

        .map-video-section {
            max-width: 1200px;
            /* Lebar maksimum untuk konten ini */
            margin: 5rem auto;
            /* Jarak atas/bawah dan tengah */
            padding: 0 2rem;
            text-align: center;
            /* Untuk menengahkan judul "Fasilitas" */
        }

        .map-video-section h2 {
            font-size: 2.5rem;
            color: #3ea2c7;
            margin-bottom: 2rem;
            /* Jarak lebih besar dari konten di bawahnya */
            font-weight: 600;
        }

        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            /* Agar elemen bisa turun baris di layar kecil */
            gap: 2rem;
            /* Jarak antara map dan video */
            justify-content: center;
            /* Pusatkan jika ada ruang kosong */
            align-items: flex-start;
            /* Sejajarkan bagian atas */
        }

        .map-container,
        .video-wrapper {
            flex: 1;
            /* Agar keduanya berbagi ruang secara merata */
            min-width: 300px;
            /* Lebar minimum sebelum turun baris */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* Penting untuk iframe agar sudutnya ikut melengkung */
        }

        .map-container {
            height: 340px;
            /* Tinggi spesifik untuk map */
            width: auto;
            position: relative;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 28.125%;
            height: 0;
            background-color: #f0f0f0;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .content-wrapper {
                flex-direction: column;
                /* Map dan Video akan bertumpuk */
                align-items: center;
            }

            .map-container,
            .video-wrapper {
                width: 100%;
                /* Memastikan keduanya mengambil lebar penuh */
                max-width: 600px;
                /* Batasi lebar di perangkat yang lebih kecil */
            }

            .map-container {
                height: 300px;
                /* Kurangi tinggi map di mobile */
            }

            .video-wrapper {
                padding-bottom: 56.25%;
                /* Video akan tampil full-width 16:9 di mobile */
            }

            .map-video-section h2 {
                font-size: 2rem;
            }

            .map-video-section {
                margin: 3rem auto;
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>
    <x-layout>
        <section id="home" class="hero-section"
            style="background-image: url('https://i.pinimg.com/1200x/b2/73/a0/b273a07ace1d814142a1e954da738e27.jpg');">
            <div class="hero-content">
                <h1>Pelayanan Terbaik untuk Sahabat Terbaik Anda</h1>
                <p class="description">
                    Berkomitmen memberikan layanan kesehatan hewan yang unggul dan profesional. Daftarkan hewan
                    kesayangan
                    Anda secara online dengan mudah dan cepat.
                </p>
                <a href="https://rshp.unair.ac.id/dokter-jaga/" class="btn">Lihat Jadwal Dokter Jaga</a>
            </div>
        </section>

        <section class="map-video-section">
            <h2>Lihat Fasilitas Kami</h2>
            <div class="content-wrapper">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.519391000673!2d112.72373321477467!3d-7.291771194723145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb3a1e2f9b87%3A0xa141c22668e1e7f!2sRumah%20Sakit%20Hewan%20Pendidikan%20Universitas%20Airlangga!5e0!3m2!1sid!2sid!4v1678888888888!5m2!1sid!2sid"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/rCfvZPECZvE?controls=1&modestbranding=1&rel=0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </section>
    </x-layout>
</body>

</html>