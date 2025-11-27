@extends('layouts.admin.admin')

@section('title', 'Edit Kategori')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Kategori"
        :action="route('dashboard.admin.kategori.update', $kategori->idkategori)"
        :back-route="route('dashboard.admin.kategori.index')"
        :is-edit="true"
    >
        {{-- HANYA ISI INPUT FIELD SAJA DI SINI --}}
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori"
                class="form-control @error('nama_kategori') is-invalid @enderror"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                required autofocus>

            @error('nama_kategori')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection