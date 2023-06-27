<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class promo_reguler extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="promo_reguler";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'min_deposit',
        'min_topup',
        'bonus_uang',
    ];

    public function tdeposit_reguler()
    {
        return $this->hasMany(promo_reguler::class, 'id_promo_reguler', 'id');
    }
}
