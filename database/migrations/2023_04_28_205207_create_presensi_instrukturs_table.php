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
        Schema::create('presensi_instruktur', function (Blueprint $table) {
            $table->id();
            $table->string('no_presensi_instruktur');
            $table->foreignId('id_jadwal_harian')->constrained('jadwal_harian')->onUpdate('cascade')->onDelete('cascade');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->date('tgl_presensi');
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
        Schema::dropIfExists('presensi__instruktur');
    }
};
