@extends('layouts.admin.admin')

@section('title', 'Tambah User & Role')

@section('content')
    <x-admin-form 
        title="Tambah User & Role"
        :action="route('dashboard.admin.role-user.store')"
        :back-route="route('dashboard.admin.role-user.index')"
    >
        {{-- === BAGIAN 1: AKUN LOGIN === --}}
        <div class="form-section-title">
            <i class="fas fa-user-lock"></i> Informasi Akun
        </div>

        <div class="form-group">
            <label for="nama">Nama User</label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama') }}" placeholder="Masukkan nama user" required autofocus>
            @error('nama') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email User</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" placeholder="Masukkan email user" required>
            @error('email') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Minimal 6 karakter" required>
            @error('password') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="idrole">Pilih Role <span style="color:red">*</span></label>
            {{-- Tambahkan ID 'roleSelect' untuk ditangkap JS --}}
            <select id="roleSelect" name="idrole" class="form-control @error('idrole') is-invalid @enderror" required>
                <option value="" disabled selected>-- Pilih Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->idrole }}" 
                            data-role-name="{{ strtolower($role->nama_role) }}" 
                            {{ old('idrole') == $role->idrole ? 'selected' : '' }}>
                        {{ $role->nama_role }}
                    </option>
                @endforeach
            </select>
            @error('idrole') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        {{-- === BAGIAN 2: FORM DINAMIS (DOKTER/PERAWAT) === --}}
        {{-- Container ini default-nya hidden (display: none) --}}
        <div id="extraFields" style="display: none;">
            
            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 2rem 0;">
            
            <div class="form-section-title">
                <i class="fas fa-id-card-alt"></i> Data Lengkap <span id="roleLabel"></span>
            </div>

            {{-- Field Umum (Alamat, No HP, Gender) --}}
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                    <option value="" disabled selected>-- Pilih Gender --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" 
                       value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                @error('no_hp') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="2" 
                          placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                @error('alamat') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            {{-- Field Khusus Dokter --}}
            <div id="fieldDokter" style="display: none;">
                <div class="form-group">
                    <label for="bidang_dokter">Bidang Dokter</label>
                    <input type="text" name="bidang_dokter" class="form-control" 
                           value="{{ old('bidang_dokter') }}" placeholder="Contoh: Umum, Gigi, Bedah">
                    @error('bidang_dokter') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Field Khusus Perawat --}}
            <div id="fieldPerawat" style="display: none;">
                <div class="form-group">
                    <label for="pendidikan">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" class="form-control" 
                           value="{{ old('pendidikan') }}" placeholder="Contoh: D3 Keperawatan, S1 Ners">
                    @error('pendidikan') <span class="error-message">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

    </x-admin-form>

    <style>
        .form-section-title {
            font-size: 1.1rem; font-weight: 600; color: #3ea2c7;
            margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
        }
    </style>

    {{-- Javascript untuk Show/Hide Form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const extraFields = document.getElementById('extraFields');
            const fieldDokter = document.getElementById('fieldDokter');
            const fieldPerawat = document.getElementById('fieldPerawat');
            const roleLabel = document.getElementById('roleLabel');

            // Fungsi untuk cek role
            function checkRole() {
                // Ambil text dari option yang dipilih (bukan value ID-nya)
                // dan ubah ke lowercase agar pencarian string lebih mudah
                const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                const roleName = selectedOption.getAttribute('data-role-name') || '';

                // Reset tampilan
                extraFields.style.display = 'none';
                fieldDokter.style.display = 'none';
                fieldPerawat.style.display = 'none';

                if (roleName.includes('dokter')) {
                    extraFields.style.display = 'block';
                    fieldDokter.style.display = 'block';
                    roleLabel.innerText = 'Dokter';
                } else if (roleName.includes('perawat')) {
                    extraFields.style.display = 'block';
                    fieldPerawat.style.display = 'block';
                    roleLabel.innerText = 'Perawat';
                }
            }

            // Jalankan saat dropdown berubah
            roleSelect.addEventListener('change', checkRole);

            // Jalankan saat halaman dimuat (untuk menghandle old input saat validasi gagal)
            checkRole();
        });
    </script>
@endsection