@props([
    'title',
    'action',
    'backRoute',
    'isEdit' => false,
])

<div class="form-container">
    <div class="form-header">
        <h2>{{ $title }}</h2>
        <p>{{ $isEdit ? 'Perbarui data dengan benar' : 'Silakan lengkapi data di bawah ini' }}</p>
    </div>

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{ $slot }}

        <div class="form-actions">
            <a href="{{ $backRoute }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
