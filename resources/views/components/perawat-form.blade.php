@props(['title', 'action', 'backRoute', 'isEdit' => false])

{{-- Container Form (Max Width 800px agar pas untuk Rekam Medis) --}}
<div class="perawat-form-container">
    
    {{-- Header Form --}}
    <div class="form-header">
        <h2>{{ $title }}</h2>
        <p>Silakan lengkapi data pada form di bawah ini.</p>
    </div>

    {{-- Form Body --}}
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- SLOT: Tempat input field disuntikkan --}}
        {{ $slot }}

        {{-- Action Buttons --}}
        <div class="form-actions">
            <a href="{{ $backRoute }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>
        </div>
    </form>
</div>

{{-- Style Khusus Komponen Ini --}}
<style>
    /* Container Style */
    .perawat-form-container {
        max-width: 800px; /* Lebar optimal untuk form medis */
        margin: 2rem auto;
        background: white;
        padding: 2.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    /* Header Style */
    .form-header { text-align: center; margin-bottom: 2rem; border-bottom: 1px dashed #eee; padding-bottom: 1rem; }
    .form-header h2 { color: #3ea2c7; margin: 0; font-weight: 700; font-size: 1.5rem; }
    .form-header p { color: #888; margin-top: 5px; font-size: 0.9rem; }

    /* Form Elements Style */
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #444; }
    
    /* Input Style Global untuk Form Ini */
    .perawat-form-container .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        box-sizing: border-box;
        transition: 0.3s;
        font-size: 0.95rem;
    }

    .perawat-form-container .form-control:focus { 
        border-color: #3ea2c7; 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(62, 162, 199, 0.1); 
    }

    /* Error State */
    .is-invalid { border-color: #dc3545 !important; }
    .error-text { color: #dc3545; font-size: 0.85rem; margin-top: 5px; display: block; }

    /* Grid System Sederhana (Untuk Anamnesa & Temuan Klinis bersandingan) */
    .row-group { display: flex; gap: 1.5rem; }
    .half { flex: 1; }

    /* Buttons */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem; }
    
    .btn { padding: 10px 24px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.95rem; }
    .btn-primary { background: #3ea2c7; color: white; transition: 0.2s; }
    .btn-primary:hover { background: #2e8aa8; transform: translateY(-2px); }
    .btn-secondary { background: #f1f3f5; color: #555; transition: 0.2s; }
    .btn-secondary:hover { background: #e9ecef; }

    /* Responsive Mobile */
    @media (max-width: 600px) {
        .row-group { flex-direction: column; gap: 0; }
        .perawat-form-container { padding: 1.5rem; margin: 1rem; }
        .form-actions { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
    }
</style>