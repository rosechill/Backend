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
        Schema::create('member', function (Blueprint $table) {
            $table->id();
            $table->string('no_member');
            $table->string('nama_member');
            $table->string('gender');
            $table->date('tanggal_lahir');
            $table->string('nomor_telp');
            $table->string('alamat');
            $table->string('username');
            $table->string('password');
            $table->float('jumlah_deposit_reguler', 10, 0)->nullable();
            $table->integer('status_membership');
            $table->date('masa_berlaku')->nullable();
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
        Schema::dropIfExists('member');
    }
};
