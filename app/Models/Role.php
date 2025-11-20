<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'idrole';
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    /**
     * Relasi many-to-many ke User (via tabel pivot 'role_user')
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser')
                    ->withPivot('status');
    }

    /**
     * Relasi one-to-many ke Model Pivot 'RoleUser'
     * Ini penting untuk controller.
     */
    public function roleUsers()
    {
        // Satu Role bisa ada di banyak baris 'role_user'
        return $this->hasMany(RoleUser::class, 'idrole', 'idrole');
    }
}