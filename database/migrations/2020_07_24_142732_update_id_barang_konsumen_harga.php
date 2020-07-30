<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdBarangKonsumenHarga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('harga_barangs', function (Blueprint $table) {
            $table->string('id_konsumen', 15)->change();
            $table->string('id_barang', 15)->change();
            $table->bigIncrements('no');
           
        });

        Schema::table('harga_barang_hists', function (Blueprint $table) {
            $table->string('id_konsumen', 15)->change();
            $table->string('id_barang', 15)->change();
            $table->bigIncrements('no');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('harga_barangs', function (Blueprint $table) {
            $table->bigInteger('id_konsumen', 15)->change();
            $table->bigInteger('id_barang', 15)->change();
            $table->dropPrimary('harga_barangs_no_primary');
            $table->dropColumn('no');
        });

        Schema::table('harga_barang_hists', function (Blueprint $table) {
            $table->bigInteger('id_konsumen', 15)->change();
            $table->bigInteger('id_barang', 15)->change();
            $table->dropPrimary('harga_barangs_no_primary');
            $table->dropColumn('no');
        });
    }
}
