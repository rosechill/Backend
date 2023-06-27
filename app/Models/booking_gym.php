<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class booking_gym extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="booking_gym";

    protected $fillable = [
        'no_booking_gym',
        'id_gym',
        'id_member',
        'tanggal',
        'status',
    ];

    public function gym()
    {
        return $this->belongsTo(gym::class, 'id_gym');
    }

    public function member()
    {
        return $this->belongsTo(member::class, 'id_member');
    }

    public function booking_gym_history()
    {
        return $this->hasMany(booking_gym_history::class, 'id_booking_gym', 'id');
    }
}
