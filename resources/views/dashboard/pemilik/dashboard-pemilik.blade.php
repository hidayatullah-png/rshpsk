@extends('layouts.pemilik.pemilik')

@section('title', 'Dashboard - Pemilik')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            @php
                // Mengambil nama dari session atau helper auth
                $nama_user = session('user_name') ?? auth()->user()->nama ?? 'Pemilik';
            @endphp
            <h2>Selamat Datang, {{ $nama_user }}!</h2>
            <span style="color: #777;">Ini adalah halaman utama untuk mengelola kesehatan hewan peliharaan Anda.</span>
        </div>
        
        <span class="badge badge-info" style="font-size: 1rem; padding: 10px 20px;">
            <i class="fas fa-user-tag mr-2"></i> Pemilik Hewan
        </span>
    </div>

    {{-- Statistik Ringkas (Grid Layout) --}}
    <div class="detail-grid">

        {{-- Kartu Total Hewan --}}
        <div class="info-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #777; margin: 0; font-size: 0.9rem; font-weight: 500;">Total Hewan Peliharaan</p>
                <h3 style="color: #3ea2c7; margin: 0.5rem 0 0; font-size: 2.5rem;">{{ $jumlahPet ?? 0 }}</h3>
                <a href="{{ route('dashboard.pemilik.daftar-pet.index') }}" style="font-size: 0.85rem; color: #3ea2c7; text-decoration: none; margin-top: 5px; display: inline-block;">
                    Lihat detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div style="background: #e3f2fd; width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-paw" style="font-size: 2rem; color: #3ea2c7;"></i>
            </div>
        </div>

        {{-- Kartu Total Reservasi --}}
        <div class="info-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #777; margin: 0; font-size: 0.9rem; font-weight: 500;">Reservasi / Janji Temu</p>
                <h3 style="color: #3ea2c7; margin: 0.5rem 0 0; font-size: 2.5rem;">{{ $jumlahReservasi ?? 0 }}</h3>
                <a href="{{ route('dashboard.pemilik.reservasi.index') }}" style="font-size: 0.85rem; color: #3ea2c7; text-decoration: none; margin-top: 5px; display: inline-block;">
                    Cek jadwal <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div style="background: #e3f2fd; width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-calendar-check" style="font-size: 2rem; color: #3ea2c7;"></i>
            </div>
        </div>

    </div>

    {{-- Menu Cepat / Quick Actions --}}
    <div class="info-card" style="margin-top: 2rem;">
        <div class="card-title">
            <i class="fas fa-bolt text-warning"></i> Menu Cepat
        </div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
            {{-- Tombol Tambah Hewan --}}
            <a href="{{ route('dashboard.pemilik.daftar-pet.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Hewan Baru
            </a>
            
            {{-- Tombol Buat Reservasi --}}
            <a href="{{ route('dashboard.pemilik.reservasi.create') }}" class="btn btn-outline">
                <i class="fas fa-calendar-plus"></i> Buat Reservasi
            </a>

            {{-- Tombol Lihat Rekam Medis (Hanya Index) --}}
            <a href="{{ route('dashboard.pemilik.rekam-medis.index') }}" class="btn btn-outline">
                <i class="fas fa-file-medical-alt"></i> Lihat Rekam Medis
            </a>

            {{-- Tombol Edit Profil --}}
            <a href="{{ route('dashboard.pemilik.profil.index') }}" class="btn btn-outline">
                <i class="fas fa-user-cog"></i> Edit Profil
            </a>
        </div>
    </div>

</div>

@endsection