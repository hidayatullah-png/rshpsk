<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Ras Hewan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            justify-content: flex-end;
            margin-bottom: 1.2rem;
        }

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
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-buttons form {
            margin: 0;
            padding: 0;
        }

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

        @media (max-width: 768px) {
            .main-container {
                margin: 2rem auto;
                padding: 1rem;
                max-width: 95%;
            }

            .data-table th,
            .data-table td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.3rem;
            }
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="main-container">
        <h2>Manajemen Ras Hewan</h2>

        <div class="action-header">
            <a href="{{ route('dashboard.admin.ras-hewan.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Ras Hewan
            </a>
        </div>

        @if ($rasList->isNotEmpty())
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Ras</th>
                            <th>Jenis Hewan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rasList as $ras)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ras->nama_ras }}</td>
                                <td>{{ $ras->nama_jenis_hewan }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('dashboard.admin.ras-hewan.edit', $ras->idras_hewan) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('dashboard.admin.ras-hewan.destroy', $ras->idras_hewan) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus ras {{ $ras->nama_ras }}?')">
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
            <p class="empty-message">Tidak ada data ras hewan yang tersedia. Silakan tambahkan yang pertama!</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.ras-hewan.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Ras Hewan Pertama
                </a>
            </div>
        @endif
    </div>
</body>
</html>
