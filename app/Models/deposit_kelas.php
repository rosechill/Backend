<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deposit_kelas extends Model
{
    use HasFactory;

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'no_deposit_kelas',
        'id_member',
        'id_kelas',
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

}
