<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiGlobal extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'no_invoice';
    protected $fillable = ['no_invoice','id_konsumen', 'total_belanja', 'diskon', 'total_bersih', 'metode'];
}
