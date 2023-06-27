<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class kelas extends Model
{
    use HasFactory, HasFactory, Notifiable;
    protected $table ="kelas";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'nama_kelas',
        'harga',
    ];

    public function jadwal_umum()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }

    public function jadwal_harian()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }

    public function promo_kelas()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }

    public function deposit_kelas()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }
    public function tdeposit_kelas()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }
}
