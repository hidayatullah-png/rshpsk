@extends('layouts.admin.admin')

@section('title', 'Tambah Kode Tindakan Terapi')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Tambah Kode Tindakan Terapi"
        :action="route('dashboard.admin.kode-tindakan-terapi.store')"
        :back-route="route('dashboard.admin.kode-tindakan-terapi.index')"
    >
        {{-- 1. Input Kode --}}
        <div class="form-group">
            <label for="kode">Kode Tindakan</label>
            <input type="text" 
                   id="kode" 
                   name="kode" 
                   class="form-control @error('kode') is-invalid @enderror" 
                   value="{{ old('kode') }}" 
                   placeholder="Masukkan kode (contoh: TRP-001)"
                   required 
                   autofocus>
            
            @error('kode')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Input Deskripsi (Menggunakan Textarea agar lebih luas) --}}
        <div class="form-group">
            <label for="deskripsi_tindakan_terapi">Deskripsi Tindakan Terapi</label>
            <textarea id="deskripsi_tindakan_terapi" 
                      name="deskripsi_tindakan_terapi" 
                      class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                      placeholder="Masukkan deskripsi tindakan terapi..."
                      rows="4"
                      required>{{ old('deskripsi_tindakan_terapi') }}</textarea>
            
            @error('deskripsi_tindakan_terapi')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 3. Dropdown Kategori Umum --}}
        <div class="form-group">
            <label for="idkategori">Kategori Umum</label>
            <select id="idkategori" name="idkategori" class="form-control @error('idkategori') is-invalid @enderror" required>
                <option value="" disabled selected>-- Pilih Kategori --</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori->idkategori }}" {{ old('idkategori') == $kategori->idkategori ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            
            @error('idkategori')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 4. Dropdown Kategori Klinis --}}
        <div class="form-group">
            <label for="idkategori_klinis">Kategori Klinis</label>
            <select id="idkategori_klinis" name="idkategori_klinis" class="form-control @error('idkategori_klinis') is-invalid @enderror" required>
                <option value="" disabled selected>-- Pilih Kategori Klinis --</option>
                @foreach($kategoriKlinisList as $klinis)
                    <option value="{{ $klinis->idkategori_klinis }}" {{ old('idkategori_klinis') == $klinis->idkategori_klinis ? 'selected' : '' }}>
                        {{ $klinis->nama_kategori_klinis }}
                    </option>
                @endforeach
            </select>
            
            @error('idkategori_klinis')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

    </x-admin-form>
@endsection