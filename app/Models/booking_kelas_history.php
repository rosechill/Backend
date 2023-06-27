<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class booking_kelas_history extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table ="booking_kelas_history";
    /**
        * fillable
        *
        * @var array
    */
    
    protected $fillable = [
        'no_kelas_history',
        'id_booking_kelas',
        'tanggal',
        'sisa_deposit',
    ];

    public function booking_kelas()
    {
        return $this->belongsTo(booking_kelas::class, 'id_booking_kelas');
    }

}
