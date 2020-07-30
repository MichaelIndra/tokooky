<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaBarangHistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_barang_hists', function (Blueprint $table) {
            $table->bigInteger('id_barang');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('harga_lusin');
            $table->date('tgl_awal');
            $table->date('tgl_akhir')->nullable();
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
        Schema::dropIfExists('harga_barang_hists');
    }
}
