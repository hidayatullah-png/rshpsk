@props(['title', 'action', 'backRoute', 'isEdit' => false])

{{-- Container Utama Form --}}
<div class="main-container" style="max-width: 600px; margin: 2rem auto; text-align: left; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.05);">
    
    {{-- Judul Form --}}
    <h2 style="text-align: center; margin-bottom: 2rem; color: #3ea2c7; font-weight: 700;">
        {{ $title }}
    </h2>

    <form action="{{ $action }}" method="POST" class="form-layout" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- SLOT: Area ini akan diisi oleh Input Field dari halaman View --}}
        {{ $slot }}

        {{-- Tombol Aksi (Simpan / Kembali) --}}
        <div class="form-actions">
            <a href="{{ $backRoute }}" class="btn btn-secondary">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                 {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>
        </div>
    </form>
</div>

{{-- CSS KHUSUS UNTUK FORM INI --}}
<style>
    /* --- Layout Form --- */
    .form-layout { display: flex; flex-direction: column; gap: 1.5rem; }

    /* --- Label Style --- */
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #555;
    }

    /* --- Input Text, Number, Password, dll --- */
    .form-control { 
        width:100%; 
        padding:12px 15px; 
        font-size:1rem; 
        border:1px solid #ddd; 
        border-radius:8px; 
        box-sizing:border-box; 
        transition:0.3s; 
        background-color: #fff;
    }
    
    .form-control:focus { 
        outline:none; 
        border-color:#3ea2c7; 
        box-shadow:0 0 0 3px rgba(62,162,199,0.2); 
    }

    /* --- Input Error State --- */
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5zM6 8.2a.6.6 0 110-1.2.6.6 0 010 1.2z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* --- STYLE KHUSUS DROPDOWN (SELECT) --- */
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233ea2c7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding-right: 2.5rem; 
    }

    select.form-control option { color: #333; padding: 10px; }
    select.form-control option:disabled { color: #999; background-color: #f9f9f9; }

    /* --- STYLE KHUSUS TEXTAREA --- */
    textarea.form-control {
        min-height: 120px;
        resize: vertical; 
    }

    /* --- Pesan Error & Tombol --- */
    .error-message { color: #dc3545; font-size: 0.875rem; margin-top: 0.4rem; display: block; }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem; }
    
    /* Tombol Style (Override Global jika perlu) */
    .btn { padding: 10px 24px; border-radius: 8px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.2s; }
    .btn:hover { transform: translateY(-2px); }
    .btn-primary { background-color: #3ea2c7; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-secondary:hover { background-color: #5a6268; }
    
    @media(max-width: 600px){
        .form-actions { flex-direction: column-reverse; }
        .btn { width: 100%; }
    }
</style>