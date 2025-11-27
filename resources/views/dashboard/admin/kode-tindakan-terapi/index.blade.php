@extends('layouts.admin.admin')

@section('title', 'Manajemen Kode Tindakan Terapi')

@section('content')
<div class="main-container">
    <h2>Manajemen Kode Tindakan Terapi</h2>

    {{-- Tombol Tambah --}}
    <div class="action-header">
        <a href="{{ route('dashboard.admin.kode-tindakan-terapi.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Tindakan
        </a>
    </div>

    {{-- Logic Table --}}
    @if ($KodeTindakanTerapi->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th> 
                        <th>Deskripsi Tindakan</th>
                        <th>Kategori</th>
                        <th>Kategori Klinis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($KodeTindakanTerapi as $tindakan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            
                            {{-- Menampilkan Kode --}}
                            <td style="font-weight: 600; color: #3ea2c7;">{{ $tindakan->kode }}</td>

                            <td>{{ $tindakan->deskripsi_tindakan_terapi }}</td>

                            {{-- PERBAIKAN DI SINI: Panggil langsung nama alias kolomnya --}}
                            <td>{{ $tindakan->nama_kategori ?? '-' }}</td>
                            <td>{{ $tindakan->nama_kategori_klinis ?? '-' }}</td>

                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('dashboard.admin.kode-tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('dashboard.admin.kode-tindakan-terapi.destroy', $tindakan->idkode_tindakan_terapi) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus tindakan ini ({{ $tindakan->kode }} - {{ $tindakan->deskripsi_tindakan_terapi }})?')">
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
            <p>Tidak ada data kode tindakan terapi yang tersedia.</p>
            <div class="empty-state-actions">
                <a href="{{ route('dashboard.admin.kode-tindakan-terapi.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i> Tambah Data Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection