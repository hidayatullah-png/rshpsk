@extends('layouts.admin.admin')

@section('title', 'Edit Data Pemilik')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Data Pemilik"
        :action="route('dashboard.admin.pemilik.update', $pemilik->idpemilik)"
        :back-route="route('dashboard.admin.pemilik.index')"
        :is-edit="true"
    >
        {{-- BAGIAN 1: INFORMASI AKUN --}}
        <div class="form-section-title">
            <i class="fas fa-id-card"></i> Informasi Akun
        </div>

        {{-- Input Nama --}}
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama', $pemilik->user->nama) }}" 
                   required>
            
            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Input Email --}}
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $pemilik->user->email) }}" 
                   required>
            
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Garis Pemisah --}}
        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 2rem 0;">

        {{-- BAGIAN 2: KONTAK & ALAMAT --}}
        <div class="form-section-title">
            <i class="fas fa-map-marker-alt"></i> Kontak & Alamat
        </div>

        {{-- Input WhatsApp --}}
        <div class="form-group">
            <label for="no_wa">Nomor WhatsApp</label>
            <input type="text" 
                   id="no_wa" 
                   name="no_wa" 
                   class="form-control @error('no_wa') is-invalid @enderror" 
                   value="{{ old('no_wa', $pemilik->no_wa) }}" 
                   placeholder="Contoh: 08123456789"
                   required>
            
            @error('no_wa')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Input Alamat (Textarea) --}}
        <div class="form-group">
            <label for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" 
                      name="alamat" 
                      class="form-control @error('alamat') is-invalid @enderror" 
                      placeholder="Masukkan alamat lengkap domisili..."
                      rows="3"
                      required>{{ old('alamat', $pemilik->alamat) }}</textarea>
            
            @error('alamat')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>

    {{-- Style Khusus untuk Judul Per-Section di Halaman Ini --}}
    <style>
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #3ea2c7;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
@endsection