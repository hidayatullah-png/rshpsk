@extends('layouts.perawat.perawat')

@section('title', 'Tambah Rekam Medis')

@section('content')

    <x-perawat-form 
        title="Tambah Rekam Medis" 
        :action="route('dashboard.perawat.rekam-medis.store')"
        :back-route="route('dashboard.perawat.rekam-medis.index')"
    >
        
        {{-- === ISI FORM (SLOT) === --}}
        
        {{-- 1. Pilihan Pasien (Auto Select) --}}
        <div class="form-group">
            <label for="idreservasi_dokter">Pilih Pasien (Antrian) <span style="color:red">*</span></label>
            
            {{-- Jika ada selectedReservasi, kita kunci tampilannya (tapi tetap bisa di-submit) --}}
            <select name="idreservasi_dokter" id="idreservasi_dokter" 
                    class="form-control @error('idreservasi_dokter') is-invalid @enderror" 
                    required
                    @if(isset($selectedReservasi)) 
                        style="pointer-events: none; background-color: #e9ecef;" 
                    @endif
            >
                <option value="" disabled selected>-- Pilih Pasien dari Antrian --</option>
                
                @forelse($reservasiList as $res)
                    <option value="{{ $res->idreservasi_dokter }}" 
                        {{-- LOGIKA AUTO SELECT: Jika ID di URL sama dengan ID di List --}}
                        {{ (isset($selectedReservasi) && $selectedReservasi->idreservasi_dokter == $res->idreservasi_dokter) ? 'selected' : '' }}>
                        
                        {{ $res->nama_pet }} ({{ $res->nama_pemilik ?? 'Tanpa Pemilik' }}) - {{ \Carbon\Carbon::parse($res->waktu_daftar)->format('d M Y') }}
                    </option>
                @empty
                    <option value="" disabled>Tidak ada pasien dalam antrian.</option>
                @endforelse
            </select>
            
            @error('idreservasi_dokter')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. Dokter Pemeriksa (Auto Select) --}}
        <div class="form-group">
            <label for="dokter_pemeriksa">Dokter Pemeriksa <span style="color:red">*</span></label>
            
            <select name="dokter_pemeriksa" id="dokter_pemeriksa" 
                    class="form-control @error('dokter_pemeriksa') is-invalid @enderror" 
                    required
                    {{-- Kunci jika dokter sudah ditentukan di reservasi --}}
                    @if(isset($selectedReservasi) && $selectedReservasi->id_user_dokter) 
                        style="pointer-events: none; background-color: #e9ecef;" 
                    @endif
            >
                <option value="" disabled selected>-- Pilih Dokter --</option>
                @foreach($dokter as $d)
                    <option value="{{ $d->iduser }}" 
                        {{-- LOGIKA AUTO SELECT DOKTER --}}
                        {{ (old('dokter_pemeriksa') == $d->iduser) || (isset($selectedReservasi) && $selectedReservasi->id_user_dokter == $d->iduser) ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                @endforeach
            </select>
            
            @error('dokter_pemeriksa')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="divider-dashed"></div>

        {{-- 3. Anamnesa & Temuan Klinis --}}
        <div class="row-group">
            <div class="form-group half">
                <label for="anamnesa">Anamnesa (Keluhan)</label>
                <textarea name="anamnesa" id="anamnesa" rows="4" class="form-control" placeholder="Catatan keluhan awal pemilik...">{{ old('anamnesa') }}</textarea>
            </div>
            <div class="form-group half">
                <label for="temuan_klinis">Temuan Klinis</label>
                <textarea name="temuan_klinis" id="temuan_klinis" rows="4" class="form-control" placeholder="Hasil pemeriksaan fisik hewan...">{{ old('temuan_klinis') }}</textarea>
            </div>
        </div>

        {{-- 4. Diagnosa --}}
        <div class="form-group">
            <label for="diagnosa">Diagnosa <span style="color:red">*</span></label>
            <input type="text" name="diagnosa" id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror" value="{{ old('diagnosa') }}" placeholder="Contoh: Scabies, Feline Panleukopenia, dll" required>
            @error('diagnosa')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        {{-- 5. Tindakan / Terapi (MULTI SELECT DROPDOWN) --}}
        <div class="form-group">
            <label for="tindakan">Tindakan / Terapi</label>
            <select name="tindakan[]" id="tindakan" class="form-control" multiple style="height: 200px;">
                @foreach($listTindakan as $t)
                    <option value="{{ $t->idkode_tindakan_terapi }}"
                        {{ in_array($t->idkode_tindakan_terapi, old('tindakan', [])) ? 'selected' : '' }}>
                        {{ $t->kode }} - {{ $t->deskripsi_tindakan_terapi }}
                    </option>
                @endforeach
            </select>
            <small style="color: #666; display: block; margin-top: 5px;">
                💡 Klik untuk pilih multiple items. Dropdown akan tetap terbuka.
            </small>
        </div>
    </x-perawat-form>

    {{-- Select2 CSS & JS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tindakan').select2({
                placeholder: 'Pilih satu atau lebih tindakan...',
                allowClear: true,
                width: '100%',
                closeOnSelect: false,
                minimumResultsForSearch: 0
            });
        });
    </script>

@endsection