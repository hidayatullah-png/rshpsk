@extends('layouts.admin.admin')

@section('title', 'Edit Data Pet')

@section('content')

    {{-- Panggil Component Admin Form --}}
    <x-admin-form 
        title="Edit Data Pet" 
        action="{{ route('dashboard.admin.pet.update', $pet->idpet) }}" 
        backRoute="{{ route('dashboard.admin.pet.index') }}" 
        :isEdit="true"
    >
        
        {{-- 1. Nama Pet --}}
        <div>
            <label for="nama" style="font-weight:600; color:#555;">Nama Pet</label>
            <input type="text" id="nama" name="nama" 
                   class="form-control" 
                   value="{{ old('nama', $pet->nama) }}" required>
            @error('nama') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 2. Tanggal Lahir --}}
        <div>
            <label for="tanggal_lahir" style="font-weight:600; color:#555;">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                   class="form-control" 
                   value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}">
            @error('tanggal_lahir') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 3. Warna / Tanda --}}
        <div>
            <label for="warna_tanda" style="font-weight:600; color:#555;">Warna / Tanda</label>
            <input type="text" id="warna_tanda" name="warna_tanda" 
                   class="form-control" 
                   value="{{ old('warna_tanda', $pet->warna_tanda) }}">
            @error('warna_tanda') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 4. Jenis Kelamin --}}
        <div>
            <label for="jenis_kelamin" style="font-weight:600; color:#555;">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                <option value="" disabled>-- Pilih --</option>
                <option value="M" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'M' ? 'selected' : '' }}>Jantan</option>
                <option value="F" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'F' ? 'selected' : '' }}>Betina</option>
            </select>
            @error('jenis_kelamin') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 5. Pemilik --}}
        <div>
            <label for="idpemilik" style="font-weight:600; color:#555;">Pemilik</label>
            <select id="idpemilik" name="idpemilik" class="form-control" required>
                <option value="" disabled>-- Pilih Pemilik --</option>
                @foreach ($pemilikList as $pemilik)
                    <option value="{{ $pemilik->idpemilik }}" 
                        {{ old('idpemilik', $pet->idpemilik) == $pemilik->idpemilik ? 'selected' : '' }}>
                        {{ $pemilik->user->nama }}
                    </option>
                @endforeach
            </select>
            @error('idpemilik') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <hr style="border:0; border-top:1px dashed #ddd; margin:0.5rem 0;">

        {{-- 6. Jenis Hewan (Dropdown Induk) --}}
        <div>
            <label for="idjenis_hewan" style="font-weight:600; color:#555;">Jenis Hewan</label>
            <select id="idjenis_hewan" class="form-control" required>
                <option value="" disabled {{ !$currentJenisId ? 'selected' : '' }}>-- Pilih Jenis Hewan --</option>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis->idjenis_hewan }}" 
                        {{ (isset($currentJenisId) && $currentJenisId == $jenis->idjenis_hewan) ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis_hewan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 7. Ras Hewan (Dropdown Anak) --}}
        <div>
            <label for="idras_hewan" style="font-weight:600; color:#555;">Ras Hewan</label>
            <select id="idras_hewan" name="idras_hewan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Jenis Hewan Terlebih Dahulu --</option>
                
                {{-- Load data awal jika mode edit --}}
                @if(isset($rasList) && count($rasList) > 0)
                    @foreach ($rasList as $ras)
                        <option value="{{ $ras->idras_hewan }}"
                            {{ old('idras_hewan', $pet->idras_hewan) == $ras->idras_hewan ? 'selected' : '' }}>
                            {{ $ras->nama_ras }}
                        </option>
                    @endforeach
                @endif
            </select>
            
            {{-- Indikator Loading (Inline Style kecil saja) --}}
            <div id="loading-ras" style="display:none; color:#3ea2c7; font-size:0.85rem; margin-top:5px;">
                <i class="fas fa-spinner fa-spin"></i> Mengambil data ras...
            </div>
            
            @error('idras_hewan') <div class="error-message">{{ $message }}</div> @enderror
        </div>

    </x-admin-form>

    {{-- SCRIPT DEPENDENT DROPDOWN TETAP SAMA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisSelect = document.getElementById('idjenis_hewan');
            const rasSelect = document.getElementById('idras_hewan');
            const loadingText = document.getElementById('loading-ras');

            jenisSelect.addEventListener('change', function() {
                const idJenis = this.value;

                // Reset Ras
                rasSelect.innerHTML = '<option value="" disabled selected>-- Pilih Ras --</option>';
                rasSelect.disabled = true;

                if (idJenis) {
                    loadingText.style.display = 'block';
                    
                    fetch(`/dashboard/admin/api/get-ras/${idJenis}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Error');
                            return response.json();
                        })
                        .then(data => {
                            loadingText.style.display = 'none';
                            rasSelect.disabled = false;

                            if (data.length > 0) {
                                data.forEach(ras => {
                                    const option = document.createElement('option');
                                    option.value = ras.idras_hewan;
                                    option.textContent = ras.nama_ras;
                                    rasSelect.appendChild(option);
                                });
                            } else {
                                const option = document.createElement('option');
                                option.textContent = 'Tidak ada data ras';
                                rasSelect.appendChild(option);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            loadingText.style.display = 'none';
                            rasSelect.disabled = false;
                        });
                }
            });
        });
    </script>
@endsection