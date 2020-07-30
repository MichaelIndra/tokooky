<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaBarang extends Model
{
    protected $primaryKey = 'no';
    protected $fillable = ['id_barang', 'id_konsumen', 'harga_satuan', 'harga_lusin',
     'status', 'tgl_awal', 'updated_at'];
    
    public function barang(){
        return $this->belongsTo('App\Barang', 'id_barang');
    }
}
