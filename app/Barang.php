<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id_barang';
    protected $fillable = ['id_barang','nama_barang', 'harga_barang']; 

    public function hargabarangs(){
        return $this->hasMany('App\HargaBarang', 'id_barang', 'id_barang');
    }
}
