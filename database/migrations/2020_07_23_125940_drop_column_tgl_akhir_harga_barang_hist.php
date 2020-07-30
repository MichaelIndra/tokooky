<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnTglAkhirHargaBarangHist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('harga_barangs', function (Blueprint $table) {
            $table->dropColumn('tgl_akhir');
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
            $table->date('tgl_akhir')->nullable();
        });
    }
}
