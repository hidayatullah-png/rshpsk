<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;

    protected $fillable = ['no_wa', 'alamat', 'iduser'];

    // One to One ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    // One to Many ke Pet
    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }
}