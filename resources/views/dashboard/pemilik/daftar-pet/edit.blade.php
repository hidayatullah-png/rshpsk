@extends('layouts.pemilik.pemilik')

@section('title', 'Edit Hewan Peliharaan')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            <h2>Edit Data Hewan</h2>
            <span style="color: #777;">Perbarui informasi lengkap hewan peliharaan Anda.</span>
        </div>
        {{-- Tombol Kembali --}}
        <a href="{{ route('dashboard.pemilik.daftar-pet.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Form Container (Menggunakan style info-card agar konsisten dengan halaman lain) --}}
    <div class="info-card">
        
        <form action="{{ route('dashboard.pemilik.daftar-pet.update', $pet->idpet) }}" method="POST">
            @csrf
            @method('PUT') {{-- Method Spoofing untuk Update --}}

            {{-- Grid Layout agar form lebih rapi --}}
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">

                {{-- Nama Hewan --}}
                <div class="form-group">
                    <label for="nama">Nama Hewan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="nama" id="nama" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $pet->nama) }}" 
                               placeholder="Masukkan nama hewan..." required>
                    </div>
                    @error('nama')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Baris: Jenis Kelamin & Tanggal Lahir --}}
                <div class="row-group">
                    {{-- Jenis Kelamin --}}
                    <div class="form-group half">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="Jantan" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                            <option value="Betina" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'Betina' ? 'selected' : '' }}>Betina</option>
                        </select>
                        @error('jenis_kelamin')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="form-group half">
                        <label for="tanggal_lahir">Tanggal Lahir (Perkiraan)</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                               class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}">
                        <small class="text-muted" style="font-size: 0.8rem;">*Kosongkan jika lupa/tidak tahu</small>
                        @error('tanggal_lahir')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Jenis & Ras Hewan --}}
                <div class="form-group">
                    <label for="idras_hewan">Jenis & Ras Hewan <span class="text-danger">*</span></label>
                    <select name="idras_hewan" id="idras_hewan" class="form-control @error('idras_hewan') is-invalid @enderror" required>
                        @foreach($ras as $r)
                            <option value="{{ $r->idras_hewan }}" {{ old('idras_hewan', $pet->idras_hewan) == $r->idras_hewan ? 'selected' : '' }}>
                                {{ $r->nama_jenis_hewan }} - {{ $r->nama_ras }}
                            </option>
                        @endforeach
                    </select>
                    @error('idras_hewan')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Warna / Tanda --}}
                <div class="form-group">
                    <label for="warna_tanda">Warna / Tanda Khusus</label>
                    <input type="text" name="warna_tanda" id="warna_tanda" 
                           class="form-control @error('warna_tanda') is-invalid @enderror" 
                           value="{{ old('warna_tanda', $pet->warna_tanda) }}" 
                           placeholder="Contoh: Belang tiga, Ekor pendek, dll">
                    @error('warna_tanda')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="divider-dashed"></div>

            {{-- Action Buttons --}}
            <div class="form-actions">
                <a href="{{ route('dashboard.pemilik.daftar-pet.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection