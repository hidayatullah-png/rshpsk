<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;

    // Hapus 'nama' dan 'email' dari sini
    protected $fillable = ['no_wa', 'alamat', 'iduser'];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }
}