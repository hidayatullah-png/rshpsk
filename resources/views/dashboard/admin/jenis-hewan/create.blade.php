@extends('layouts.admin.admin')

@section('title', 'Tambah Jenis Hewan Baru')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Tambah Jenis Hewan"
        :action="route('dashboard.admin.jenis-hewan.store')"
        :back-route="route('dashboard.admin.jenis-hewan.index')"
    >
        {{-- SLOT: Hanya isi input field saja --}}
        <div class="form-group">
            <label for="nama_jenis_hewan">Nama Jenis Hewan</label>
            
            <input type="text" 
                   id="nama_jenis_hewan" 
                   name="nama_jenis_hewan" 
                   class="form-control @error('nama_jenis_hewan') is-invalid @enderror" 
                   value="{{ old('nama_jenis_hewan') }}" 
                   required 
                   autofocus>

            @error('nama_jenis_hewan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection