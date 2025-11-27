@extends('layouts.admin.admin')

@section('title', 'Edit Jenis Hewan')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Jenis Hewan"
        :action="route('dashboard.admin.jenis-hewan.update', $jenis->idjenis_hewan)"
        :back-route="route('dashboard.admin.jenis-hewan.index')"
        :is-edit="true"
    >
        {{-- SLOT: Hanya isi input field spesifik Jenis Hewan di sini --}}
        <div class="form-group">
            <label for="nama_jenis_hewan">Nama Jenis Hewan</label>
            <input type="text" 
                   id="nama_jenis_hewan" 
                   name="nama_jenis_hewan" 
                   class="form-control @error('nama_jenis_hewan') is-invalid @enderror" 
                   value="{{ old('nama_jenis_hewan', $jenis->nama_jenis_hewan) }}" 
                   required 
                   autofocus>

            @error('nama_jenis_hewan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection