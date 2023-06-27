<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class promo_kelas extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="promo_kelas";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'id_kelas',
        'jumlah_kelas',
        'bonus_kelas',
        'durasi_aktif',
    ];

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas');
    }

    public function tdeposit_kelas()
    {
        return $this->hasMany(kelas::class, 'id_kelas', 'id');
    }
}
