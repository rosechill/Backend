<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class jadwal_umum extends Model
{
    use HasFactory, HasFactory, Notifiable;
    protected $table ="jadwal_umum";
    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'id_instruktur',
        'id_kelas',
        'hari_jadwal',
        'kapasitas',
        'jam_mulai',
        'jam_selesai',
    ];

    public function instruktur()
    {
        return $this->belongsTo(instruktur::class, 'id_instruktur');
    }

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas');
    }

    public function jadwal_harian()
    {
        return $this->belongsTo(jadwal_harian::class, 'id_jadwal_harian', 'id');
    }
}
