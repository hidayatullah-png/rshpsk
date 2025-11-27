<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuDokter extends Model
{
    protected $table = 'temu_dokter';
    
    // Primary Key sesuai SQL
    protected $primaryKey = 'idreservasi_dokter'; 
    
    // Karena di SQL Anda pakai 'waktu_daftar' (timestamp), bukan created_at/updated_at bawaan Laravel
    public $timestamps = false; 

    protected $fillable = [
        'no_urut',
        'status',
        'idpet',
        'idrole_user' // Ini Foreign Key ke Dokter
    ];

    // ✅ Relasi ke Pet
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    // ✅ Relasi ke Dokter (via RoleUser)
    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }

    // Helper ambil data user dokter
    public function getDokterAttribute()
    {
        return $this->roleUser ? $this->roleUser->user : null;
    }

    // ✅ Relasi ke Rekam Medis
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'idreservasi_dokter', 'idreservasi_dokter');
    }
}