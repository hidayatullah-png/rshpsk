@extends('layouts.admin.admin')

@section('title', 'Tambah Kategori Klinis')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Tambah Kategori Klinis"
        :action="route('dashboard.admin.kategori-klinis.store')"
        :back-route="route('dashboard.admin.kategori-klinis.index')"
    >
        {{-- SLOT: Input Field --}}
        <div class="form-group">
            <label for="nama_kategori_klinis">Nama Kategori Klinis</label>
            
            <input type="text" 
                   id="nama_kategori_klinis" 
                   name="nama_kategori_klinis" 
                   class="form-control @error('nama_kategori_klinis') is-invalid @enderror" 
                   value="{{ old('nama_kategori_klinis') }}" 
                   placeholder="Masukkan nama kategori klinis..."
                   required 
                   autofocus>

            @error('nama_kategori_klinis')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection