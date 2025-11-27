@extends('layouts.admin.admin')

@section('title', 'Data Rekam Medis')

@section('content')

<div class="main-container">
    <h2>Data Rekam Medis</h2>

    {{-- Action Header: Filter di Kiri, Tambah di Kanan --}}
    <div class="action-header" style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom: 1.5rem;">
        
        {{-- Grup Tombol Filter --}}
        <div class="filter-group">
            <a href="{{ route('dashboard.admin.rekam-medis.index') }}" 
               class="btn {{ request('filter') != 'today' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-history"></i> Semua History
            </a>
            
            <a href="{{ route('dashboard.admin.rekam-medis.index', ['filter' => 'today']) }}" 
               class="btn {{ request('filter') == 'today' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-calendar-day"></i> Hari Ini
            </a>
        </div>

        {{-- Tombol Tambah --}}
        <a href="{{ route('dashboard.admin.rekam-medis.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Data --}}
    @if($data->isNotEmpty())
        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align:center; width:50px;">No</th>
                        <th style="padding: 12px; text-align: left;">Tanggal Periksa</th>
                        <th style="padding: 12px; text-align: left;">Nama Hewan</th>
                        <th style="padding: 12px; text-align: left;">Pemilik</th>
                        <th style="padding: 12px; text-align: left;">Dokter</th>
                        <th style="padding: 12px; text-align: left;">Diagnosa</th>
                        <th style="padding: 12px; text-align:center; width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px; text-align:center;">{{ $loop->iteration + $data->firstItem() - 1 }}</td>
                            
                            {{-- Tanggal & Jam --}}
                            <td style="padding: 12px;">
                                @if($item->created_at)
                                    <div style="font-weight:600;">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                                    <small style="color:#888;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</small>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- 
                                PERBAIKAN UTAMA:
                                Mengambil data via relasi 'temuDokter' karena 'idpet' ada di tabel temu_dokter 
                            --}}
                            <td style="padding: 12px;">
                                {{ $item->temuDokter->pet->nama ?? '-' }}
                            </td>
                            
                            <td style="padding: 12px;">
                                {{ $item->temuDokter->pet->pemilik->user->nama ?? '-' }}
                            </td>
                            
                            {{-- Relasi Dokter --}}
                            <td style="padding: 12px;">{{ $item->dokter->nama ?? 'Belum ditentukan' }}</td>
                            
                            {{-- Diagnosa (Limit Text) --}}
                            <td style="padding: 12px;">{{ Str::limit($item->diagnosa, 40, '...') ?? '-' }}</td>

                            {{-- Tombol Aksi --}}
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 5px;">
                                    <a href="{{ route('dashboard.admin.rekam-medis.edit', $item->idrekam_medis) }}" class="btn btn-warning btn-sm" style="color:white; padding: 5px 10px; font-size: 0.8rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('dashboard.admin.rekam-medis.destroy', $item->idrekam_medis) }}" method="POST"
                                          onsubmit="return confirm('Hapus rekam medis hewan {{ $item->temuDokter->pet->nama ?? '' }}?');" 
                                          style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 5px 10px; font-size: 0.8rem;">
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

        {{-- Pagination --}}
        <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
             <div style="font-size: 0.9rem; color: #666;">
                 Total Data: <strong>{{ $data->total() }}</strong>
             </div>
             <div>
                {{ $data->links() }} 
             </div>
        </div>

    @else
        {{-- Empty State --}}
        <div class="empty-message" style="text-align: center; padding: 2rem; background: #f9f9f9; border-radius: 8px; margin-top: 1rem;">
            <p style="color: #777; font-size: 1.1rem;">Belum ada data rekam medis.</p>
        </div>
    @endif
</div>

@endsection