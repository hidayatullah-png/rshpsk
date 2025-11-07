<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>visi misi missisipi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
        }

        .vm-container {
            max-width: 1280px;
            margin: 100px auto 0;
            padding: 0 2rem;
            background-color: #ffffff;
            /* Ubah dari gelap ke putih */
            color: #333;
            /* Ubah dari terang ke gelap */
            min-height: calc(100vh - 90px);
            padding-bottom: 5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            /* Tambahkan bayangan ringan untuk kontainer */
            border-radius: 8px;
            /* Sudut sedikit melengkung */
        }

        .vm-header {
            text-align: center;
            padding-top: 4rem;
            padding-bottom: 1rem;
            color: #333;
            /* Ubah dari putih ke gelap */
        }

        .vm-header h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
            color: #3ea2c7;
            /* Bisa tetap biru tema */
        }

        .vm-header p {
            font-size: 1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
        }

        .vm-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            padding: 4rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            /* Ubah garis pemisah menjadi lebih gelap */
        }

        .vm-section:last-child {
            border-bottom: none;
        }

        .vm-content {
            text-align: left;
            position: relative;
            /* Ini sudah ada, pastikan */
            padding-top: 20px;
            /* Tambahkan padding-top agar inner-content bisa lebih ke atas relatif ke span number */
        }

        .vm-content span.section-number {
            font-size: 6rem;
            color: rgba(0, 0, 0, 0.05);
            font-weight: 700;
            position: absolute;
            left: -30px;
            top: -20px;
            /* POSISI ANGKA: Disesuaikan jika perlu, mungkin perlu diatur ulang bersama inner-content */
            line-height: 1;
            z-index: 1;
        }

        .vm-content .inner-content {
            position: relative;
            z-index: 2;
            margin-top: -30px;
            /* PERBAIKAN 2: Geser konten ke atas untuk mengurangi jarak dengan angka */
        }

        .vm-content h3 {
            font-size: 2.2rem;
            font-weight: 600;
            color: #333;
            margin-top: 0;
            /* Pastikan tidak ada margin atas yang terlalu besar */
            margin-bottom: 0.5rem;
            /* DIKECILKAN: Jarak antara "Visi" dan paragraf di bawahnya */
        }

        .vm-content p,
        .vm-content ol {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #555;
            margin-top: 0;
            /* Pastikan paragraf dimulai tanpa margin atas besar */
            margin-bottom: 1.5rem;
        }

        .vm-content ol {
            padding-left: 1.5rem;
            margin-top: 0;
        }

        .vm-content ol li {
            margin-bottom: 0.8rem;
        }

        .vm-image {
            position: relative;
            height: 400px;
            background-color: #eee;
            /* Warna fallback lebih terang */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* Bayangan lebih ringan */
        }

        .vm-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* --- Pengaturan Responsive --- */
        @media (max-width: 900px) {
            .vm-section {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 3rem 0;
            }

            .vm-section:nth-child(odd) .vm-image {
                order: 2;
            }

            .vm-section:nth-child(odd) .vm-content {
                order: 1;
            }

            .vm-content h3 {
                font-size: 1.8rem;
            }

            .vm-content p,
            .vm-content ol {
                font-size: 1rem;
            }

            .vm-content span.section-number {
                font-size: 4rem;
                left: -15px;
                top: -10px;
            }
        }

        @media (max-width: 600px) {
            .vm-header h2 {
                font-size: 1rem;
                padding: 1rem;
            }

            .vm-header p {
                font-size: 1rem;
            }

            .vm-container {
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>
    <x-layout>
        <div class="vm-container">
            <div class="vm-header">
                <h2>Visi, Misi, & Tujuan</h2>
                <p style="font-size: 1rem; text-transform: uppercase; letter-spacing: 2px; color: #666;">Tentang Kami
                </p>

            </div>

            <div class="vm-section">
                <div class="vm-content">
                    <div class="inner-content">
                        <span class="section-number">01</span>
                        <h3>Visi</h3>
                        <p>
                            "Menjadi rumah sakit hewan pendidikan yang unggul di tingkat nasional dan internasional,
                            berlandaskan moral agama, etika, dan kesejahteraan hewan."
                        </p>
                    </div>
                </div>
                <div class="vm-image">
                    <img src="https://i.pinimg.com/1200x/14/20/49/142049035edb271e97bf97c62791aeb4.jpg" alt="Visi">
                </div>
            </div>

            <div class="vm-section">
                <div class="vm-image">
                    <img src="https://i.pinimg.com/736x/b1/c4/1d/b1c41dad47acbb32364e06a8a85c6e17.jpg" alt="Misi">
                </div>
                <div class="vm-content">
                    <div class="inner-content">
                        <span class="section-number">02</span>
                        <h3>Misi</h3>
                        <ol>
                            <li>Menyelenggarakan pelayanan kesehatan hewan yang profesional dan berkualitas.</li>
                            <li>Menyelenggarakan pendidikan dan pelatihan bagi calon dokter hewan dan tenaga profesional
                                lainnya.</li>
                            <li>Mengembangkan penelitian di bidang kedokteran hewan yang inovatif dan bermanfaat bagi
                                masyarakat.</li>
                            <li>Melaksanakan pengabdian kepada masyarakat untuk meningkatkan kesehatan dan kesejahteraan
                                hewan.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="vm-section">
                <div class="vm-content">
                    <div class="inner-content">
                        <span class="section-number">03</span>
                        <h3>Tujuan</h3>
                        <ol>
                            <li>Terwujudnya pelayanan kesehatan hewan yang prima dan menjadi rujukan.</li>
                            <li>Dihasilkannya lulusan dokter hewan yang kompeten dan berdaya saing global.</li>
                            <li>Berkembangnya ilmu pengetahuan dan teknologi kedokteran hewan melalui penelitian.</li>
                            <li>Meningkatnya kesadaran masyarakat akan pentingnya kesehatan dan kesejahteraan hewan.
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="vm-image">
                    <img src="https://i.pinimg.com/1200x/7d/62/44/7d62443bab5150755a69fbf542d8e9a3.jpg" alt="Tujuan">
                </div>
            </div>
        </div>
    </x-layout>
</body>

</html>