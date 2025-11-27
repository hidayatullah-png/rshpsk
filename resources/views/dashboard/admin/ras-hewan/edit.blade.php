@extends('layouts.admin.admin')

@section('title', 'Edit Ras Hewan')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Ras Hewan"
        :action="route('dashboard.admin.ras-hewan.update', $ras->idras_hewan)"
        :back-route="route('dashboard.admin.ras-hewan.index')"
        :is-edit="true"
    >
        {{-- 1. Dropdown Jenis Hewan --}}
        <div class="form-group">
            <label for="idjenis_hewan">Jenis Hewan</label>
            <select id="idjenis_hewan" name="idjenis_hewan" class="form-control @error('idjenis_hewan') is-invalid @enderror" required>
                <option value="" disabled>-- Pilih Jenis Hewan --</option>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis->idjenis_hewan }}" 
                        {{-- Logika Selected: Cek input lama ATAU data database --}}
                        {{ old('idjenis_hewan', $ras->idjenis_hewan) == $jenis->idjenis_hewan ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis_hewan }}
                    </option>
                @endforeach
            </select>
            
            @error('idjenis_hewan')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Input Nama Ras --}}
        <div class="form-group">
            <label for="nama_ras">Nama Ras</label>
            <input type="text" 
                   id="nama_ras" 
                   name="nama_ras" 
                   class="form-control @error('nama_ras') is-invalid @enderror" 
                   value="{{ old('nama_ras', $ras->nama_ras) }}" 
                   placeholder="Contoh: Persia, Anggora, Bulldog..."
                   required>
            
            @error('nama_ras')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection