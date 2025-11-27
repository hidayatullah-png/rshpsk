@extends('layouts.admin.admin')

@section('title', 'Manajemen Role Pengguna')

@section('content')
<div class="main-container">
    <h2>Manajemen Role Pengguna</h2>

    {{-- Tombol Tambah --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.role.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Role
        </a>
    </div>

    {{-- Tabel Data --}}
    {{-- Menggunakan variabel $role sesuai codingan controller kamu --}}
    @if ($role->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 
                        Saya ubah variabel loop jadi $item agar tidak bentrok 
                        dengan nama koleksi $role (Best Practice) 
                    --}}
                    @foreach ($role as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_role }}</td>
                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('dashboard.admin.role.edit', $item->idrole) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('dashboard.admin.role.destroy', $item->idrole) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus role \'{{ $item->nama_role }}\'?')">
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
        <div class="empty-message">
            <p>Tidak ada data role yang tersedia.</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.role.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Role Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection