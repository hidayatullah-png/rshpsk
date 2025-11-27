@extends('layouts.admin.admin')

@section('title', 'Edit Role')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Role"
        :action="route('dashboard.admin.role.update', $role->idrole)"
        :back-route="route('dashboard.admin.role.index')"
        :is-edit="true"
    >
        {{-- Input Nama Role --}}
        <div class="form-group">
            <label for="nama_role">Nama Role</label>
            <input type="text" 
                   id="nama_role" 
                   name="nama_role" 
                   class="form-control @error('nama_role') is-invalid @enderror" 
                   value="{{ old('nama_role', $role->nama_role) }}" 
                   placeholder="Contoh: Admin, Dokter, Staff..."
                   required 
                   autofocus>
            
            @error('nama_role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection