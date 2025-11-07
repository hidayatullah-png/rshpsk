@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">

                    <!-- ambil sesi yang sudah disimpan saat login -->
                    <div class="card-header">
                        {{ __('Dashboard') }} - {{ session('user_name') ?? 'Guest' }}
                    </div>

                    <div class="card-body">
                        {{-- ✅ Tampilkan pesan sukses login --}}
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ✅ Tampilkan status akun --}}
                        <p>Status Akun:
                            @if (session('user_status') == 1 || session('user_status') === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </p>

                        {{-- ✅ Informasi User --}}
                        <p>Nama: <strong>{{ session('user_name') ?? '-' }}</strong></p>
                        <p>Email: {{ session('user_email') ?? '-' }}</p>
                        <p>Role: <span class="badge bg-info text-dark">{{ session('user_role_name') ?? 'User' }}</span></p>

                        {{-- ✅ Informasi login --}}
                        <hr>
                        <p>{{ __('You are logged in!') }}</p>

                        {{-- ✅ Menu sesuai role --}}
                        @if (session('user_role') == 1)
                            {{-- Menu Admin --}}
                            <h5>Menu Admin</h5>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.jenis-hewan.index') }}">🐾 Kelola Jenis Hewan</a></li>
                                <li><a href="{{ route('admin.pemilik.index') }}">👤 Kelola Pemilik</a></li>
                            </ul>

                        @elseif (session('user_role') == 2)
                            {{-- Menu Pemilik --}}
                            <h5>Menu Pemilik</h5>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('pemilik.hewan.index') }}">🐶 Daftar Hewan Saya</a></li>
                                <li><a href="{{ route('pemilik.profil.index') }}">⚙️ Profil Saya</a></li>
                            </ul>
                        @else
                            <h5>Menu Umum</h5>
                            <p>Anda belum memiliki hak akses khusus.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection