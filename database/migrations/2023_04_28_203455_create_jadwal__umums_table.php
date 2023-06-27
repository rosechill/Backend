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
        Schema::create('jadwal_umum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_instruktur')->constrained('instruktur')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('hari_jadwal');
            $table->integer('kapasitas');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
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
        Schema::dropIfExists('jadwal_umum');
    }
};
