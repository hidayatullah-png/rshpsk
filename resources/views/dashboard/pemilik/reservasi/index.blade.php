@extends('layouts.pemilik.pemilik')

@section('title', 'Riwayat Reservasi')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            <h2>Reservasi & Temu Janji</h2>
            <span style="color: #777;">Kelola jadwal pemeriksaan kesehatan hewan peliharaan Anda.</span>
        </div>
        
        {{-- Tombol Tambah --}}
        <a href="{{ route('dashboard.pemilik.reservasi.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Buat Janji Baru
        </a>
    </div>

    {{-- Tabel Container --}}
    <div class="info-card" style="padding: 0; overflow: hidden;">
        
        <div class="table-responsive">
            <table class="custom-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 30%;">Hewan</th>
                        <th style="width: 30%;">Waktu Daftar</th>
                        <th style="width: 20%; text-align: center;">No. Antrian</th>
                        <th style="width: 15%; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasi as $index => $res)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $res->nama_pet ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $res->nama_dokter ?? 'Dokter Umum' }}</small>
                            </td>
                            <td>
                                {{-- Menggunakan kolom waktu_daftar dari database --}}
                                <div><i class="far fa-calendar-alt text-info mr-1"></i> {{ \Carbon\Carbon::parse($res->waktu_daftar)->isoFormat('D MMMM Y') }}</div>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($res->waktu_daftar)->format('H:i') }} WIB</small>
                            </td>
                            <td style="text-align: center;">
                                {{-- Menggunakan kolom no_urut --}}
                                @if($res->no_urut)
                                    <span style="font-size: 1.2rem; font-weight: bold; color: #3ea2c7;">{{ $res->no_urut }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $statusClass = 'secondary';
                                    // Sesuaikan dengan char(7) status database Anda
                                    $status = trim($res->status); 
                                    if($status == 'Menunggu' || $status == 'Baru') $statusClass = 'warning';
                                    elseif($status == 'Proses') $statusClass = 'info';
                                    elseif($status == 'Selesai') $statusClass = 'success';
                                    elseif($status == 'Batal') $statusClass = 'danger';
                                @endphp
                                <span class="badge badge-{{ $statusClass }}" style="padding: 5px 10px; border-radius: 20px; font-weight: 500;">
                                    {{ $res->status ?? 'Menunggu' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem;">
                                <div style="color: #bbb; margin-bottom: 10px;">
                                    <i class="fas fa-calendar-times" style="font-size: 3rem;"></i>
                                </div>
                                <h5 style="color: #777;">Belum ada riwayat reservasi.</h5>
                                <p class="text-muted">Silakan buat janji temu baru untuk hewan kesayangan Anda.</p>
                                <a href="{{ route('dashboard.pemilik.reservasi.create') }}" class="btn btn-outline-primary btn-sm mt-2">
                                    Buat Reservasi Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination jika diperlukan --}}
        @if(method_exists($reservasi, 'links'))
            <div class="p-3">
                {{ $reservasi->links() }}
            </div>
        @endif

    </div>

</div>

@endsection