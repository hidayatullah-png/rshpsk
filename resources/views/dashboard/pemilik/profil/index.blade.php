@extends('layouts.pemilik.pemilik')

@section('title', 'Profil Saya')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            <h2>Profil Pemilik</h2>
            <span style="color: #777;">Informasi akun dan data pribadi Anda.</span>
        </div>
    </div>

    <div class="detail-grid">

        {{-- KARTU KIRI: FOTO & INFO SINGKAT --}}
        <div class="info-card" style="text-align: center;">

            <div class="card-title">Foto Profil</div>

            <div style="display: flex; justify-content: center; margin: 2rem 0;">
                {{-- LOGIKA GAMBAR PROFIL --}}
                @php
                    $idUser = auth()->user()->iduser;
                    $fotoPath = 'images/users/' . $idUser . '.jpg';
                @endphp

                @if(file_exists(public_path($fotoPath)))
                    <img src="{{ asset($fotoPath) }}" alt="Foto Profil"
                        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #e3f2fd;">
                @else
                    <div class="avatar-circle" style="width: 100px; height: 100px; font-size: 3rem;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>

            <h3 style="color: #3ea2c7; margin: 10px 0;">{{ $profil->nama_user }}</h3>
            <p style="color: #777; margin-bottom: 1.5rem;">{{ $profil->email }}</p>

            <div class="badge-status" style="font-size: 0.9rem; padding: 10px 20px; display: inline-block; width: auto;">
                {{-- PERBAIKAN DI SINI: Menggunakan $profil->jumlah_pet --}}
                <i class="fas fa-paw mr-1"></i> Memiliki <strong>{{ $profil->jumlah_pet }}</strong> Hewan
            </div>
        </div>

        {{-- KARTU KANAN: DETAIL INFORMASI --}}
        <div class="info-card">
            <div class="card-title">
                <i class="fas fa-id-card"></i> Detail Informasi
            </div>

            <div class="info-row">
                <label>Nama Lengkap</label>
                <span>{{ $profil->nama_user }}</span>
            </div>

            <div class="info-row">
                <label>Email</label>
                <span>{{ $profil->email }}</span>
            </div>

            <div class="divider-dashed" style="margin: 1rem 0;"></div>

            <div class="info-row">
                <label>No. WhatsApp / HP</label>
                <span>
                    @if($profil->no_hp != '-')
                        {{ $profil->no_hp }}
                    @else
                        <span class="text-muted font-italic">Belum diisi</span>
                    @endif
                </span>
            </div>

            <div class="info-row">
                <label>Alamat Domisili</label>
                <span style="line-height: 1.5; text-align: right;">
                    {{ $profil->alamat ?? '-' }}
                </span>
            </div>
            
            {{-- Informasi Tambahan (Opsional) --}}
            <div class="mt-4 p-3 rounded" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                <small class="text-muted d-flex align-items-start">
                    <i class="fas fa-info-circle mr-2 mt-1 text-info"></i>
                    <span>Pastikan nomor WhatsApp Anda aktif agar klinik dapat menghubungi Anda terkait jadwal pemeriksaan hewan kesayangan Anda.</span>
                </small>
            </div>

        </div>

    </div>

</div>

@endsection