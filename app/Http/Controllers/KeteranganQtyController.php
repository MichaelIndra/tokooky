<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KeteranganQty;

class KeteranganQtyController extends Controller
{
    public function store(Request $request)
    { 
        $project = KeteranganQty::create([
            'keterangan_qty' => $request->input('keterangan_qty'),              
        ]);
 
        $msg = [
            'success' => true,
            'message' => 'Keterangan QTY '.$request->input('keterangan_qty').' created successfully!'
        ];
 
        return response()->json($msg);
    }

    public function getAll(){
        $query  =KeteranganQty::orderBy('keterangan_qty')->get();
        return $query->toJson();
    }
}
