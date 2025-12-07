@extends('layouts.pemilik.pemilik')

@section('title', 'Riwayat Medis')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            <h2>Riwayat Medis</h2>
            <span style="color: #777;">Daftar lengkap riwayat kesehatan dan pemeriksaan hewan peliharaan Anda.</span>
        </div>
    </div>

    {{-- Filter Section (Hanya Dropdown Hewan) --}}
    <div class="info-card mb-4" style="padding: 1.5rem;">
        <form action="{{ route('dashboard.pemilik.rekam-medis.index') }}" method="GET" style="display: flex; align-items: center; gap: 1rem;">
            
            {{-- Label --}}
            <div style="font-weight: 600; color: #555; white-space: nowrap;">
                <i class="fas fa-filter mr-1"></i> Filter:
            </div>

            {{-- Dropdown Hewan --}}
            <div style="flex-grow: 1; max-width: 300px;">
                <select name="idpet" class="form-control" onchange="this.form.submit()" style="width: 100%; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">-- Tampilkan Semua Hewan --</option>
                    @foreach($pets as $pet)
                        <option value="{{ $pet->idpet }}" {{ request('idpet') == $pet->idpet ? 'selected' : '' }}>
                            {{ $pet->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Reset (Muncul jika ada filter) --}}
            @if(request('idpet'))
                <a href="{{ route('dashboard.pemilik.rekam-medis.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 20px;">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Daftar Rekam Medis (Tampilan Tabel) --}}
    <div class="info-card" style="padding: 0; overflow: hidden;">
        @if($rekamMedis->isNotEmpty())
            <div class="table-responsive">
                <table class="custom-table" style="width: 100%; margin: 0;">
                    <thead style="background-color: #3ea2c7; color: white;">
                        <tr>
                            <th style="padding: 15px; text-align: center; width: 5%;">No</th>
                            <th style="padding: 15px; width: 15%;">Tanggal</th>
                            <th style="padding: 15px; width: 20%;">Nama Hewan</th>
                            <th style="padding: 15px;">Diagnosa / Catatan</th>
                            <th style="padding: 15px; width: 20%;">Dokter</th>
                            <th style="padding: 15px; text-align: center; width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekamMedis as $index => $rm)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                    {{ $rekamMedis->firstItem() + $index }}
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    <div style="font-weight: 500; color: #333;">
                                        {{ \Carbon\Carbon::parse($rm->created_at)->format('d M Y') }}
                                    </div>
                                    <small style="color: #888;">
                                        {{ \Carbon\Carbon::parse($rm->created_at)->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    <span style="font-weight: 600; color: #3ea2c7;">{{ $rm->nama_pet }}</span>
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    <p style="margin: 0; color: #555; line-height: 1.5;">
                                        {{ Str::limit($rm->diagnosa, 60) ?: '-' }}
                                    </p>
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    @if($rm->nama_dokter)
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-user-md text-muted"></i>
                                            <span>{{ $rm->nama_dokter }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                    <a href="{{ route('dashboard.pemilik.rekam-medis.show', $rm->idrekam_medis) }}" class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="padding: 1.5rem;">
                {{ $rekamMedis->withQueryString()->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div style="text-align: center; padding: 3rem;">
                <div style="color: #ddd; margin-bottom: 1rem;">
                    <i class="fas fa-folder-open" style="font-size: 3rem;"></i>
                </div>
                <h5 style="color: #777;">Belum ada data rekam medis.</h5>
                <p class="text-muted small">
                    Catatan kesehatan hewan Anda akan muncul di sini setelah pemeriksaan.
                </p>
            </div>
        @endif
    </div>

</div>

@endsection