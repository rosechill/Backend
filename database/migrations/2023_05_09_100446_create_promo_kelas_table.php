<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas');
            $table->integer('jumlah_kelas');
            $table->integer('bonus_kelas');
            $table->integer('durasi_aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_kelas');
    }
};
