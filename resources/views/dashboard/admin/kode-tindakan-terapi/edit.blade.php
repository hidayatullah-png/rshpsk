@extends('layouts.admin.admin')

@section('title', 'Edit Kode Tindakan Terapi')

@section('content')
    {{-- Panggil Component admin-form --}}
    <x-admin-form 
        title="Edit Tindakan Terapi"
        :action="route('dashboard.admin.kode-tindakan-terapi.update', $tindakan->idkode_tindakan_terapi)"
        :back-route="route('dashboard.admin.kode-tindakan-terapi.index')"
        :is-edit="true"
    >
        {{-- 1. Input Kode Tindakan --}}
        <div class="form-group">
            <label for="kode">Kode Tindakan</label>
            <input type="text" 
                   id="kode" 
                   name="kode" 
                   class="form-control @error('kode') is-invalid @enderror" 
                   value="{{ old('kode', $tindakan->kode) }}" 
                   placeholder="Contoh: TRP-001"
                   required>
            
            @error('kode')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Input Deskripsi (Textarea) --}}
        <div class="form-group">
            <label for="deskripsi_tindakan_terapi">Deskripsi Tindakan</label>
            <textarea id="deskripsi_tindakan_terapi" 
                      name="deskripsi_tindakan_terapi" 
                      class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                      placeholder="Jelaskan detail tindakan terapi..."
                      rows="4"
                      required>{{ old('deskripsi_tindakan_terapi', $tindakan->deskripsi_tindakan_terapi) }}</textarea>
            
            @error('deskripsi_tindakan_terapi')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 3. Dropdown Kategori Umum --}}
        <div class="form-group">
            <label for="idkategori">Kategori Umum</label>
            <select id="idkategori" name="idkategori" class="form-control @error('idkategori') is-invalid @enderror" required>
                <option value="" disabled>-- Pilih Kategori --</option>
                @foreach ($kategoriList as $kat)
                    <option value="{{ $kat->idkategori }}" 
                        {{ old('idkategori', $tindakan->idkategori) == $kat->idkategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
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
                <option value="" disabled>-- Pilih Kategori Klinis --</option>
                @foreach ($kategoriKlinisList as $klinis)
                    <option value="{{ $klinis->idkategori_klinis }}" 
                        {{ old('idkategori_klinis', $tindakan->idkategori_klinis) == $klinis->idkategori_klinis ? 'selected' : '' }}>
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