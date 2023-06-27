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
        Schema::create('promo_reguler', function (Blueprint $table) {
            $table->id();
            $table->float('min_deposit', 10, 0);
            $table->float('min_topup', 10, 0);
            $table->float('bonus_uang', 10, 0);
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
        Schema::dropIfExists('promo_reguler');
    }
};
