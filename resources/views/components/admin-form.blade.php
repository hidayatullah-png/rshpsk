{{-- resources/views/components/admin-form.blade.php --}}
@props(['title', 'action', 'backRoute', 'isEdit' => false])

<div class="main-container" style="max-width:600px; text-align:left;">
    <h2 style="text-align:center; margin-bottom:2rem; color:#3ea2c7;">
        {{ $title }}
    </h2>

    <form action="{{ $action }}" method="POST" class="form-layout">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- SLOT: Area ini akan diisi oleh Input Field dari View --}}
        {{ $slot }}

        <div class="form-actions">
            <a href="{{ $backRoute }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>

<style>
    /* --- Layout Form --- */
    .form-layout { display:flex; flex-direction:column; gap:1.5rem; }

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

    /* --- STYLE KHUSUS DROPDOWN (SELECT) --- */
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        /* Icon Panah Custom (SVG encoded) berwarna #3ea2c7 */
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233ea2c7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding-right: 2.5rem; /* Memberi ruang agar teks tidak menabrak panah */
    }

    /* --- STYLE KHUSUS TEXTAREA --- */
    textarea.form-control {
        min-height: 100px;
        resize: vertical; /* Agar hanya bisa ditarik ke bawah, tidak ke samping */
    }

    /* --- Pesan Error & Tombol --- */
    .error-message { color:#dc3545; font-size:0.875rem; margin-top:0.4rem; }
    
    .form-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem; }
    
    .btn:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    .btn-primary:hover { background-color:#2e8aa8; }
    .btn-secondary:hover { background-color:#e9e9e9; border-color:#ccc; }
    
    @media(max-width:480px){
        .form-actions { flex-direction:column-reverse; }
        .form-actions .btn { width:100%; justify-content:center; }
    }
</style>

<style>
    /* --- Layout Form --- */
    .form-layout { display:flex; flex-direction:column; gap:1.5rem; }

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
        color: #333; /* Warna teks input */
    }
    
    .form-control:focus { 
        outline:none; 
        border-color:#3ea2c7; 
        box-shadow:0 0 0 3px rgba(62,162,199,0.2); 
    }

    /* --- STYLE KHUSUS DROPDOWN (SELECT) --- */
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        /* Icon Panah Biru Custom */
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233ea2c7' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding-right: 2.5rem; 
    }

    /* --- STYLE ISI OPSI DROPDOWN (Baru Ditambahkan) --- */
    select.form-control option {
        background-color: #fff; /* Latar belakang putih */
        color: #333;            /* Teks hitam */
        padding: 15px;          /* Padding (Hanya jalan di beberapa browser) */
        font-size: 1rem;
    }

    /* Style untuk opsi pertama (-- Pilih Kategori --) yang disabled */
    select.form-control option:disabled {
        color: #999;
        font-style: italic;
        background-color: #f9f9f9;
    }

    /* --- STYLE KHUSUS TEXTAREA --- */
    textarea.form-control {
        min-height: 100px;
        resize: vertical; 
        font-family: inherit; /* Agar font sama dengan input lain */
    }

    /* --- Pesan Error & Tombol --- */
    .error-message { color:#dc3545; font-size:0.875rem; margin-top:0.4rem; }
    
    .form-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem; }
    
    .btn:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    .btn-primary:hover { background-color:#2e8aa8; }
    .btn-secondary:hover { background-color:#e9e9e9; border-color:#ccc; }
    
    @media(max-width:480px){
        .form-actions { flex-direction:column-reverse; }
        .form-actions .btn { width:100%; justify-content:center; }
    }
</style>