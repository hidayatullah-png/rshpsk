@extends('layouts.dokter.dokter')

@section('title', 'Detail Rekam Medis')

@section('content')

<div class="detail-container">

    {{-- Header Detail (Nomor RM & Tombol Kembali) --}}
    <div class="detail-header">
        <div>
            <h2>RM-{{ str_pad($rekamMedis->idrekam_medis, 5, '0', STR_PAD_LEFT) }}</h2>
            <span class="date-badge">
                <i class="far fa-calendar-alt"></i>
                {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d M Y, H:i') }} WIB
            </span>
        </div>
        {{-- Kembali ke Index Dokter --}}
        <a href="{{ route('dashboard.dokter.rekam-medis.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="detail-grid">
        
        {{-- KARTU KIRI: INFO PASIEN --}}
        <div class="info-card">
            <div class="card-title"><i class="fas fa-paw"></i> Informasi Pasien</div>
            <div class="info-row"><label>Nama Hewan:</label><span>{{ $rekamMedis->nama_pet ?? '-' }}</span></div>
            <div class="info-row"><label>Jenis / Ras:</label><span>{{ $rekamMedis->jenis_hewan ?? '-' }} / {{ $rekamMedis->ras ?? '-' }}</span></div>
            <div class="info-row"><label>Pemilik:</label><span>{{ $rekamMedis->nama_pemilik ?? '-' }}</span></div>
            <div class="info-row"><label>Kontak:</label><span>{{ $rekamMedis->no_wa ?? '-' }}</span></div>
        </div>

        {{-- KARTU KANAN: INFO DOKTER --}}
        <div class="info-card">
            <div class="card-title"><i class="fas fa-user-md"></i> Dokter Pemeriksa</div>
            <div class="doctor-profile">
                <div class="avatar-circle">
                    {{ substr($rekamMedis->nama_dokter ?? 'D', 0, 1) }}
                </div>
                <div>
                    <strong>{{ $rekamMedis->nama_dokter ?? 'Tidak diketahui' }}</strong>
                    <p style="margin:0; font-size:0.85rem; color:#777;">Dokter Hewan</p>
                </div>
            </div>
        </div>
    </div>


    {{-- ======================================================= --}}
    {{-- BAGIAN 2: FORM UPDATE DATA MEDIS DASAR (DOKTER EDIT) --}}
    {{-- ======================================================= --}}
    <div class="medical-data-box">
        <h4 style="color:#3ea2c7; margin-bottom:1.5rem;"><i class="fas fa-notes-medical"></i> Data Pemeriksaan</h4>
        
        {{-- Form menargetkan PUT / rekamedis/{id} di DokterController --}}
        <form action="{{ route('dashboard.dokter.rekam-medis.update', $rekamMedis->idrekam_medis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-group">
                {{-- Kiri: Anamnesa (Read Only) --}}
                <div class="form-group half">
                    <label class="label">Anamnesa / Keluhan (Perawat)</label>
                    <textarea name="anamnesa_ro" rows="4" class="form-control" readonly style="background-color: #f5f5f5;">{{ $rekamMedis->anamnesa ?: '-' }}</textarea>
                </div>
                
                {{-- Kanan: Temuan Klinis (Read Only) --}}
                <div class="form-group half">
                    <label class="label">Temuan Klinis (Perawat)</label>
                    <textarea name="temuan_klinis_ro" rows="4" class="form-control" readonly style="background-color: #f5f5f5;">{{ $rekamMedis->temuan_klinis ?: '-' }}</textarea>
                </div>
            </div>

            {{-- Diagnosa (Dapat Diubah Dokter) --}}
            <div class="form-group">
                <label for="diagnosa" class="label">Diagnosa <span style="color:red;">*</span></label>
                <input type="text" name="diagnosa" id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror" value="{{ old('diagnosa', $rekamMedis->diagnosa) }}" required>
                @error('diagnosa') <span class="error-text">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Diagnosa</button>
            </div>
        </form>
    </div>


    {{-- ======================================================= --}}
    {{-- BAGIAN 3: CRUD DETAIL TINDAKAN (TABEL UTAMA DOKTER) --}}
    {{-- ======================================================= --}}
    <div class="medical-data-box" style="margin-top: 2rem;">
        <h4 style="color:#3ea2c7; margin-bottom:1.5rem;"><i class="fas fa-syringe"></i> Kelola Tindakan/Terapi</h4>
        
        {{-- Tabel List Tindakan yang Sudah Ada --}}
        @if($detailTindakan->isNotEmpty())
            <div class="table-responsive">
                <table class="custom-table data-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="15%">Kode</th>
                            <th width="70%">Deskripsi Tindakan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailTindakan as $item)
                            <tr>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->deskripsi_tindakan_terapi }}</td>
                                <td class="text-center">
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('dashboard.dokter.rekam-medis.hapus-tindakan', $item->iddetail_rekam_medis) }}" method="POST" onsubmit="return confirm('Yakin hapus tindakan ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted" style="font-style: italic; padding: 10px 0;">Belum ada tindakan/terapi yang dicatat.</p>
        @endif
        
        
        <hr class="divider-dashed" style="margin: 2rem 0 1.5rem 0;">

        {{-- Form Tambah Tindakan Baru --}}
        <h5 style="color:#444; margin-bottom:1rem;"><i class="fas fa-plus-circle"></i> Tambah Tindakan Baru</h5>
        <form action="{{ route('dashboard.dokter.rekam-medis.tambah-tindakan', $rekamMedis->idrekam_medis) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <select name="idkode_tindakan_terapi" class="form-control @error('idkode_tindakan_terapi') is-invalid @enderror" required>
                    <option value="" selected disabled>-- Pilih Kode Tindakan/Terapi --</option>
                    @foreach($listMasterTindakan as $master)
                        <option value="{{ $master->idkode_tindakan_terapi }}">
                            {{ $master->kode }} - {{ $master->deskripsi_tindakan_terapi }}
                        </option>
                    @endforeach
                </select>
                @error('idkode_tindakan_terapi') <span class="error-text">{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Tambah Tindakan</button>
        </form>
    </div>

</div>

@endsection