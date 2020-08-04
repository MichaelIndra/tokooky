<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use App\TransaksiGlobal;
use App\TransaksiDetail;
use App\TransaksiBayar;
use App\TransaksiBayarHist;
use App\Cart;
use DB;
use PDF;

class TransaksiGlobalController extends Controller
{
    public function store(Request $request){
        $status = false;
        $msg = "gagal insert transaksi";

        $prefix = "INV-".date('ymd')."-"; 
        $config = [
            'table' => 'transaksi_globals',
            'field'=>'no_invoice', 
            'length' => 15, 
            'prefix' =>$prefix,
            'reset_on_prefix_change' => true
        ];
        $no_invoice = IdGenerator::generate($config);
        $id_konsumen = Cart::all()->first()->id_konsumen;
        $total_belanja = Cart::sum('harga_total');
        $diskon = $request->input('diskon');
        $total_bersih = $total_belanja - $diskon;
        $metode = $request->input('bayar') >= $total_bersih ? 'LUNAS' : 'HUTANG';
        $transGlb = [
            'no_invoice' => $no_invoice,
            'id_konsumen' => $id_konsumen,
            'total_belanja' =>$total_belanja,
            'diskon' => $diskon,
            'total_bersih' => $total_bersih,
            'metode' =>$metode,
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
        ];
        $transDet = [];
        foreach (Cart::all() as $det){
            $transDet[] = [
                'no_invoice' => $no_invoice,
                'id_barang' => $det->id_barang,
                'qty' => $det->qty,
                'harga'=> $det->harga_satuan, 
                'total_harga'=> $det->harga_total,
                'keterangan_qty' => $det->keterangan_qty,
                "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            ]; 
        }
        $transByr = [
            'no_invoice' => $no_invoice,
            'total_tagihan' => $total_bersih, 
            'total_bayar' => $request->input('bayar'), 
            'status' => $metode,
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
        ];
        $transByrHist = [
            'no_invoice' => $no_invoice,
            'bayar' => $request->input('bayar'), 
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
        ];

        TransaksiGlobal::insert($transGlb);
        TransaksiDetail::insert($transDet);
        TransaksiBayar::insert($transByr);
        TransaksiBayarHist::insert($transByrHist);
        app('App\Http\Controllers\CartController')->deleteAll();

        $status = true;
        $msg = "berhasil insert transaksi";
        $dt = [
            'status' =>$status,
            'msg' =>$msg,
        ];
        return response()->json($dt);
    }

    public function get5Transaksi(){
        return DB::table('transaksi_globals')
            ->join('konsumens', 'transaksi_globals.id_konsumen', '=', 'konsumens.id_konsumen')
            ->select('transaksi_globals.no_invoice', 'konsumens.nama_konsumen' ,'transaksi_globals.total_belanja', 
                     'transaksi_globals.created_at')
            ->orderBy('transaksi_globals.created_at', 'desc')
            ->skip(0)
            ->take(5)
            ->get();
            
    }

    public function getAllTransaksi(Request $request){
        if ( $request->input('showdata') ) {
    	    return DB::table('transaksi_globals')
            ->join('transaksi_bayars', 'transaksi_globals.no_invoice', '=', 'transaksi_bayars.no_invoice')
            ->join('konsumens', 'transaksi_globals.id_konsumen', '=', 'konsumens.id_konsumen')
            ->select('transaksi_globals.no_invoice', 'konsumens.nama_konsumen' ,'transaksi_globals.total_belanja', 
                    'transaksi_globals.diskon', 'transaksi_globals.total_bersih', 'transaksi_bayars.total_bayar',
                    'transaksi_bayars.status', 'transaksi_globals.created_at')
            ->orderBy('transaksi_globals.created_at')
            ->get();
            
    	}  

        $columns = ['transaksi_globals.no_invoice', 'konsumens.nama_konsumen' ,'transaksi_globals.total_belanja', 
                    'transaksi_globals.diskon', 'transaksi_globals.total_bersih', 'transaksi_bayars.total_bayar',
                    'transaksi_bayars.status', 'transaksi_globals.created_at'];
        $length = $request->input('length');
        $column = $request->input('column'); 
        $search_input = $request->input('search');

        
        $query = DB::table('transaksi_globals')
                    ->join('transaksi_bayars', 'transaksi_globals.no_invoice', '=', 'transaksi_bayars.no_invoice')
                    ->join('konsumens', 'transaksi_globals.id_konsumen', '=', 'konsumens.id_konsumen')
                    ->select('transaksi_globals.no_invoice', 'konsumens.nama_konsumen' ,'transaksi_globals.total_belanja', 
                            'transaksi_globals.diskon', 'transaksi_globals.total_bersih', 'transaksi_bayars.total_bayar',
                            'transaksi_bayars.status', 'transaksi_globals.created_at')
                    ->orderBy($columns[$column]);

        if ($search_input) {
            $query->where(function($query) use ($search_input) {
                $query->where('transaksi_globals.no_invoice', 'like', '%' . $search_input . '%')
                        ->orWhere('konsumens.nama_konsumen', 'like', '%' . $search_input . '%');
            });
        }
        $data = $query->paginate($length);
        return ['data' => $data];
    }

    public function getAllTransaksiDetail($no_invoice){
        $query = DB::table('transaksi_details')
                    ->join('barangs', 'transaksi_details.id_barang', '=', 'barangs.id_barang')
                    ->select('barangs.nama_barang', 'transaksi_details.qty' ,'transaksi_details.harga', 
                            'transaksi_details.keterangan_qty','transaksi_details.total_harga')
                    ->where('transaksi_details.no_invoice',$no_invoice)        
                    ->get();
        return response()->json($query);             
    }

    public function bayarInvoice($no_invoice, $totalbayar){
        $status = false;
        $msg = "gagal update bayar";
        $transByrHist = [
            'no_invoice' => $no_invoice,
            'bayar' => $totalbayar, 
        ];
        TransaksiBayarHist::create($transByrHist);
        
        $sumbayar = TransaksiBayarHist::where('no_invoice', $no_invoice)
                                     ->sum('bayar');
        $transbyr = TransaksiBayar::find($no_invoice);    
        $totaltagihan = $transbyr->total_tagihan;
        $metode = $sumbayar >= $totaltagihan ? 'LUNAS' : 'HUTANG';
        $transbyr->total_bayar = $sumbayar;
        $transbyr->status = $metode;
        $transbyr->save();
        
        $status = true;
        $msg = "berhasil update bayar";
        $msg = [
            'status' =>$status,
            'message'=>$msg,
            
        ];
        return response()->json($msg);             
    }

    public function printInvoice($no_invoice){
        $dataglb = DB::table('transaksi_globals')
            ->join('transaksi_bayars', 'transaksi_globals.no_invoice', '=', 'transaksi_bayars.no_invoice')
            ->join('konsumens', 'transaksi_globals.id_konsumen', '=', 'konsumens.id_konsumen')
            ->select('transaksi_globals.no_invoice', 'konsumens.nama_konsumen' , 'konsumens.alamat_konsumen', 'konsumens.no_telp',
                    'transaksi_globals.total_belanja', 'transaksi_globals.diskon', 'transaksi_globals.total_bersih',
                    'transaksi_bayars.total_bayar', 'transaksi_bayars.status', 'transaksi_globals.created_at')
            ->where('transaksi_bayars.no_invoice',$no_invoice)
            ->orderBy('transaksi_globals.created_at')
            ->first();
        $datadet = DB::table('transaksi_details')
                ->join('barangs', 'transaksi_details.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.nama_barang', 'transaksi_details.qty' ,'transaksi_details.harga', 
                        'transaksi_details.total_harga', 'transaksi_details.keterangan_qty')
                ->where('transaksi_details.no_invoice',$no_invoice)
            ->get();    

        $msg=[
            'glb' => $dataglb,
            'det' => $datadet
        ];
        $pdf = PDF::loadview('printinvoice',['glb'=>$dataglb, 'det'=>$datadet])->setPaper('letter', 'portrait');
        // return $pdf->download($no_invoice.'.pdf');
        return $pdf->stream();
    }
    
}
