@extends('layouts.admin.admin')

@section('title', 'Manajemen Pet')

@section('content')

<div class="main-container">
    <h2>Manajemen Pet</h2>

    {{-- Tombol Tambah (Menggunakan class .action-header dari Layout) --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.pet.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Pet
        </a>
    </div>

    {{-- Tabel Data --}}
    @if ($pets->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pet</th>
                        <th>Ras</th>
                        <th>Jenis</th>
                        <th>Pemilik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pets as $pet)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pet->nama }}</td>
                            
                            {{-- Gunakan ?? '-' untuk menangani data kosong --}}
                            <td>{{ $pet->ras->nama_ras ?? '-' }}</td>
                            <td>{{ $pet->ras->jenis->nama_jenis_hewan ?? '-' }}</td>
                            <td>{{ $pet->pemilik->user->nama ?? '-' }}</td>
                            
                            <td class="action-buttons">
                                {{-- Edit --}}
                                <a href="{{ route('dashboard.admin.pet.edit', $pet->idpet) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('dashboard.admin.pet.destroy', $pet->idpet) }}" method="POST"
                                      onsubmit="return confirm('Hapus data {{ $pet->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
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
        {{-- Empty State (Class .empty-message sudah ada di Layout) --}}
        <div class="empty-message">
            <p>Belum ada data pet yang tersedia.</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.pet.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Pet Pertama
                </a>
            </div>
        </div>
    @endif
</div>

@endsection