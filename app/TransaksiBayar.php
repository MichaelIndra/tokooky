<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiBayar extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'no_invoice';
    protected $fillable = ['no_invoice','total_tagihan', 'total_bayar', 'status'];
}
