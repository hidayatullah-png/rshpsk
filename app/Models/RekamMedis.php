<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    public $timestamps = false;

    protected $fillable = [
        'idreservasi_dokter', // Penghubung utama ke data Pasien/Hewan
        // 'idpet', HAPUS INI (Tidak ada di tabel rekam_medis)
        'dokter_pemeriksa',   
        'anamnesa',
        'temuan_klinis',
        'diagnosa',
        'terapi',
        'created_at'
    ];

    // HAPUS atau Comment fungsi pet() ini karena idpet tidak ada di tabel ini
    /*
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }
    */

    // Relasi ke Dokter (User)
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_pemeriksa', 'iduser');
    }

    // PENTING: Relasi ke Temu Dokter untuk mengambil data Pet & Pemilik
    public function temuDokter()
    {
        return $this->belongsTo(TemuDokter::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }

    // Relasi ke Detail
    public function detailRekamMedis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
}