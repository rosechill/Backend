<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class taktivasi extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="taktivasi";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'no_taktivasi',
        'id_member',
        'id_pegawai',
        'tanggal_transaksi',
        'harga',
        'expire_date',
    ];

    public function member()
    {
        return $this->belongsTo(member::class, 'id_member');
    }

    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }
  
}
