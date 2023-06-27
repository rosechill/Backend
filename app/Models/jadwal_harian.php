<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class jadwal_harian extends Model
{
    use HasFactory;
    use HasFactory, HasFactory, Notifiable;
    protected $table ="jadwal_harian";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'id_instruktur',
        'id_kelas',
        'id_jadwal_umum',
        'tanggal',
        'kapasitas',
        'hari_jadwal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function instruktur()
    {
        return $this->belongsTo(instruktur::class, 'id_instruktur');
    }

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas');
    }

    public function jadwal_umum()
    {
        return $this->belongsTo(jadwal_umum::class, 'id_jadwal_umum');
    }

    public function instruktur_izin()
    {
        return $this->hasMany(instruktur_izin::class, 'id_jadwal_harian', 'id');
    }
    public function booking_kelas()
    {
        return $this->hasMany(booking_kelas::class, 'id_jadwal_harian', 'id');
    }

    public function presensi_instruktur()
    {
        return $this->hasMany(presensi_instruktur::class, 'id_jadwal_harian', 'id');
    }
}
