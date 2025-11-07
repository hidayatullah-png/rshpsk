<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan RSHP UNAIR</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
        }

        /* --- New Service Section Styles --- */
        .services-section {
            max-width: 1200px;
            margin: 3rem auto;
            /* Atur jarak dari navbar dan elemen lain */
            padding: 1rem;
            text-align: center;
            border-radius: 12px;
        }

        .services-section h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
            color: #3ea2c7;
        }

        .services-section p.intro {
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 3rem auto;
            color: #555;
        }

        .service-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            /* 3 kolom, responsif */
            gap: 2.5rem;
            /* Jarak antar kategori */
            margin-top: 3rem;
        }

        .service-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            text-align: left;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .service-card .icon {
            font-size: 3.5rem;
            color: #ffb726;
            /* Warna ikon kuning */
            margin-bottom: 1.5rem;
            text-align: center;
            /* Pusatkan ikon */
            width: 100%;
        }

        .service-card h3 {
            font-size: 1.8rem;
            color: #3ea2c7;
            margin-bottom: 1rem;
            font-weight: 600;
            text-align: center;
            /* Pusatkan judul */
        }

        .service-card p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: #666;
            margin-bottom: 1.5rem;
            flex-grow: 1;
            /* Agar deskripsi mengisi ruang */
        }

        /* Accordion Styles */
        .accordion {
            border-top: 1px solid #eee;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }

        .accordion-item {
            margin-bottom: 0.8rem;
        }

        .accordion-header {
            background-color: #eaf6fc;
            /* Latar belakang header biru muda */
            padding: 1rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            color: #3ea2c7;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .accordion-header:hover {
            background-color: #d8edf8;
            /* Hover effect */
        }

        .accordion-header .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        .accordion-header.active .fa-chevron-down {
            transform: rotate(180deg);
            /* Panah berputar saat aktif */
        }

        .accordion-content {
            padding: 0 1.5rem;
            /* Start with 0 vertical padding */
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 5px 5px;
            background-color: #ffffff;
            max-height: 0;
            /* Sembunyikan konten secara default */
            overflow: hidden;
            /* Update transition to include padding */
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            font-size: 0.9rem;
            color: #555;
        }

        .accordion-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .accordion-content ul li {
            padding: 0.4rem 0;
            border-bottom: 1px dashed #eee;
        }

        .accordion-content ul li:last-child {
            border-bottom: none;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .services-section h1 {
                font-size: 2.5rem;
            }

            .service-categories {
                grid-template-columns: 1fr;
                /* Satu kolom di tablet/mobile */
            }

            .service-card {
                padding: 2rem;
            }

            .service-card .icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }

            .service-card h3 {
                font-size: 1.5rem;
            }

            .service-card p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .services-section {
                padding: 1.5rem;
                margin: 2rem auto;
            }

            .services-section h1 {
                font-size: 2rem;
            }

            .services-section p.intro {
                font-size: 0.95rem;
                margin-bottom: 2rem;
            }

            .accordion-header {
                font-size: 0.9rem;
                padding: 0.8rem 1rem;
            }

            .accordion-content {
                padding: 0.8rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <x-layout>
        <section class="services-section">
            <h2>Layanan Kami di RSHP UNAIR</h2>
            <p class="intro">
                Rumah Sakit Hewan Pendidikan Universitas Airlangga menyediakan beragam layanan kesehatan hewan terbaik,
                baik atas permintaan klien langsung maupun rujukan dari dokter hewan praktisi.
            </p>

            <div class="service-categories">

                <div class="service-card">
                    <div class="icon"><i class="fas fa-stethoscope"></i></div>
                    <h3>Poliklinik</h3>
                    <p>
                        Layanan rawat jalan untuk observasi, diagnosis, pengobatan, rehabilitasi medik,
                        serta surat keterangan sehat. Didukung pemeriksaan sitologi, dermatologi, hematologi,
                        radiologi, ultrasonografi, dan elektrokardiografi.
                        Kami juga bekerja sama dengan FKH UNAIR untuk pemeriksaan khusus dan dilengkapi rapid test untuk
                        diagnosa cepat penyakit berbahaya pada kucing dan anjing.
                    </p>
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">Layanan Poliklinik<i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <ul>
                                    <li>Rawat jalan</li>
                                    <li>Vaksinasi</li>
                                    <li>Akupuntur</li>
                                    <li>Kemoterapi</li>
                                    <li>Fisioterapi</li>
                                    <li>Mandi terapi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-hospital-alt"></i></div>
                    <h3>Rawat Inap</h3>
                    <p>
                        Perawatan intensif bagi pasien dengan kondisi berat atau parah di bawah pengawasan dokter dan
                        paramedis
                        handal.
                        Klien wajib mengisi inform consent setelah penjelasan detail kondisi pasien dan biaya layanan.
                        Menerima pembayaran tunai dan debit.
                    </p>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-cut"></i></div>
                    <h3>Bedah</h3>
                    <p>
                        Menyediakan tindakan bedah minor dan mayor dengan prosedur profesional.
                        Setiap tindakan dilakukan dengan standar keamanan tinggi untuk kesejahteraan hewan.
                    </p>
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">Tindakan Bedah Minor<i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <ul>
                                    <li>Jahit luka</li>
                                    <li>Kastrasi</li>
                                    <li>Othematoma</li>
                                    <li>Scaling – root planning</li>
                                    <li>Ekstraksi gigi</li>
                                </ul>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">Tindakan Bedah Mayor<i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <ul>
                                    <li>Gastrotomi; Entrotomi; Enterektomi; Salivary mucocele</li>
                                    <li>Ovariohisterektomi; Sectio caesar; Piometra</li>
                                    <li>Sistotomi; Urethrostomi</li>
                                    <li>Fraktur tulang</li>
                                    <li>Hernia diafragmatika</li>
                                    <li>Hernia perinealis</li>
                                    <li>Hernia inguinalis</li>
                                    <li>Eksisi tumor</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-microscope"></i></div>
                    <h3>Pemeriksaan Diagnostik</h3>
                    <p>
                        Berbagai jenis pemeriksaan untuk meneguhkan diagnosis, memastikan kondisi kesehatan hewan,
                        dan mendukung tindakan medis yang tepat.
                    </p>
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">Daftar Pemeriksaan<i class="fas fa-chevron-down"></i></div>
                            <div class="accordion-content">
                                <ul>
                                    <li>Pemeriksaan Sitologi</li>
                                    <li>Pemeriksaan Dermatologi</li>
                                    <li>Pemeriksaan Hematologi</li>
                                    <li>Pemeriksaan Radiografi</li>
                                    <li>Pemeriksaan Ultrasonografi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-shower"></i></div>
                    <h3>Grooming</h3>
                    <p>
                        Selain layanan medis, Rumah Sakit Hewan Pendidikan Universitas Airlangga juga melayani
                        perawatan kebersihan dan penampilan (grooming) untuk hewan kesayangan Anda,
                        menjaga mereka tetap bersih dan sehat.
                    </p>
                </div>

            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const accordionHeaders = document.querySelectorAll('.accordion-header');

                accordionHeaders.forEach(header => {
                    header.addEventListener('click', function () {
                        const accordionContent = this.nextElementSibling;
                        const isActive = this.classList.toggle('active');

                        if (isActive) {
                            accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
                        } else {
                            accordionContent.style.maxHeight = null;
                        }
                    });
                });
            });
        </script>
    </x-layout>
</body>

</html>