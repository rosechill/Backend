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
        Schema::create('instruktur', function (Blueprint $table) {
            $table->id();
            $table->string('no_instruktur');
            $table->string('nama_instruktur');
            $table->string('gender');
            $table->date('tanggal_lahir');
            $table->string('nomor_telp');
            $table->string('alamat');
            $table->string('username');
            $table->string('password');
            $table->time('jumlah_terlambat');
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
        Schema::dropIfExists('instruktur');
    }
};
