<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class gym extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="gym";

     /**
        * fillable
        *
        * @var array
    */
    
    protected $fillable = [
        'kapasitas',
        'tanggal',
        'start_gym',
        'end_gym',
    ];

    public function booking_gym()
    {
        return $this->hasMany(booking_gym::class, 'id_gym', 'id');
    }
}
