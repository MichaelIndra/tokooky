<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaBarangHist extends Model
{
    protected $primaryKey = 'no';
    protected $fillable = ['id_barang',  'id_konsumen', 'harga_satuan', 'harga_lusin', 'tgl_awal', 'tgl_akhir'];
}
