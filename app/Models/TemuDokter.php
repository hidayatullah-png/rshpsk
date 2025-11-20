<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuDokter extends Model
{
    protected $table = 'temu_dokter';
    protected $primaryKey = 'idtemu_dokter';
    public $timestamps = false;

    protected $fillable = [
        'idpet',
        'iduser',
        'jadwal',
        'status'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
