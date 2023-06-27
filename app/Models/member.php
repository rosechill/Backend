<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class member extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
        * fillable
        *
        * @var array
    */

    protected $table ="member";

    protected $fillable = [
        'no_member',
        'nama_member',
        'gender',
        'tanggal_lahir',
        'nomor_telp',
        'alamat',
        'username',
        'password',
        'jumlah_deposit_reguler',
        'status_membership',
        'masa_berlaku',
    ];

    public function tdeposit_reguler()
    {
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function taktivasi()
    {
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function tdeposit_kelas()
    {
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function booking_kelas()
    {
        return $this->hasMany(member::class, 'id_member', 'id');
    }
    public function booking_gym()
    {
        return $this->hasMany(booking_gym::class, 'id_gym', 'id');
    }
}
