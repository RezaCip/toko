<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('utama');
// });

Route::get('/', 'C_Halaman@pendaftaran');
Route::get('/pendaftaran', 'C_Halaman@pendaftaran');
Route::get('/transaksi', 'C_Transaksi@index');
Route::get('/belanja', 'C_Belanja@index');
Route::post('/tambah', 'C_Halaman@tambah');
Route::post('/tambahStok', 'C_Halaman@tambahStok');
Route::post('/tambah_brg', 'C_Belanja@tambah');
Route::post('/tambahTransaksi', 'C_Transaksi@tambah');
Route::post('/find', 'C_Transaksi@cariAuto');
Route::post('/gntHarga', 'C_Halaman@gantiHarga');
Route::get('/pdf', 'C_Transaksi@pdf');
Route::get('/convert', 'C_Convert@index');
Route::post('/csv', 'C_Convert@todb');
