<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class booking_gym_history extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="booking_gym_history";

    protected $fillable = [
        'no_booking_gym_history',
        'id_booking_gym',
        'tanggal',
    ];

    public function booking_gym()
    {
        return $this->belongsTo(booking_gym::class, 'id_booking_gym');
    }

}
