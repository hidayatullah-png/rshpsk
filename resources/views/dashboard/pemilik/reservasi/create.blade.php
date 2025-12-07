@extends('layouts.pemilik.pemilik')

@section('title', 'Buat Reservasi Baru')

@section('content')

<div class="detail-container">

    {{-- Header Halaman --}}
    <div class="detail-header">
        <div>
            <h2>Ajukan Reservasi</h2>
            <span style="color: #777;">Isi formulir di bawah untuk membuat janji temu dengan dokter.</span>
        </div>
        
        {{-- Tombol Kembali --}}
        <a href="{{ route('dashboard.pemilik.reservasi.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Container --}}
    <div class="info-card">
        
        <form action="{{ route('dashboard.pemilik.reservasi.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">

                {{-- Pilih Hewan --}}
                <div class="form-group">
                    <label for="idpet">Pilih Hewan Peliharaan <span class="text-danger">*</span></label>
                    <select name="idpet" id="idpet" class="form-control @error('idpet') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Hewan yang akan diperiksa --</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>
                                {{ $pet->nama }} ({{ $pet->jenis_hewan ?? 'Hewan' }})
                            </option>
                        @endforeach
                    </select>
                    @if($pets->isEmpty())
                        <small class="text-danger mt-1 d-block">
                            <i class="fas fa-exclamation-circle"></i> Anda belum mendaftarkan hewan peliharaan. 
                            <a href="{{ route('dashboard.pemilik.daftar-pet.create') }}">Daftar di sini</a>.
                        </small>
                    @endif
                    @error('idpet')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pilih Dokter (Wajib sesuai Controller) --}}
                <div class="form-group">
                    <label for="iddokter">Pilih Dokter <span class="text-danger">*</span></label>
                    <select name="iddokter" id="iddokter" class="form-control @error('iddokter') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Dokter --</option>
                        @foreach($dokter as $d)
                            <option value="{{ $d->iduser }}" {{ old('iddokter') == $d->iduser ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('iddokter')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Baris Tanggal --}}
                <div class="form-group">
                    <label for="tanggal_periksa">Rencana Tanggal Temu <span class="text-danger">*</span></label>
                    {{-- Name diganti jadi tanggal_periksa sesuai controller --}}
                    <input type="date" name="tanggal_periksa" id="tanggal_periksa" 
                           class="form-control @error('tanggal_periksa') is-invalid @enderror" 
                           value="{{ old('tanggal_periksa') }}" 
                           min="{{ date('Y-m-d') }}" required>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Jam operasional klinik: Senin - Sabtu (08:00 - 20:00).
                    </small>
                    @error('tanggal_periksa')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Input Keluhan DIHAPUS karena tidak ada di database --}}

            </div>

            <div class="divider-dashed"></div>

            {{-- Action Buttons --}}
            <div class="form-actions">
                <a href="{{ route('dashboard.pemilik.reservasi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary" {{ $pets->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection