<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $primaryKey = 'idrole_user';
    public $timestamps = false;

    protected $fillable = ['iduser', 'idrole', 'status'];

    /**
     * Mendapatkan data User dari entri pivot ini.
     */
    public function user()
    {
        // Argumen ke-2: foreign key di 'role_user'
        // Argumen ke-3: primary key di 'user'
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    /**
     * Mendapatkan data Role dari entri pivot ini.
     * Ini adalah relasi yang dipanggil oleh 'roleUsers.role'
     */
    public function role()
    {
        // Argumen ke-2: foreign key di 'role_user'
        // Argumen ke-3: primary key di 'role'
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}