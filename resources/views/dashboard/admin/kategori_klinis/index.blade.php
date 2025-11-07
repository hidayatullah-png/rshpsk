<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori Klinis</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            padding-top: 110px;
        }

        .main-container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .main-container h2 {
            font-size: 2.2rem;
            color: #3ea2c7;
            margin: 0.5rem auto;
            padding: 0.5rem;
            font-weight: 700;
        }

        .action-header {
            display: flex;
            /* Gunakan flexbox untuk alignment yang lebih baik */
            justify-content: flex-end;
            /* Pindahkan ke kanan */
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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1rem;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #3ea2c7;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .data-table th:first-child,
        .data-table td:first-child {
            text-align: center;
            width: 50px;
        }

        .data-table th:last-child,
        .data-table td.action-buttons {
            text-align: center;
            width: 180px;
            /* Beri lebar tetap untuk kolom Aksi agar tombol muat */
        }

        /* Penataan Tombol di dalam sel Aksi */
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            /* Jarak antar tombol */
            flex-wrap: wrap;
            /* Agar bisa wrap di layar kecil */
        }

        /* Atur margin form delete agar tidak ada margin ekstra */
        .action-buttons form {
            margin: 0;
            padding: 0;
        }


        /* Sudut melengkung untuk header tabel */
        .data-table th:first-child {
            border-top-left-radius: 8px;
        }

        .data-table th:last-child {
            border-top-right-radius: 8px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tbody tr:hover {
            background-color: #eaf6fc;
        }

        .data-table td {
            font-size: 0.95rem;
            color: #555;
        }

        /* --- Empty State Message --- */
        .empty-message {
            font-size: 1.1rem;
            color: #777;
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: #ffe0b2;
            border: 1px solid #ffb726;
            border-radius: 8px;
        }

        .empty-state-actions {
            margin-top: 1.5rem;
        }


        /* --- Responsive Adjustments --- */
        @media (max-width: 768px) {
            body {
                padding-top: 150px;

            }

            .main-container {
                margin: 2rem auto;
                padding: 1rem;
                max-width: 95%;
            }

            .main-container h2 {
                font-size: 1.8rem;
            }

            .action-header {
                justify-content: center;
            }

            .data-table {
                min-width: 500px;
            }

            .data-table th,
            .data-table td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.3rem;
            }

            .action-buttons .btn {
                width: 100%;
                box-sizing: border-box;
            }

            .data-table th:last-child,
            .data-table td.action-buttons {
                width: auto;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                margin: 1rem auto;
                padding: 0.8rem;
            }
            .main-container h2 {
                font-size: 1.5rem;
            }
            .data-table {
                min-width: 400px;
            }
            .btn {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }
    </style>
</head>

<body>
    <x-layout>

        <div class="main-container">
            <h2>Manajemen Kategori Klinis</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('danger'))
                <div class="alert alert-danger">
                    {{ session('danger') }}
                </div>
            @endif

            <div class="action-header">
                <a href="{{ route('admin.kategori_klinis.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori
                </a>
            </div>

            @if ($kategoriKlinis->isNotEmpty())
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Kategori Klinis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($KategoriKlinis as $KategoriKlinis)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $KategoriKlinis->nama_kategori_klinis }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.kategori_klinis.edit', $KategoriKlinis->idkategori_klinis) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.kategori_klinis.destroy', $KategoriKlinis->idkategori_klinis) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini ({{ $KategoriKlinis->nama_kategori_klinis }})?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty-message">Tidak ada data kategori klinis yang tersedia. Silakan tambahkan yang pertama!</p>
                <div class="empty-state-actions">
                    <a href="{{ route('admin.kategori_klinis.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Tambah Kategori Pertama
                    </a>
                </div>
            @endif

        </div>
    </x-layout>
</body>

</html>
