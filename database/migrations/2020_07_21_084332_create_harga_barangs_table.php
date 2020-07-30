<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_barangs', function (Blueprint $table) {
            $table->bigInteger('id_barang');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('harga_lusin');
            $table->string('status', 1);//1 = true, 0 = false
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
        Schema::dropIfExists('harga_barangs');
    }
}
