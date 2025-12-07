@extends('layouts.pemilik.pemilik')

@section('title', 'Daftar Hewan Peliharaan')

@section('content')

<div class="table-container">

    {{-- Header Halaman --}}
    <div class="header-actions">
        <div>
            <h2 style="margin: 0; color: #3ea2c7; font-weight: 700;">Hewan Peliharaan Saya</h2>
            <p style="color: #777; margin: 5px 0 0;">Kelola data hewan peliharaan Anda di sini.</p>
        </div>
        
        {{-- Tombol Tambah --}}
        <a href="{{ route('dashboard.pemilik.daftar-pet.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Hewan
        </a>
    </div>

    {{-- Tabel Data Hewan --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            @if($pets->isNotEmpty())
                <div class="table-responsive">
                    <table class="custom-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="25%">Nama Hewan</th>
                                <th width="25%">Jenis & Ras</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">Warna / Tanda</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pets as $pet)
                                <tr>
                                    {{-- No --}}
                                    <td class="text-center align-middle" style="font-weight: 600; color: #555;">
                                        {{ $loop->iteration }}
                                    </td>
                                    
                                    {{-- Nama Hewan --}}
                                    <td class="align-middle">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 40px; height: 40px; background: #e3f2fd; color: #3ea2c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                            <div style="font-weight: 700; color: #3ea2c7; font-size: 1rem;">
                                                {{ $pet->nama }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Jenis & Ras --}}
                                    <td class="align-middle">
                                        <span class="badge" style="background-color: #f0f9fc; color: #3ea2c7; border: 1px solid #bfe2ef; padding: 5px 10px; font-weight: 500; font-size: 0.8rem;">
                                            {{ $pet->nama_jenis_hewan ?? '-' }}
                                        </span>
                                        <div style="font-size: 0.85rem; color: #666; margin-top: 5px; font-style: italic;">
                                            {{ $pet->nama_ras ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Jenis Kelamin --}}
                                    <td class="align-middle">
                                        @if($pet->jenis_kelamin == 'Jantan')
                                            <span style="color: #007bff; font-weight: 500;"><i class="fas fa-mars mr-1"></i> Jantan</span>
                                        @elseif($pet->jenis_kelamin == 'Betina')
                                            <span style="color: #e83e8c; font-weight: 500;"><i class="fas fa-venus mr-1"></i> Betina</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Warna / Tanda --}}
                                    <td class="align-middle text-secondary">
                                        {{ $pet->warna_tanda ?? '-' }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Edit --}}
                                            <a href="{{ route('dashboard.pemilik.daftar-pet.edit', $pet->idpet) }}" 
                                               class="btn btn-warning btn-sm" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Hapus --}}
                                            <form action="{{ route('dashboard.pemilik.daftar-pet.destroy', $pet->idpet) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus hewan ini? Data riwayat medis juga mungkin terpengaruh.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Data">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <i class="fas fa-cat mb-3" style="font-size: 4rem; color: #e0e0e0;"></i>
                    <h5 class="text-muted font-weight-bold">Belum ada hewan peliharaan.</h5>
                    <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">Silakan tambahkan data hewan peliharaan Anda agar dapat melakukan reservasi temu dokter.</p>
                    <a href="{{ route('dashboard.pemilik.daftar-pet.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Tambah Hewan Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection