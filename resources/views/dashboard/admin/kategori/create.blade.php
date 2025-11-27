@extends('layouts.admin.admin')

@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="main-container" style="max-width:600px; text-align:left;">
    <h2 style="text-align:center; margin-bottom:2rem; color:#3ea2c7;">
        {{ isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori' }}
    </h2>

    <form action="{{ isset($kategori) ? route('dashboard.admin.kategori.update', $kategori->idkategori) : route('dashboard.admin.kategori.store') }}" 
          method="POST" class="form-layout">
        @csrf
        @if(isset($kategori))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori"
                class="form-control @error('nama_kategori') is-invalid @enderror"
                value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" 
                required autofocus>
            
            @error('nama_kategori')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard.admin.kategori.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                {{ isset($kategori) ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>

<style>
    .form-layout {
        display:flex; flex-direction:column; gap:1.5rem;
    }
    .form-control {
        width:100%; padding:12px 15px; border-radius:8px; border:1px solid #ddd;
        font-size:1rem; box-sizing:border-box; transition:0.3s;
    }
    .form-control:focus {
        outline:none; border-color:#3ea2c7; box-shadow:0 0 0 3px rgba(62,162,199,0.2);
    }
    .error-message { color:#dc3545; font-size:0.875rem; margin-top:0.4rem; }
    .form-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem; }
    .btn-primary:hover { background-color:#2e8aa8; }
    .btn-secondary:hover { background-color:#e9e9e9; border-color:#ccc; }
    @media(max-width:480px){
        .form-actions { flex-direction:column-reverse; }
        .form-actions .btn { width:100%; justify-content:center; }
    }
</style>
@endsection
