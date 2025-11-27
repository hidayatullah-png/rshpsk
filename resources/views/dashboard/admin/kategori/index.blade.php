@extends('layouts.admin.admin')

@section('content')
<div class="main-container">
    <h2>Manajemen Kategori</h2>

    {{-- Tombol Tambah --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.kategori.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    {{-- 
        NOTE: Flash messages section removed here.
        They are now handled globally in layouts/admin.blade.php 
    --}}

    @if($kategori->isNotEmpty())
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('dashboard.admin.kategori.edit', $item->idkategori) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('dashboard.admin.kategori.destroy', $item->idkategori) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah yakin ingin menghapus kategori {{ $item->nama_kategori }}?')">
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
    <p class="empty-message">Tidak ada data kategori.</p>
    <div class="empty-state-actions">
        <a href="{{ route('dashboard.admin.kategori.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Kategori
        </a>
    </div>
    @endif
</div>
@endsection