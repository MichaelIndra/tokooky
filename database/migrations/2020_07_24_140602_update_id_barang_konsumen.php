<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdBarangKonsumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->string('id_konsumen', 15)->change();
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->string('id_barang', 15)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('konsumens', function (Blueprint $table) {
            $table->bigIncrements('id_konsumen', 15)->change();
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->bigIncrements('id_barang', 15)->change();
        });
    }
}
