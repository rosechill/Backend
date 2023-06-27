<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class tdeposit_reguler extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="tdeposit_reguler";


    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'no_struk_deposit_reguler',
        'id_member',
        'id_pegawai',
        'id_promo_reguler',
        'tanggal_transaksi',
        'jumlah_deposit',
        'bonus',
        'sisa_deposit',
        'total_deposit',
    ];

    public function member()
    {
        return $this->belongsTo(member::class, 'id_member');
    }

    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }

    public function promo_reguler()
    {
        return $this->belongsTo(promo_reguler::class, 'id_promo_reguler');
    }
}
