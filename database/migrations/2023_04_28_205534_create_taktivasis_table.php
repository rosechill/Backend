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
        Schema::create('taktivasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_taktivasi');
            $table->foreignId('id_member')->constrained('member')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pegawai')->constrained('pegawai')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            $table->integer('harga');
            $table->date('expire_date');
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
        Schema::dropIfExists('taktivasi');
    }
};
