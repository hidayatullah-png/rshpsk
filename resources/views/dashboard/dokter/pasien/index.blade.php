@extends('layouts.dokter.dokter')

@section('title', 'Data Pasien')

@section('content')

<div class="table-container">

    {{-- Judul Halaman --}}
    <div class="header-actions">
        <div>
            <h2 style="margin: 0; color: #3ea2c7; font-weight: 700;">Data Pasien</h2>
            <p style="color: #777; margin: 5px 0 0;">Daftar pemilik hewan dan peliharaan yang terdaftar di klinik.</p>
        </div>
    
    </div>

    {{-- Tabel Data Pasien --}}
    @if($pasien->isNotEmpty())
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="text-center" width="50">No</th>
                        <th width="25%">Informasi Pemilik</th>
                        <th width="30%">Informasi Hewan</th>
                        <th width="40%">Kontak & Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pasien as $p)
                        <tr>
                            {{-- NO --}}
                            <td class="text-center" style="font-weight: 600; vertical-align: middle;">
                                {{ $loop->iteration }}
                            </td>
                            
                            {{-- INFORMASI PEMILIK --}}
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 700; color: #333; font-size: 1rem; margin-bottom: 5px;">
                                    {{ $p->nama_pemilik }}
                                </div>
                                @if($p->email) 
                                    <div style="font-size: 0.85rem; color: #777; display: flex; align-items: center; gap: 5px;">
                                        <i class="far fa-envelope" style="color: #999;"></i> {{ Str::limit($p->email, 25) }}
                                    </div>
                                @endif
                            </td>

                            {{-- INFORMASI HEWAN --}}
                            <td style="vertical-align: middle;">
                                <div style="font-weight: 700; color: #3ea2c7; font-size: 1rem; margin-bottom: 5px;">
                                    {{ $p->nama_hewan }}
                                </div>
                                <div style="font-size: 0.85rem; color: #555; display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-paw" style="color: #ccc;"></i>
                                    <span style="font-style: italic;">
                                        {{ $p->nama_jenis_hewan ?? '-' }} / {{ $p->nama_ras ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            
                            {{-- ALAMAT & KONTAK --}}
                            <td style="vertical-align: middle;">
                                <div style="font-size: 0.9rem; color: #555; margin-bottom: 5px;">
                                    @if($p->no_wa) 
                                        <div style="display: flex; align-items: flex-start; gap: 8px;">
                                            <i class="fab fa-whatsapp text-success mt-1" style="opacity: 0.9;"></i> 
                                            <span>{{ $p->no_wa }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div style="font-size: 0.9rem; color: #555;">
                                    <div style="display: flex; align-items: flex-start; gap: 8px; color: #555;">
                                        <i class="fas fa-map-marker-alt text-danger mt-1" style="opacity: 0.7;"></i>
                                        <span style="line-height: 1.4;">{{ Str::limit($p->alamat, 50) ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        {{-- Empty State --}}
        <div style="text-align: center; padding: 4rem 2rem; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #ddd;">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p style="color: #999; margin: 0; font-size: 1.1rem;">Belum ada data pasien yang terdaftar.</p>
        </div>
    @endif

</div>

@endsection