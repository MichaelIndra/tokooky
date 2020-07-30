<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id_konsumen';
    protected $fillable = ['id_konsumen','nama_konsumen', 'alamat_konsumen', 'no_telp']; 

    public function hargabarangs(){
        return $this->hasMany('App\HargaBarang', 'id_konsumen', 'id_konsumen');
    }
}
