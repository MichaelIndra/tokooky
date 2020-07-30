<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use DB;

class CartController extends Controller
{
    public function store(Request $request){
        
        $status = false;
        $message ='';
        $cart = Cart::where('id_konsumen', $request->input('id_konsumen'));
        $cartawal = Cart::All();
        if($cart->count() == 0 && $cartawal->count() > 0)
        {
            $status = false;
            $message = 'Konsumen berbeda. Hapus semua keranjang terlebih dahulu untuk mengganti konsumen';
        }else{
            $cart = Cart::where('id_barang', $request->input('id_barang'));
            if($cart->count() > 0){
                $data = $cart->first();
                $finddata = Cart::find($data->id);
                $finddata->qty = $data->qty + $request->input('qty');
                $finddata->harga_total =  ($data->qty + $request->input('qty')) * $data->harga_satuan;
                $finddata->save();
                $status = true;
                $message = 'Berhasil update keranjang';
            }else{
                $savecart ['id_barang'] = $request->input('id_barang');
                $savecart ['id_konsumen'] = $request->input('id_konsumen');
                $savecart ['qty'] = $request->input('qty');
                $savecart ['harga_satuan'] = $request->input('harga_satuan');
                $savecart ['harga_total'] = $request->input('qty') * $request->input('harga_satuan');
                Cart::create($savecart);
                $status = true;
                $message = 'Berhasil tambah keranjang';
            }
        }
        $msg = [
            'success' => $status,
            'message' => $message,
            'data' => $this->getAllData(),
            'total' => Cart::sum('harga_total'),
        ];
        return response()->json($msg);

    }

    public function update($id, $qty){
        $status = false;
        $message ='';
        $finddata = Cart::find($id);
        if($qty > 0){
            $finddata->qty = $qty;
            $finddata->harga_total =  $qty * $finddata->harga_satuan;
            $finddata->save();
            $status = true;
            $message = 'Berhasil update keranjang';
            $msg = [
                'success' => $status,
                'message' => $message,
                'data' => $this->getAllData(),
                'total' => Cart::sum('harga_total'),
            ];
            return response()->json($msg);
        }else{
            $this->delete($id);
        }
        
    }

    public function delete($id){
        $status = false;
        $message ='';
        $finddata = Cart::find($id);
        if(!empty($finddata)){
            $finddata->delete();
            $status = true;
            $message ='Berhasil hapus keranjang';
        }
        $msg = [
            'success' => $status,
            'message' => $message,
            'data' => $this->getAllData(),
            'total' => Cart::sum('harga_total'),
        ];
        return response()->json($msg);
    }

    public function deleteAll(){
        $status = false;
        $message ='';
            DB::table('carts')->delete();
            Cart::truncate();
            DB::statement("SET foreign_key_checks=0");
            $status = true;
            $message ='Berhasil hapus semua keranjang';
        
        $msg = [
            'success' => $status,
            'message' => $message,
        ];
        return response()->json($msg);
    }

    private function getAllData(){
        $this->deleteAll();
        return DB::table('carts')
            ->join('barangs', 'carts.id_barang', '=', 'barangs.id_barang')
            ->select('barangs.nama_barang', 'carts.qty', 'carts.harga_satuan', 'carts.harga_total', 'carts.id')
            ->orderBy('barangs.nama_barang')
            ->get();
    }

    public function getAll(){
        $msg = [
            'data' => $this->getAllData(),
            'total' => Cart::sum('harga_total'),
        ];
        return response()->json($msg);
    }

    
}
