<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class instruktur_izin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table ="instruktur_izin";
    /**
        * fillable
        *
        * @var array
    */
    
    protected $fillable = [
        'id_instruktur',
        'id_instruktur_pengganti',
        'id_jadwal_harian',
        'alasan',
        'is_confirm',
    ];

    public function instruktur()
    {
        return $this->belongsTo(instruktur::class, 'id_instruktur');
    }

    public function instruktur_pengganti()
    {
        return $this->belongsTo(instruktur::class, 'id_instruktur_pengganti');
    }
    public function jadwal_harian()
    {
        return $this->belongsTo(jadwal_harian::class, 'id_jadwal_harian');
    }

}
