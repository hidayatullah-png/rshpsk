@extends('layouts.perawat.perawat')

@section('title', 'Data Pasien')

@section('content')

<div class="table-container">

    {{-- Header Halaman --}}
    <div class="header-actions">
        <div style ="margin-bottom: 2rem;">
            <h2 style="color: #3ea2c7; border-bottom: 2px solid #eee; padding-bottom: 10px;"><i class="fas fa-user-injured"></i> Data Pasien</h2>
            <p style="color: #777; margin: 5px 0 0;">Daftar pemilik hewan dan peliharaan terdaftar.</p>
        </div>
    </div>

    
    @if($pasien->isNotEmpty())
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="text-center" width="50">No</th>
                        <th width="30%">Informasi Pemilik</th>
                        <th width="30%">Informasi Hewan</th>
                        <th width="35%">Alamat Domisili</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pasien as $p)
                        <tr>
                            {{-- No --}}
                            <td class="text-center" style="font-weight: 600;">{{ $loop->iteration }}</td>
                            
                            {{-- Pemilik --}}
                            <td>
                                <div style="font-weight: 700; color: #333; font-size: 1rem;">
                                    {{ $p->nama_pemilik }}
                                </div>
                                <div style="font-size: 0.85rem; color: #777; margin-top: 4px;">
                                    @if($p->no_wa) 
                                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 2px;">
                                            <i class="fab fa-whatsapp text-success"></i> {{ $p->no_wa }}
                                        </div>
                                    @endif
                                    @if($p->email) 
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <i class="far fa-envelope" style="color: #999;"></i> {{ Str::limit($p->email, 25) }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Hewan --}}
                            <td>
                                <div style="font-weight: 700; color: #3ea2c7; font-size: 1rem;">
                                    {{ $p->nama_hewan }}
                                </div>
                                <div style="font-size: 0.85rem; color: #555; margin-top: 4px; display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-paw" style="color: #ccc;"></i>
                                    <span>
                                        {{ $p->nama_jenis_hewan ?? '-' }} <span style="color: #ccc;">|</span> {{ $p->nama_ras ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Alamat --}}
                            <td>
                                <div style="display: flex; align-items: flex-start; gap: 8px; color: #555;">
                                    <i class="fas fa-map-marker-alt text-danger" style="margin-top: 3px; opacity: 0.7;"></i>
                                    <span style="line-height: 1.4;">{{ Str::limit($p->alamat, 60) ?: '-' }}</span>
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