@extends('layouts.admin')

@section('title', 'Manajemen Jenis Hewan')

@section('content')
    <div class="main-container">
        <h2>Manajemen Jenis Hewan</h2>

        <div class="action-header">
            <a href="{{ route('dashboard.admin.jenis-hewan.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Jenis Hewan
            </a>
        </div>

        @if ($list->isNotEmpty())
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Jenis Hewan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $jenis)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ htmlspecialchars($jenis->nama_jenis_hewan) }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('dashboard.admin.jenis-hewan.edit', $jenis->idjenis_hewan) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form
                                        action="{{ route('dashboard.admin.jenis-hewan.destroy', ['jenis_hewan' => $jenis->idjenis_hewan]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus jenis hewan ini ({{ $jenis->nama_jenis_hewan }})?')">
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
            <p class="empty-message">Tidak ada data jenis hewan yang tersedia. Silakan tambahkan yang pertama!</p>

            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.jenis-hewan.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Jenis Hewan Pertama
                </a>
            </div>
        @endif
    </div>
@endsection