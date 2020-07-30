<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnKustomerHargaBarangHist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('harga_barang_hists', function (Blueprint $table) {
            $table->bigInteger('id_konsumen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('harga_barang_hists', function (Blueprint $table) {
            $table->dropColumn('id_konsumen');
        });
    }
}
