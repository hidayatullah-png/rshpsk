@extends('layouts.admin.admin')

@section('title', 'Manajemen Kategori Klinis')

@section('content')
<div class="main-container">
    <h2>Manajemen Kategori Klinis</h2>

    {{-- Tombol Tambah di Pojok Kanan --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.kategori-klinis.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    {{-- Logic Table --}}
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
                    @foreach ($kategoriKlinis as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->nama_kategori_klinis }}</td>
                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('dashboard.admin.kategori-klinis.edit', $kategori->idkategori_klinis) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('dashboard.admin.kategori-klinis.destroy', $kategori->idkategori_klinis) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini ({{ $kategori->nama_kategori_klinis }})?')">
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
        {{-- Empty State --}}
        <div class="empty-state">
            <p class="empty-message">Tidak ada data kategori klinis yang tersedia.</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.kategori-klinis.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Data Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection