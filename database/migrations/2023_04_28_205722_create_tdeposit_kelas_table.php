a<?php

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
        Schema::create('tdeposit_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('no_struk_deposit_kelas');
            $table->foreignId('id_promo_kelas')->nullable();
            $table->foreignId('id_kelas')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_member')->constrained('member')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pegawai')->constrained('pegawai')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            $table->float('total_harga', 10, 0);
            $table->integer('jumlah_deposit_kelas');
            $table->date('expire_date')->nullable();
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
        Schema::dropIfExists('tdeposit_kelas');
    }
};
