<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFloatQtyCartTransDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->float('qty', 8, 2)->change();;
        });

        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->float('qty', 8, 2)->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->integer('qty', 8, 2)->change();;
        });

        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->integer('qty', 8, 2)->change();;
        }); 
    }
}
