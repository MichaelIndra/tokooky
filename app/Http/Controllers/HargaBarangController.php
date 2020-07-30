<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HargaBarang;
use Carbon\Carbon;
use App\Barang;
use App\HargaBarangHist;

class HargaBarangController extends Controller
{
    public function store(Request $request)
    {
        $msg = [
            'success' => false,
            'message' => 'Harga barang created failed!',
        ];

        $validatedData = $request->validate([  
          'id_barang' => 'required',
          'id_konsumen' => 'required',
          'harga_satuan' => 'required',
          'harga_lusin' => 'required',
          'tgl_awal' => 'required',
        ]);
        $validatedData['tgl_awal'] = \Carbon\Carbon::parse($validatedData['tgl_awal'])->format('Y-m-d');

        $hrgbrg = HargaBarang::where('id_barang',$validatedData['id_barang'])
                                ->where('id_konsumen',$validatedData['id_konsumen']);
        if($hrgbrg->count() > 0){
            $validatedData['tgl_akhir'] = \Carbon\Carbon::parse($validatedData['tgl_awal'])->format('Y-m-d');
            HargaBarangHist::create($validatedData);
            $validatedData['tgl_akhir'] = null;
            $hrgbrgupdt = HargaBarang::find($request->input('no'));
            $hrgbrgupdt->harga_satuan = $validatedData['harga_satuan'];
            $hrgbrgupdt->harga_lusin = $validatedData['harga_lusin'];
            $hrgbrgupdt->tgl_awal = $validatedData['tgl_awal'];
            $hrgbrgupdt->save();
            $msg = [
                'success' => true,
                'message' => 'Harga barang update successfully! totaly : ',
                'data' => $hrgbrgupdt
            ];
        }else{
            $validatedData['status'] = "1";
            HargaBarang::create($validatedData);
            $msg = [
                'success' => true,
                'message' => 'Harga barang created successfully! totaly : ',
                'data' => $validatedData
            ];
        }     
        return response()->json($msg);
    }

    public function getHargaBarang($id) // for edit and show
    {
        $hargabarang = HargaBarang::find($id);
 
        return $hargabarang->toJson();
    }

    
    
}
