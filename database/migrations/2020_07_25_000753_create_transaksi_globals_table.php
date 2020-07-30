<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiGlobalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_globals', function (Blueprint $table) {
            $table->string('no_invoice', 15);
            $table->string('id_konsumen',15);
            $table->bigInteger('total_belanja');
            $table->bigInteger('diskon');
            $table->bigInteger('total_bersih');
            $table->string('metode',6);// LUNAS/HUTANG
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
        Schema::dropIfExists('transaksi_globals');
    }
}
