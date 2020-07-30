<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;
use App\HargaBarang;
use App\Konsumen;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BarangController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
          'nama_barang' => 'required',
        ]);
        $prefix = "BRG".date('ym'); 
        $id = IdGenerator::generate(['table' => 'barangs','field'=>'id_barang', 'length' => 15, 'prefix' =>$prefix]);
 
        $project = Barang::create([
            'id_barang' => $id,    
          'nama_barang' => $validatedData['nama_barang'],
          'deskripsi_barang' => $request->input('deskripsi_barang'),
          
        ]);
 
        $msg = [
            'success' => true,
            'message' => 'Master barang '.$validatedData['nama_barang'].' created successfully!'
        ];
 
        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required',
          ]);
 
        $barang = Barang::find($id);
        $barang->nama_barang = $validatedData['nama_barang'];
        $barang->deskripsi_barang = $request->input('deskripsi_barang');

        $barang->save();
 
        $msg = [
            'success' => true,
            'message' => 'Master barang '.$validatedData['nama_barang'].' updated successfully'
        ];
 
        return response()->json($msg);
    }

    public function getNamaBarang(Request $request){
        $id_konsumen = $request->input('id_konsumen');
        $cekhrgbrg = HargaBarang::All()->count();
        if($cekhrgbrg > 0){
            $barang = Barang::select('id_barang','nama_barang')
                    ->whereNotIn('id_barang', 
                        HargaBarang::select('id_barang')  
                        ->where('id_konsumen', $id_konsumen)      
                        ->get()->toArray())
                    ->get();
        }else{
            $barang = Barang::All();
        }
        
    
        return $barang->toJson();
        // $konsumen = Konsumen::select('id_konsumen', 'nama_konsumen')

    }
    
    public function getBarangAndHarga($id_konsumen){
        return DB::table('barangs')
            ->join('harga_barangs', 'barangs.id_barang', '=', 'harga_barangs.id_barang')
            ->select('barangs.id_barang', 'barangs.nama_barang', 'harga_barangs.harga_satuan', 'harga_barangs.harga_lusin', 'harga_barangs.id_konsumen')
            ->where('harga_barangs.status','1')
            ->where('harga_barangs.id_konsumen', $id_konsumen)
            ->orderBy('barangs.nama_barang')
            ->get();
    }

    public function getBarang($id) // for edit and show
    {
        $barang = Barang::find($id);
 
        return $barang->toJson();
    }

    public function getAllBarang(Request $request){

        if ( $request->input('showdata') ) {
    	    return DB::table('barangs')
            ->join('harga_barangs', 'barangs.id_barang', '=', 'harga_barangs.id_barang')
            ->join('konsumens', 'harga_barangs.id_konsumen', '=', 'konsumens.id_konsumen')
            ->select('barangs.id_barang', 'barangs.nama_barang', 'konsumens.nama_konsumen' ,'harga_barangs.harga_satuan', 'harga_barangs.harga_lusin', 'harga_barangs.tgl_awal', 'harga_barangs.no')
            ->where('harga_barangs.status','1')
            ->orderBy('barangs.nama_barang')
            ->get();
            
    	}  

        $columns = ['barangs.id_barang', 'barangs.nama_barang', 'konsumens.nama_konsumen' , 'harga_barangs.harga_satuan', 'harga_barangs.harga_lusin', 'harga_barangs.tgl_awal'];
        $length = $request->input('length');
        $column = $request->input('column'); 
        $search_input = $request->input('search');

        
        $query = DB::table('barangs')
                    ->join('harga_barangs', 'barangs.id_barang', '=', 'harga_barangs.id_barang')
                    ->join('konsumens', 'harga_barangs.id_konsumen', '=', 'konsumens.id_konsumen')
                    ->select('barangs.id_barang', 'barangs.nama_barang', 'konsumens.nama_konsumen' ,'harga_barangs.harga_satuan', 'harga_barangs.harga_lusin', 'harga_barangs.tgl_awal', 'harga_barangs.no')
                    ->where('harga_barangs.status','1')
                    ->orderBy($columns[$column]);

        if ($search_input) {
            $query->where(function($query) use ($search_input) {
                $query->where('barangs.nama_barang', 'like', '%' . $search_input . '%')
                        ->orWhere('konsumens.nama_konsumen', 'like', '%' . $search_input . '%');
            });
        }
        $barang = $query->paginate($length);
        return ['data' => $barang];
    }
}

// $barangs = HargaBarang::
// select('harga_satuan', 'id_barang', 'harga_lusin')
// ->where('status', '1')
// ->where('tgl_akhir', null)
// ->with('barang:id_barang,nama_barang')
// ->whereHas('barang', function($sql) use($search_input){
//     $sql->where('nama_barang', 'LIKE', '%'.$search_input.'%');
// })                
// ->get();