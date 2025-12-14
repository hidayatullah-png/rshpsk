@extends('layouts.admin.admin')

@section('title', 'Manajemen Hewan Peliharaan')

@section('content')
<div class="main-container">
    <h2>Manajemen Hewan Peliharaan (Pet)</h2>

    <div class="action-header">
        <div class="filter-buttons">
            @if(request('trash') == 1)
                <a href="{{ route('dashboard.admin.pet.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Aktif
                </a>
                <span class="badge badge-danger ml-2">
                    <i class="fas fa-trash"></i> Mode: Sampah
                </span>
            @else
                <a href="{{ route('dashboard.admin.pet.index', ['trash' => 1]) }}" class="btn btn-secondary">
                    <i class="fas fa-trash-restore"></i> Lihat Data Terhapus
                </a>
            @endif
        </div>

        @if(request('trash') != 1)
            <a href="{{ route('dashboard.admin.pet.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Pet Baru
            </a>
        @endif
    </div>

    @if ($pets->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Hewan</th>
                        <th>Jenis & Ras</th>
                        <th>Pemilik</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pets as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight: bold;">{{ $item->nama }}</td>
                            <td>
                                {{ $item->nama_jenis_hewan }} <br>
                                <small class="text-muted">({{ $item->nama_ras }})</small>
                            </td>
                            <td>{{ $item->nama_pemilik }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>
                            <td>
                                @if(request('trash') == 1)
                                    <span class="badge badge-danger">Terhapus</span>
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}</small>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    @if(request('trash') == 1)
                                        <a href="{{ route('dashboard.admin.pet.restore', $item->idpet) }}" 
                                           class="btn btn-info btn-sm"
                                           onclick="return confirm('Pulihkan data ini?')">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard.admin.pet.edit', $item->idpet) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('dashboard.admin.pet.destroy', $item->idpet) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-warning btn-sm"
                                                    onclick="return confirm('Pindahkan ke sampah?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-message">
            <p>Tidak ada data Pet <strong>{{ request('trash') == 1 ? 'di Sampah' : 'Aktif' }}</strong>.</p>
        </div>
    @endif
</div>

{{-- CSS Style --}}
<style>
    .action-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
    .badge { padding: 6px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; display: inline-block; }
    .badge-success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .badge-danger { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
    .badge-secondary { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
    .text-muted { color: #6c757d; font-size: 0.8rem; }
    .ml-2 { margin-left: 0.5rem; }
    .empty-message { text-align: center; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: #777; }
    @media (max-width: 768px) { .action-header { flex-direction: column; align-items: stretch; } .action-header .btn { width: 100%; margin-bottom: 5px; } }
</style>
@endsection