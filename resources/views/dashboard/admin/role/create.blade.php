@extends('layouts.admin.admin')

@section('title', 'Tambah Role')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Tambah Role"
        :action="route('dashboard.admin.role.store')"
        :back-route="route('dashboard.admin.role.index')"
    >
        {{-- Input Nama Role --}}
        <div class="form-group">
            <label for="nama_role">Nama Role</label>
            <input type="text" 
                   id="nama_role" 
                   name="nama_role" 
                   class="form-control @error('nama_role') is-invalid @enderror" 
                   value="{{ old('nama_role') }}" 
                   placeholder="Contoh: Admin, Kasir, Dokter"
                   required 
                   autofocus>
            
            @error('nama_role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection