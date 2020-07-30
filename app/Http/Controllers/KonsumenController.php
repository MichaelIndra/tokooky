<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Konsumen;
use App\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumen = Konsumen::all();
 
        return $konsumen->toJson();
    }
 
    public function store(Request $request)
    {
        $validatedData = $request->validate([
          'nama_konsumen' => 'required',
        ]);
        $prefix = "KON".date('ym'); 
        $id = IdGenerator::generate(['table' => 'konsumens','field'=>'id_konsumen', 'length' => 15, 'prefix' =>$prefix]);
        $project = Konsumen::create([
            'id_konsumen' => $id,
            'nama_konsumen' => $validatedData['nama_konsumen'],
            'alamat_konsumen' => $request->input('alamat_konsumen'),
            'no_telp' => $request->input('no_telp'),
        ]);
 
        $msg = [
            'success' => true,
            'message' => 'Konsumen created successfully!'
        ];
 
        return response()->json($msg);
    }
 
    public function getKonsumen($id) // for edit and show
    {
        $konsumen = Konsumen::find($id);
 
        return $konsumen->toJson();
    }
 
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_konsumen' => 'required',
          ]);
 
        $konsumen = Konsumen::find($id);
        $konsumen->nama_konsumen = $validatedData['nama_konsumen'];
        $konsumen->alamat_konsumen = $request->input('alamat_konsumen');
        $konsumen->no_telp = $request->input('no_telp');
        $konsumen->save();
 
        $msg = [
            'success' => true,
            'message' => 'Konsumen updated successfully'
        ];
 
        return response()->json($msg);
    }
 
    public function delete($id)
    {
        $konsumen = Konsumen::find($id);
        if(!empty($konsumen)){
            $konsumen->delete();
            $msg = [
                'success' => true,
                'message' => 'Konsumen deleted successfully!'
            ];
            return response()->json($msg);
        } else {
            $msg = [
                'success' => false,
                'message' => 'Konsumen deleted failed!'
            ];
            return response()->json($msg);
        }
    }

    public function searchKonsumen(Request $request){
        if ( $request->input('showdata') ) {
    	    return Konsumen::orderBy('created_at', 'desc')->get();
            
    	}
        $columns = ['nama_konsumen', 'alamat_konsumen', 'id_konsumen','no_telp', 'created_at'];
        $length = $request->input('length');
        $column = $request->input('column'); 
        $search_input = $request->input('search');

        $query = Konsumen::select('nama_konsumen', 'alamat_konsumen', 'id_konsumen','no_telp', 'created_at')->orderBy($columns[$column]);

        if ($search_input) {
            $query->where(function($query) use ($search_input) {
                $query->where('nama_konsumen', 'like', '%' . $search_input . '%')
                ->orWhere('alamat_konsumen', 'like', '%' . $search_input . '%');
            });
        }

        $konsumen = $query->paginate($length);
        return ['data' => $konsumen];
    }
}
