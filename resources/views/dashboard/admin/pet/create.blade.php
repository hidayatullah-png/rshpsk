@extends('layouts.admin.admin')

@section('title', 'Tambah Pet Baru')

@section('content')

    {{-- Panggil Component Admin Form --}}
    <x-admin-form 
        title="Tambah Pet Baru" 
        action="{{ route('dashboard.admin.pet.store') }}" 
        backRoute="{{ route('dashboard.admin.pet.index') }}"
    >
        
        {{-- 1. Nama Pet --}}
        <div>
            <label for="nama" style="font-weight:600; color:#555;">Nama Pet</label>
            <input type="text" id="nama" name="nama" 
                   class="form-control" 
                   value="{{ old('nama') }}" required placeholder="Masukkan nama hewan">
            @error('nama') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 2. Tanggal Lahir --}}
        <div>
            <label for="tanggal_lahir" style="font-weight:600; color:#555;">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                   class="form-control" 
                   value="{{ old('tanggal_lahir') }}">
            @error('tanggal_lahir') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 3. Warna / Tanda --}}
        <div>
            <label for="warna_tanda" style="font-weight:600; color:#555;">Warna / Tanda</label>
            <input type="text" id="warna_tanda" name="warna_tanda" 
                   class="form-control" 
                   value="{{ old('warna_tanda') }}" placeholder="Contoh: Putih, Belang Tiga">
            @error('warna_tanda') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 4. Jenis Kelamin --}}
        <div>
            <label for="jenis_kelamin" style="font-weight:600; color:#555;">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                <option value="" disabled selected>-- Pilih Gender --</option>
                
                {{-- Kirim string "Jantan" ke controller --}}
                <option value="Jantan" {{ old('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                
                {{-- Kirim string "Betina" ke controller --}}
                <option value="Betina" {{ old('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>Betina</option>
            </select>
            @error('jenis_kelamin') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        {{-- 5. Pemilik --}}
        <div>
            <label for="idpemilik" style="font-weight:600; color:#555;">Pemilik</label>
            <select id="idpemilik" name="idpemilik" class="form-control" required>
                <option value="" disabled selected>-- Pilih Pemilik --</option>
                @foreach ($pemilikList as $p)
                    <option value="{{ $p->idpemilik }}" {{ old('idpemilik') == $p->idpemilik ? 'selected' : '' }}>
                        {{ $p->user->nama ?? 'Tanpa Nama' }}
                    </option>
                @endforeach
            </select>
            @error('idpemilik') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <hr style="border:0; border-top:1px dashed #ddd; margin:0.5rem 0;">

        {{-- 6. Jenis Hewan (Parent Dropdown) --}}
        <div>
            <label for="idjenis_hewan" style="font-weight:600; color:#555;">Jenis Hewan</label>
            <select id="idjenis_hewan" class="form-control" required>
                <option value="" disabled selected>-- Pilih Jenis Hewan --</option>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis_hewan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 7. Ras Hewan (Child Dropdown) --}}
        <div>
            <label for="idras_hewan" style="font-weight:600; color:#555;">Ras Hewan</label>
            <select id="idras_hewan" name="idras_hewan" class="form-control" required disabled>
                <option value="" disabled selected>-- Pilih Jenis Hewan Terlebih Dahulu --</option>
                {{-- Opsi akan diisi oleh JavaScript --}}
            </select>

            <div id="loading-ras" style="display:none; color:#3ea2c7; font-size:0.85rem; margin-top:5px;">
                <i class="fas fa-spinner fa-spin"></i> Mengambil data ras...
            </div>

            @error('idras_hewan') <div class="error-message">{{ $message }}</div> @enderror
        </div>

    </x-admin-form>

    {{-- SCRIPT DEPENDENT DROPDOWN (Sama dengan Edit, tapi tanpa load awal) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisSelect = document.getElementById('idjenis_hewan');
            const rasSelect = document.getElementById('idras_hewan');
            const loadingText = document.getElementById('loading-ras');

            // Event saat Jenis Hewan dipilih
            jenisSelect.addEventListener('change', function() {
                const idJenis = this.value;

                // Reset Ras
                rasSelect.innerHTML = '<option value="" disabled selected>-- Pilih Ras --</option>';
                rasSelect.disabled = true;

                if (idJenis) {
                    loadingText.style.display = 'block';
                    
                    // Panggil API
                    fetch(`/dashboard/admin/api/get-ras/${idJenis}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal mengambil data');
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
                                option.textContent = 'Tidak ada ras untuk jenis ini';
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