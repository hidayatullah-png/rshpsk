@extends('layouts.dokter.dokter')

@section('title', 'Pemeriksaan Medis')

@section('content')

<div class="table-container">

    {{-- Header Halaman --}}
    <div class="header-actions">
        <div style="margin-bottom: 2rem;">
            <h2 style="color: #3ea2c7; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                <i class="fas fa-stethoscope"></i> Pemeriksaan Medis
            </h2>
            <p style="color: #777; margin: 5px 0 0;">Daftar pasien yang menunggu pemeriksaan lanjutan atau riwayat.</p>
        </div>

        {{-- Filter Buttons --}}
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('dashboard.dokter.rekam-medis.index') }}" 
               class="btn {{ request('filter') != 'all' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-calendar-day"></i> Hari Ini
            </a>
            <a href="{{ route('dashboard.dokter.rekam-medis.index', ['filter' => 'all']) }}" 
               class="btn {{ request('filter') == 'all' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-history"></i> Semua Data
            </a>
        </div>
    </div>

    {{-- Tabel Data --}}
    @if($data->isNotEmpty())
        <div class="table-responsive" style="margin-top: 1.5rem;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="15%">Waktu</th>
                        <th width="20%">Pasien</th>
                        <th width="20%">Pemilik</th>
                        <th width="30%">Diagnosa Awal</th>
                        <th class="text-center" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td class="text-center" style="font-weight: 600; color: #999;">
                                {{ $loop->iteration + $data->firstItem() - 1 }}
                            </td>
                            
                            {{-- Waktu --}}
                            <td>
                                <div style="font-weight: 600; color: #333;">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </div>
                                <small style="color: #888;">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                </small>
                            </td>

                            {{-- Pasien --}}
                            <td>
                                <div style="font-weight: 700; color: #666; font-size: 1rem;">
                                    {{ $item->nama_pet }}
                                </div>
                            </td>

                            {{-- Pemilik --}}
                            <td style="color: #555;">
                                {{ $item->nama_pemilik }}
                            </td>

                            {{-- Diagnosa --}}
                            <td>
                                <span style="background-color: #f0f7fb; color: #3ea2c7; padding: 6px 12px; border-radius: 4px; font-size: 0.9rem; font-style: italic;">
                                    {{ Str::limit($item->diagnosa, 50) ?: 'Belum ada diagnosa' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <a href="{{ route('dashboard.dokter.rekam-medis.show', $item->idrekam_medis) }}" 
                                   class="btn btn-primary btn-sm" style="border-radius: 20px;"
                                   title="Periksa / Detail">
                                    <i class="fas fa-edit"></i> Periksa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
            {{ $data->withQueryString()->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div style="text-align: center; padding: 4rem 2rem; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #ddd; margin-top: 1.5rem;">
            <div style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;">
                <i class="fas fa-user-md"></i>
            </div>
            <p style="color: #999; margin: 0; font-size: 1.1rem;">Belum ada pasien untuk diperiksa hari ini.</p>
        </div>
    @endif

</div>

@endsection