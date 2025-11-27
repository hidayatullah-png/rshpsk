<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawat extends Model
{
    use HasFactory;

    // 1. Nama Tabel
    protected $table = 'perawat';

    // 2. Primary Key
    protected $primaryKey = 'id_perawat';

    // 3. Fillable
    protected $fillable = [
        'id_user',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'pendidikan',
    ];

    /**
     * Relasi: Perawat "dimiliki oleh" (Belongs To) satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}