<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class presensi_instruktur extends Model
{
    use HasFactory, HasFactory, Notifiable;

    protected $table ="presensi_instruktur";

    /**
        * fillable
        *
        * @var array
    */
    protected $fillable = [
        'no_presensi_instruktur',
        'id_jadwal_harian',
        'jam_mulai',
        'jam_selesai',
        'tgl_presensi',
    ];
    public function jadwal_harian()
    {
        return $this->belongsTo(jadwal_harian::class, 'id_jadwal_harian');
    }
}
