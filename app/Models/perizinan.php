<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perizinan extends Model
{
    use HasFactory;
    /**
        * fillable
        *
        * @var array
    */

    protected $fillable = [
        'id_instruktur',
        'id_jadwal_harian',
        'no_perizinan',
        'nama_instruktur',
        'nama_pengganti',
        'izin_date',
        'alasan',
    ];}
