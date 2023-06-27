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
        Schema::create('tdeposit_reguler', function (Blueprint $table) {
            $table->id();
            $table->string('no_struk_deposit_reguler');
            $table->foreignId('id_member')->constrained('member')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pegawai')->constrained('pegawai')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_promo_reguler')->nullable();
            $table->date('tanggal_transaksi');
            $table->float('jumlah_deposit', 10, 0);
            $table->float('bonus', 10, 0);
            $table->float('sisa_deposit', 10, 0)->nullable();
            $table->float('total_deposit', 10, 0);
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
        Schema::dropIfExists('tdeposit_reguler');
    }
};
