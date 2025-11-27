<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'warna_tanda',
        'jenis_kelamin',
        'idpemilik',
        'idras_hewan'
    ];

    // ✅ Relasi ke Pemilik
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    // ✅ Relasi ke Ras
    public function ras()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }

    // ✅ Relasi ke Rekam Medis (SOLUSI ERROR SQL)
    // Menggunakan hasManyThrough karena tabel rekam_medis tidak punya kolom idpet
    // Pet -> TemuDokter -> RekamMedis
    public function rekamMedis()
    {
        return $this->hasManyThrough(
            RekamMedis::class,      
            TemuDokter::class,     
            'idpet',                
            'idreservasi_dokter',   
            'idpet',                
            'idreservasi_dokter'    
        );
    }

    // ✅ Relasi ke Temu Dokter (SOLUSI ERROR HAPUS)
    // Digunakan untuk pengecekan withCount('temuDokter') di Controller
    public function temuDokter()
    {
        return $this->hasMany(TemuDokter::class, 'idpet', 'idpet');
    }
}