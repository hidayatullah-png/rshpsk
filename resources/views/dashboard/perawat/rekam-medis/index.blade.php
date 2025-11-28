@extends('layouts.perawat.perawat')

@section('title', 'Data Rekam Medis')

@section('content')

<div class="table-container">
    
    {{-- ================= BAGIAN 1: ANTRIAN PASIEN (DATA TEMU DOKTER) ================= --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="color: #3ea2c7; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-users"></i> Antrian Pasien Hari Ini
        </h3>

        @if($antrian->isNotEmpty())
            <div class="table-responsive" style="margin-top: 1rem;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="50" style="text-align: center;">No</th>
                            <th>Nama Hewan</th>
                            <th>Pemilik</th>
                            <th>Status</th>
                            <th width="220" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($antrian as $q)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{ $q->no_urut }}</td>
                                
                                <td style="vertical-align: middle;"><strong>{{ $q->nama_pet }}</strong></td>
                                <td style="vertical-align: middle;">{{ $q->nama_pemilik }}</td>
                                
                                <td style="vertical-align: middle;">
                                    @if($q->status == 'Proses')
                                        <span class="badge badge-warning" style="border-radius: 20px; padding: 8px 15px; color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba;">
                                            Sedang Diperiksa
                                        </span>
                                    @else
                                        <span class="badge badge-secondary" style="border-radius: 20px; padding: 8px 15px; background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db;">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($q->status != 'Proses')
                                            {{-- TOMBOL PANGGIL --}}
                                            <a href="{{ route('dashboard.perawat.rekam-medis.panggil', $q->idreservasi_dokter) }}" 
                                               class="btn btn-primary btn-sm" style="border-radius: 20px;" title="Panggil Pasien">
                                                <i class="fas fa-bullhorn"></i>
                                            </a>

                                            {{-- TOMBOL BATAL (DELETE) - Hanya muncul jika belum diproses --}}
                                            <a href="{{ route('dashboard.perawat.rekam-medis.batal', $q->idreservasi_dokter) }}" 
                                               class="btn btn-danger btn-sm" style="border-radius: 20px;" 
                                               onclick="return confirm('Apakah Anda yakin ingin membatalkan antrian ini? Data akan hilang dari daftar.')"
                                               title="Batalkan Antrian">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif

                                        {{-- TOMBOL PERIKSA --}}
                                        <a href="{{ route('dashboard.perawat.rekam-medis.create', ['id_reservasi' => $q->idreservasi_dokter]) }}" 
                                           class="btn btn-success btn-sm" style="border-radius: 20px;" title="Periksa Pasien">
                                            <i class="fas fa-stethoscope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="margin-top: 1rem;">
                <i class="fas fa-info-circle"></i> Tidak ada antrian pasien saat ini.
            </div>
        @endif
    </div>


    {{-- ================= BAGIAN 2: HISTORY REKAM MEDIS ================= --}}
    
    <div style="margin-bottom: 1.5rem; margin-top: 3rem;">
        <h3 style="color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-history"></i> Riwayat Pemeriksaan
        </h3>
    </div>

    {{-- Filter & Tombol Tambah --}}
    <div class="header-actions">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('dashboard.perawat.rekam-medis.index') }}" 
               class="btn {{ request('filter') != 'all' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-calendar-day"></i> Hari Ini
            </a>
            <a href="{{ route('dashboard.perawat.rekam-medis.index', ['filter' => 'all']) }}" 
               class="btn {{ request('filter') == 'all' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-history"></i> Semua History
            </a>
        </div>
        
        {{-- Tombol Tambah Manual (Tanpa Antrian) --}}
        <a href="{{ route('dashboard.perawat.rekam-medis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Manual
        </a>
    </div>

    {{-- Tabel History --}}
    @if($history->isNotEmpty())
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="50" style="text-align: center;">No</th>
                        <th>Tanggal</th>
                        <th>Hewan</th>
                        <th>Diagnosa</th>
                        <th width="150" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration + $history->firstItem() - 1 }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                                <small style="color:#888;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $item->nama_pet }}</strong><br>
                                <small>{{ $item->nama_pemilik }}</small>
                            </td>
                            <td>{{ Str::limit($item->diagnosa, 40) }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('dashboard.perawat.rekam-medis.show', $item->idrekam_medis) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 1rem;">
             {{ $history->withQueryString()->links() }} 
        </div>

    @else
        <div style="text-align: center; padding: 2rem; color: #999;">
            Belum ada data riwayat rekam medis.
        </div>
    @endif
</div>

@endsection