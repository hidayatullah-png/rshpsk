@extends('layouts.admin.admin')

@section('title', 'Edit Role User')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Role User"
        :action="route('dashboard.admin.role-user.update', $roleUser->idrole_user)"
        :back-route="route('dashboard.admin.role-user.index')"
        :is-edit="true"
    >
        {{-- 1. Nama User (Sekarang Bisa Diedit) --}}
        <div class="form-group">
            <label for="nama" class="form-label">Nama User</label>
            <input type="text" 
                   id="nama"
                   name="nama"
                   class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama', $roleUser->user->nama) }}" 
                   required>
            
            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Email User (Sekarang Bisa Diedit) --}}
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   id="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $roleUser->user->email) }}" 
                   required>

            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Garis Pemisah --}}
        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 1.5rem 0;">

        {{-- 3. Pilihan Role (Dropdown) --}}
        <div class="form-group">
            <label for="idrole">Role / Jabatan <span style="color:red">*</span></label>
            <select id="idrole" name="idrole" class="form-control @error('idrole') is-invalid @enderror" required>
                <option value="" disabled>-- Pilih Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->idrole }}" 
                        {{ old('idrole', $roleUser->idrole) == $role->idrole ? 'selected' : '' }}>
                        {{ $role->nama_role }}
                    </option>
                @endforeach
            </select>
            
            @error('idrole')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="status" value="{{ $roleUser->status }}">

    </x-admin-form>
@endsection