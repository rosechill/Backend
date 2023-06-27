<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class instruktur extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table ="instruktur";
    /**
        * fillable
        *
        * @var array
    */
    
    protected $fillable = [
        'no_instruktur',
        'nama_instruktur',
        'gender',
        'tanggal_lahir',
        'nomor_telp',
        'alamat',
        'username',
        'password',
        'jumlah_terlambat'
    ];

    public function jadwal_umum()
    {
        return $this->hasMany(instruktur::class, 'id_instruktur', 'id');
    }

    public function jadwal_harian()
    {
        return $this->hasMany(instruktur::class, 'id_instruktur', 'id');
    }

    public function tdeposit_kelas()
    {
        return $this->hasMany(instruktur::class, 'id_instruktur', 'id');
    }

    public function instruktur_izin()
    {
        return $this->hasMany(instruktur_izin::class, 'id_instruktur', 'id');
    }

    public function instruktur_izin_pengganti()
    {
        return $this->hasMany(instruktur_izin::class, 'id_instruktur_pengganti', 'id');
    }
}
