<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class tdeposit_kelas extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'no_struk_deposit_kelas',
        'id_promo_kelas',
        'id_kelas',
        'id_member',
        'id_pegawai',
        'tanggal_transaksi',
        'total_harga',
        'jumlah_deposit_kelas',
        'expire_date',
    ];


    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas');
    }

    public function member()
    {
        return $this->belongsTo(member::class, 'id_member');
    }
    
    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }

    public function promo_kelas()
    {
        return $this->belongsTo(instruktur::class, 'id_promo_kelas');
    }
}
