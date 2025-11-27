@extends('layouts.resepsionis.resepsionis')

@section('title', 'Antrian Temu Dokter')

@section('content')

<x-temu-dokter />
<div class="main-container">
    
    {{-- 1. HEADER & FILTER TANGGAL --}}
    <h2>
        <span><i class="fas fa-calendar-check"></i> Antrian Dokter</span>
        
        <div class="header-actions">
            <form method="get" style="display: flex; gap: 10px; align-items: center;">
                <label style="font-size: 0.9rem; font-weight: 600; color: #555;">Tanggal:</label>
                <input type="date" name="date" value="{{ $selectedDate }}" 
                       class="form-control" style="width: auto; padding: 8px;" 
                       onchange="this.form.submit()">
            </form>
        </div>
    </h2>

    {{-- 3. FORM TAMBAH ANTRIAN --}}
    <div class="input-section">
        <h4><i class="fas fa-plus-circle"></i> Daftar Pasien Baru (Hari Ini)</h4>
        
        <form method="POST" action="{{ route('dashboard.resepsionis.temu-dokter.store') }}" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            @csrf
            
            {{-- Pilih Hewan --}}
            <div style="flex-grow: 1; min-width: 250px;">
                <label style="font-weight: 500; color: #555; display:block; margin-bottom: 5px;">Pilih Hewan / Pemilik</label>
                <select name="idpet" required class="form-control">
                    <option value="" disabled selected>— Cari Nama Hewan / Pemilik —</option>
                    @foreach ($allPets as $p)
                        <option value="{{ $p->idpet }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Dokter --}}
            <div style="flex-grow: 1; min-width: 250px;">
                <label style="font-weight: 500; color: #555; display:block; margin-bottom: 5px;">Pilih Dokter</label>
                <select name="idrole_user" required class="form-control">
                    <option value="" disabled selected>— Pilih Dokter Tujuan —</option>
                    @foreach ($doctors as $d)
                        <option value="{{ $d->idrole_user }}">
                            drh. {{ $d->nama_dokter }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="height: 45px;">
                <i class="fas fa-paper-plane"></i> Ambil Antrian
            </button>
        </form>
    </div>

    {{-- 4. TABEL ANTRIAN --}}
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="text-align:center; width: 60px;">No</th>
                    <th>Waktu</th>
                    <th>Pasien (Hewan)</th>
                    <th>Dokter Tujuan</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center; width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($antrian as $a)
                    <tr>
                        <td style="text-align:center;">
                            <span style="background:#e0f7fa; color:#006064; padding:5px 10px; border-radius:50%; font-weight:bold; font-size:0.9rem;">
                                {{ $a->no_urut }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($a->waktu_daftar)->format('H:i') }}</td>
                        <td>
                            <strong style="color:#333;">{{ $a->nama_pet }}</strong>
                        </td>
                        
                        {{-- Nama Dokter --}}
                        <td>
                            @if($a->nama_dokter)
                                drh. {{ $a->nama_dokter }}
                            @else
                                <span style="color: #ccc;">-</span>
                            @endif
                        </td>

                        {{-- STATUS (MENGGUNAKAN STRING) --}}
                        <td style="text-align:center;">
                            @if($a->status == 'In-line')
                                <span style="background:#fff3cd; color:#856404; padding:4px 10px; border-radius:20px; font-size:0.8rem; font-weight:600;">Menunggu</span>
                            @elseif($a->status == 'Selesai')
                                <span style="background:#d1e7dd; color:#0f5132; padding:4px 10px; border-radius:20px; font-size:0.8rem; font-weight:600;">Selesai</span>
                            @elseif($a->status == 'Batal')
                                <span style="background:#f8d7da; color:#842029; padding:4px 10px; border-radius:20px; font-size:0.8rem; font-weight:600;">Batal</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td style="text-align:center;">
                            @if($a->status == 'In-line')
                                {{-- Tombol Selesai (Kirim string "Selesai") --}}
                                <form method="POST" action="{{ route('dashboard.resepsionis.temu-dokter.update', $a->idreservasi_dokter) }}" style="display:inline;">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Selesai">
                                    <button class="btn btn-green btn-sm" title="Selesai">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                {{-- Tombol Batal (Kirim string "Batal") --}}
                                <form method="POST" action="{{ route('dashboard.resepsionis.temu-dokter.update', $a->idreservasi_dokter) }}" style="display:inline;" onsubmit="return confirm('Batalkan antrian ini?')">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Batal">
                                    <button class="btn btn-red btn-sm" title="Batal">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @else
                                <span style="color:#ccc; font-size:1.2rem;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                            Belum ada antrian untuk tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection