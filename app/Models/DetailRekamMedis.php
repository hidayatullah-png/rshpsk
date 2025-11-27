<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRekamMedis extends Model
{
    // Sesuai dengan CREATE TABLE `detail_rekam_medis`
    protected $table = 'detail_rekam_medis';
    
    // Sesuai dengan PRIMARY KEY (`iddetail_rekam_medis`)
    protected $primaryKey = 'iddetail_rekam_medis';
    
    // Karena di tabel tidak ada created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'idrekam_medis',          // FK ke tabel rekam_medis
        'idkode_tindakan_terapi', // FK ke tabel kode_tindakan_terapi
        'detail'                  // Kolom detail (varchar 1000)
    ];

    // Relasi ke induk Rekam Medis
    // Constraint SQL: FOREIGN KEY (`idrekam_medis`) REFERENCES `rekam_medis`
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }

    // Relasi ke Kode Tindakan
    // Constraint SQL: FOREIGN KEY (`idkode_tindakan_terapi`) REFERENCES `kode_tindakan_terapi`
    public function kodeTindakan()
    {
        return $this->belongsTo(KodeTindakanTerapi::class, 'idkode_tindakan_terapi', 'idkode_tindakan_terapi');
    }
}