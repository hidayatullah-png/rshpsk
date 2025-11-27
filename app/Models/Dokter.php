<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel (karena tidak menggunakan plural 'dokters')
    protected $table = 'dokter';

    // 2. Tentukan Primary Key (karena bukan 'id')
    protected $primaryKey = 'id_dokter';

    // 3. Izinkan kolom ini untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'id_user',        // Foreign Key ke tabel user
        'alamat',
        'no_hp',
        'bidang_dokter',
        'jenis_kelamin',  // Enum/Varchar(1)
    ];

    /**
     * Relasi: Dokter "dimiliki oleh" (Belongs To) satu User.
     * FK: id_user, OwnerKey: iduser (di tabel user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}