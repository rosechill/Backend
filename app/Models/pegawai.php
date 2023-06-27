<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class pegawai extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table ="pegawai";

    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'no_pegawai',
        'nama_pegawai',
        'role',
        'gender',
        'tanggal_lahir',
        'nomor_telp',
        'alamat',
        'username',
        'password',
    ];

    public function tdeposit_reguler()
    {
        return $this->hasMany(pegawai::class, 'id_pegawai', 'id');
    }
    public function taktivasi()
    {
        return $this->hasMany(pegawai::class, 'id_pegawai', 'id');
    }
    
    public function tdeposit_kelas()
    {
        return $this->hasMany(pegawai::class, 'id_pegawai', 'id');
    }
}
