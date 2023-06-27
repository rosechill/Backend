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
        Schema::create('booking_kelas_history', function (Blueprint $table) {
            $table->id();
            $table->string('no_kelas_history')->nullable();
            $table->foreignId('id_booking_kelas')->nullable()->constrained('booking_kelas')->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->float('sisa_deposit',10,0)->nullable();
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
        Schema::dropIfExists('booking_kelas_history');
    }
};
