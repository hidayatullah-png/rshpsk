<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Rekam Medis - RSHP UNAIR</title>
    <!-- Import Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Import Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            margin: 0;
            background-color: #f4f7f6;
            color: #333;
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

        h1 {
            font-size: 2.2rem; /* Style dari h2 referensi */
            color: #3ea2c7;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 700; /* Style dari h2 referensi */
        }

        .action-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.2rem;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px; /* Radius dari referensi */
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Transisi dari referensi */
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem; /* Gap dari referensi */
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px); /* Efek hover dari referensi */
        }

        .btn-primary {
            background-color: #3ea2c7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e8aa8;
        }

        .btn-warning {
            background-color: #f0ad4e;
            color: white;
        }

        .btn-warning:hover {
            background-color: #ec971f;
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
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
            overflow: hidden; /* Untuk border-radius di tabel */
        }

        .data-table {
            width: 100%;
            border-collapse: separate; /* Dari referensi */
            border-spacing: 0;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .data-table th {
            background-color: #3ea2c7;
            color: white;
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 600; /* Dari referensi */
        }

        /* Styling kolom pertama (No) */
        .data-table th:first-child,
        .data-table td:first-child {
            text-align: center;
            width: 50px;
        }

        /* Styling kolom terakhir (Aksi) */
        .data-table th:last-child,
        .data-table td.action-buttons {
            text-align: center;
            width: 200px; /* Beri ruang lebih untuk 2 tombol */
        }

        .data-table th:first-child {
            border-top-left-radius: 8px; /* Sudut bulat dari referensi */
        }

        .data-table th:last-child {
            border-top-right-radius: 8px; /* Sudut bulat dari referensi */
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: nowrap; /* Mencegah tombol turun baris */
        }

        .action-buttons form {
            margin: 0;
            padding: 0;
            display: inline-block;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping dari referensi */
        }

        .data-table tbody tr:hover {
            background-color: #eaf6fc; /* Efek hover dari referensi */
        }

        .data-table td.empty-row-cell {
            text-align: center;
            padding: 2rem;
            color: #777;
            font-size: 1.1rem;
            background-color: #fdfdfd;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px; /* Samakan dengan container */
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .total-count {
            margin-top: 1rem;
            color: #555;
            font-size: 0.9rem;
            text-align: right;
            padding-right: 5px;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1rem;
            }

            .data-table th,
            .data-table td {
                padding: 8px 10px;
            }
            
            .action-buttons {
                flex-direction: column; /* Tombol aksi jadi vertikal di mobile */
                gap: 0.3rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="main-container">
        <h1>Data Rekam Medis</h1>

        {{-- ✅ Flash Messages --}}
        @foreach(['success' => 'alert-success', 'danger' => 'alert-danger'] as $type => $class)
            @if(session($type))
                <div class="alert {{ $class }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        {{-- 🔹 Tombol Tambah --}}
        <div class="action-header">
            <a href="{{ route('dashboard.admin.rekam-medis.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Rekam Medis
            </a>
        </div>

        {{-- 🔹 Tabel Data --}}
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Hewan</th>
                        <th>Pemilik</th>
                        <th>Dokter Pemeriksa</th>
                        <th>Diagnosa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                            <td>{{ $item->pet->nama ?? '-' }}</td>
                            <td>{{ $item->pet->pemilik->user->nama ?? '-' }}</td>
                            <td>{{ $item->dokter->nama ?? 'N/A' }}</td>
                            <td>{{ Str::limit($item->diagnosa, 50, '...') ?? '-' }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('dashboard.admin.rekam-medis.edit', $item->idrekam_medis) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>

                                <form action="{{ route('dashboard.admin.rekam-medis.destroy', $item->idrekam_medis) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus rekam medis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row-cell">
                                Belum ada data rekam medis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="total-count">
            Total: <strong>{{ $data->count() }}</strong> rekam medis.
        </div>
    </div>
</body>

</html>