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
        Schema::create('instruktur_izin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_instruktur');
            $table->foreignId('id_instruktur_pengganti');
            $table->foreignId('id_jadwal_harian');
            $table->string('alasan');
            $table->integer('is_confirm');
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
        Schema::dropIfExists('instruktur_izin');
    }
};
