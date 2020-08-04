<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'konsumens'], function(){
    Route::get('/', ['uses'=>'KonsumenController@index', 'as'=>'konsumen.index']);
    Route::post('/store', ['uses'=>'KonsumenController@store', 'as'=>'konsumen.store']);
    Route::get('/edit/{id}', ['uses'=>'KonsumenController@getKonsumen', 'as'=>'konsumen.edit']);
    Route::get('/show/{id}', ['uses'=>'KonsumenController@getKonsumen', 'as'=>'konsumen.show']);
    Route::put('/update/{id}', ['uses'=>'KonsumenController@update', 'as'=>'konsumen.update']);
    Route::delete('/delete/{id}', ['uses'=>'KonsumenController@delete', 'as'=>'konsumen.delete']);
    Route::get('/search', ['uses'=>'KonsumenController@searchKonsumen', 'as'=>'konsumen.search']);
    
});

Route::group(['prefix'=>'barangs'], function(){
    Route::get('/getnamabarang', ['uses'=>'BarangController@getNamaBarang', 'as'=>'barang.getNamaBarang']);
    Route::post('/store', ['uses'=>'BarangController@store', 'as'=>'barang.store']);
    Route::get('/edit/{id}', ['uses'=>'BarangController@getBarang', 'as'=>'barang.edit']);
    Route::put('/update/{id}', ['uses'=>'BarangController@update', 'as'=>'barang.update']);
    Route::get('/getbarangandharga', ['uses'=>'BarangController@getBarangAndHarga', 'as'=>'barang.getbarangandharga']);
    Route::get('/getallbarang', ['uses'=>'BarangController@getAllBarang', 'as'=>'konsumen.getAllBarang']);
    
});

Route::group(['prefix'=>'hargabarangs'], function(){
    Route::post('/store', ['uses'=>'HargaBarangController@store', 'as'=>'hargabarang.store']);
    Route::get('/edit/{id}', ['uses'=>'HargaBarangController@getHargaBarang', 'as'=>'barang.edit']);
    
});

Route::group(['prefix'=>'carts'], function(){
    Route::get('/getall', ['uses'=>'CartController@getAll', 'as'=>'barang.getall']);
    Route::post('/store', ['uses'=>'CartController@store', 'as'=>'cart.store']);
    Route::put('/update/{id}/{qty}', ['uses'=>'CartController@update', 'as'=>'cart.update']);
    Route::delete('/delete/{id}', ['uses'=>'CartController@delete', 'as'=>'cart.delete']);
    Route::get('/deleteall', ['uses'=>'CartController@deleteAll', 'as'=>'cart.deleteall']);
    
});

Route::group(['prefix'=>'transaksiglobals'], function(){
    Route::post('/store', ['uses'=>'TransaksiGlobalController@store', 'as'=>'transaksiglobal.store']);
    Route::get('/getalltransaksi', ['uses'=>'TransaksiGlobalController@getAllTransaksi', 'as'=>'transaksiglobal.getalltransaksi']);
    Route::get('/get5transaksi', ['uses'=>'TransaksiGlobalController@get5Transaksi', 'as'=>'transaksiglobal.get5transaksi']);
    Route::get('/gettransaksidet/{no_invoice}', ['uses'=>'TransaksiGlobalController@getAllTransaksiDetail', 'as'=>'transaksiglobal.gettransaksidet']);
    Route::get('/bayarinvoice/{no_invoice}/{totalbayar}', ['uses'=>'TransaksiGlobalController@bayarInvoice', 'as'=>'transaksiglobal.bayarinvoice']);
    Route::get('/printinvoice/{no_invoice}', ['uses'=>'TransaksiGlobalController@printInvoice', 'as'=>'transaksiglobal.printinvoice']);
});

Route::group(['prefix'=>'keteranganqtys'], function(){
    Route::post('/store', ['uses'=>'KeteranganQtyController@store', 'as'=>'keteranganqty.store']);
    Route::get('/getall', ['uses'=>'KeteranganQtyController@getAll', 'as'=>'keteranganqty.getall']);
    
});