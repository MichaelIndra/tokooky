<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = ['no_invoice','id_barang', 'qty', 'harga', 'total_harga', 'keterangan_qty'];
}
