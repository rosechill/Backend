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
        Schema::create('booking_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('no_booking_kelas');
            $table->foreignId('id_jadwal_harian')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_member')->constrained('kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal');
            $table->boolean('status');
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
        Schema::dropIfExists('booking_kelas');
    }
};
