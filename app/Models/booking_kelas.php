<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class booking_kelas extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table ="booking_kelas";
    /**
        * fillable
        *
        * @var array
    */
    
    protected $fillable = [
        'no_booking_kelas',
        'id_jadwal_harian',
        'id_member',
        'tanggal',
        'status',
    ];

    public function jadwal_harian()
    {
        return $this->belongsTo(jadwal_harian::class, 'id_jadwal_harian');
    }

    public function member()
    {
        return $this->belongsTo(member::class, 'id_member');
    }
    public function booking_kelas_history()
    {
        return $this->hasMany(booking_kelas_history::class, 'id_booking_kelas', 'id');
    }


}
