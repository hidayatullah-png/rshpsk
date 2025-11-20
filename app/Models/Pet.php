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

    // Pemilik
    public function pemilik()
    {
        // --- PERBAIKAN ---
        // Relasi Pet adalah ke model Pemilik, bukan User.
        // Kunci asing di tabel 'pet' adalah 'idpemilik'.
        // Kunci utama di tabel 'pemilik' adalah 'idpemilik'.
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    // Ras
    public function ras()
    {
        // Model RasHewan.php akan dibutuhkan nanti
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }

    // Rekam Medis
    public function rekamMedis()
    {
        // Model RekamMedis.php akan dibutuhkan nanti
        return $this->hasMany(RekamMedis::class, 'idpet', 'idpet');
    }
}