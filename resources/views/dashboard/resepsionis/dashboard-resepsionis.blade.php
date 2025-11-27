@extends('layouts.resepsionis.resepsionis')

@section('title', 'Dashboard - Resepsionis')

@section('styles')
<style>
    .header {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        color: black;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    .badge {
        display: inline-block;
        background: #3ea2c7;
        color: white;
        font-weight: bold;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        margin-left: 0.3rem;
    }
</style>
@endsection

@section('content')

    <div class="header">
        <h2>Selamat datang di halaman khusus resepsionis</h2>
        
        @php
            // Mengambil data user (gunakan Auth::user() lebih disarankan jika sudah login)
            $nama_user = Auth::user()->name ?? session('user_name', 'User');
            // Untuk role, sesuaikan dengan cara Anda menyimpan role user
            $nama_role = 'Resepsionis'; 
        @endphp

        <p>
            Halo <b>{{ $nama_user }}</b>, anda login sebagai
            <span class="badge">{{ $nama_role }}</span>
        </p>
    </div>

    @endsection