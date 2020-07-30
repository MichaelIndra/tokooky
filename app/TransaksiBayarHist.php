<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiBayarHist extends Model
{
    protected $fillable = ['no_invoice','bayar'];
}
