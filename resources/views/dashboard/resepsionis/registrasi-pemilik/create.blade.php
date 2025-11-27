{{-- Pastikan extends mengarah ke layout resepsionis yang benar --}}
@extends('layouts.resepsionis.resepsionis')

@section('title', 'Registrasi Pemilik Baru')

@section('content')

    {{-- Memanggil Component Resepsionis Form yang baru dibuat --}}
    <x-resepsionis-form title="Registrasi Pemilik Baru" :action="route('dashboard.resepsionis.registrasi-pemilik.store')"
        :back-route="route('dashboard.resepsionis.dashboard-resepsionis')">
        {{-- BAGIAN 1: INFORMASI AKUN --}}
        <div class="form-section-title">
            <i class="fas fa-user-shield"></i> Informasi Akun Login
        </div>

        {{-- 1. Nama Lengkap --}}
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama') }}" placeholder="Masukkan nama lengkap pemilik..." required autofocus>

            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Email --}}
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Contoh: pemilik@email.com" required>

            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 3. Password --}}
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>

            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Garis Pemisah --}}
        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 2rem 0;">

        {{-- BAGIAN 2: DATA PROFIL --}}
        <div class="form-section-title">
            <i class="fas fa-address-card"></i> Kontak & Alamat
        </div>

        {{-- 4. Nomor WA --}}
        <div class="form-group">
            <label for="no_wa">Nomor WhatsApp</label>
            <input type="number" id="no_wa" name="no_wa" class="form-control @error('no_wa') is-invalid @enderror"
                value="{{ old('no_wa') }}" placeholder="Contoh: 08123456789" required>

            @error('no_wa')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 5. Alamat --}}
        <div class="form-group">
            <label for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                placeholder="Masukkan alamat lengkap domisili..." rows="3" required>{{ old('alamat') }}</textarea>

            @error('alamat')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-resepsionis-form>

    {{-- Style Tambahan Khusus Halaman Ini --}}
    @section('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .form-section-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #3ea2c7;
                margin-bottom: 1.2rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        </style>
    @endsection

@endsection