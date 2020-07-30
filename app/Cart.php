<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $timestamps = false;
    protected $fillable = ['id_barang','id_konsumen', 'qty', 'harga_satuan', 'harga_total']; 
}
