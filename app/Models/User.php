<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /** 🔹 Relasi many-to-many ke Role */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
            ->withPivot('status', 'idrole_user');
    }

    /** 🔹 Relasi one-to-many ke RoleUser */
    public function roleUsers()
    {
        return $this->hasMany(RoleUser::class, 'iduser', 'iduser');
    }

    /** 🔹 Relasi one-to-one ke Pemilik */
    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'iduser', 'iduser');
    }
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'iduser', 'iduser');
    }

    // Relasi ke Perawat
    public function perawat()
    {
        return $this->hasOne(Perawat::class, 'iduser', 'iduser');
    }
}