@extends('layouts.perawat.perawat')

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
            <a href="{{ route('dashboard.perawat.rekam-medis.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Grid Informasi (Kiri: Pasien, Kanan: Dokter) --}}
        <div class="detail-grid">

            {{-- Kartu Info Pasien --}}
            <div class="info-card">
                <div class="card-title"><i class="fas fa-paw"></i> Informasi Pasien</div>

                <div class="info-row">
                    <label>Nama Hewan:</label>
                    {{-- Diambil dari alias 'nama_pet' --}}
                    <span>{{ $rekamMedis->nama_pet ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <label>Jenis / Ras:</label>
                    <span>
                        {{-- Diambil dari kolom 'jenis_hewan' dan 'ras' di tabel pet --}}
                        {{ $rekamMedis->jenis_hewan ?? '-' }} /
                        {{ $rekamMedis->ras ?? '-' }}
                    </span>
                </div>

                <div class="info-row">
                    <label>Pemilik:</label>
                    {{-- Diambil dari alias 'nama_pemilik' --}}
                    <span>{{ $rekamMedis->nama_pemilik ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <label>Kontak:</label>
                    {{-- Pastikan di controller select mencakup 'pem.no_wa' --}}
                    <span>{{ $rekamMedis->no_wa ?? '-' }}</span>
                </div>
            </div>

            {{-- Kartu Info Dokter --}}
            <div class="info-card">
                <div class="card-title"><i class="fas fa-user-md"></i> Dokter Pemeriksa</div>

                <div class="doctor-profile">
                    {{-- Avatar inisial nama dokter --}}
                    <div class="avatar-circle">
                        {{ substr($rekamMedis->nama_dokter ?? 'D', 0, 1) }}
                    </div>
                    <div>
                        {{-- Diambil dari alias 'nama_dokter' --}}
                        <strong>{{ $rekamMedis->nama_dokter ?? 'Tidak diketahui' }}</strong>
                        <p style="margin:0; font-size:0.85rem; color:#777;">Dokter Hewan</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kartu Data Medis Lengkap --}}
        <div class="medical-data-box">
            <h3 style="margin-top:0; color:#3ea2c7; margin-bottom:1.5rem;">Data Pemeriksaan</h3>

            <div class="medical-item">
                <span class="label">Anamnesa / Keluhan</span>
                <p class="content-text">{{ $rekamMedis->anamnesa ?: '-' }}</p>
            </div>

            <div class="medical-item">
                <span class="label">Temuan Klinis</span>
                <p class="content-text">{{ $rekamMedis->temuan_klinis ?: '-' }}</p>
            </div>

            <div class="medical-item">
                <span class="label">Diagnosa</span>
                {{-- Style khusus untuk diagnosa agar lebih menonjol --}}
                <p class="content-text"
                    style="background:#e3f2fd; border-left-color:#1976d2; font-weight:600; color:#0d47a1;">
                    {{ $rekamMedis->diagnosa }}
                </p>
            </div>

            {{-- ✅ BAGIAN BARU: Menampilkan List Tindakan (Menggantikan kolom Terapi yang error) --}}
            <div class="medical-item">
                <span class="label">Tindakan / Terapi (Detail)</span>

                @if(isset($detailRekamMedis) && $detailRekamMedis->isNotEmpty())
                    <ul style="padding-left: 20px; color: #555; margin-top: 5px;">
                        @foreach($detailRekamMedis as $tindakan)
                            <li style="margin-bottom: 8px;">
                                {{-- Menampilkan Kode & Deskripsi --}}
                                <strong style="color: #3ea2c7;">[{{ $tindakan->kode }}]</strong>
                                {{ $tindakan->deskripsi_tindakan_terapi }}

                                {{-- Jika ada catatan tambahan --}}
                                @if(isset($tindakan->detail) && $tindakan->detail)
                                    <br><small style="color: #888;">Note: {{ $tindakan->detail }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="content-text" style="color: #999; font-style: italic;">Tidak ada tindakan khusus.</p>
                @endif
            </div>

        </div>

    </div>

@endsection