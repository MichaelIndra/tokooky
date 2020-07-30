<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiBayarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_bayars', function (Blueprint $table) {
            $table->string('no_invoice', 15);
            $table->bigInteger('total_tagihan');
            $table->bigInteger('total_bayar');
            $table->string('status',6);
            $table->timestamps();

            $table->primary('no_invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_bayars');
    }
}
