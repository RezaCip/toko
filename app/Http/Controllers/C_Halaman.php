<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class C_Halaman extends Controller
{
    public function index()
    {
    	return view('utama');
    }

    public function pendaftaran()
    {
		$data_barang = DB::table('daftar_barang')->get();
		if ($data_barang != NULL) {
			return view('pendaftaran',['data' => $data_barang]);	
		}
		
		return view('pendaftaran');
	}
	
	public function tambah( Request $request)
	{
		$nama = $request->input('nama');
		$merk = $request->input('merk');
		$stok = $request->input('stok');
		$beli = str_replace(",", '', $request->input('harga_beli'));
		$jual = str_replace(",", '', $request->input('harga_jual'));

		$data = array(
			'nama_barang' => $nama,
			'merk' => $merk,
			'stok' => $stok,
			'harga_beli' => (int)$beli,
			'harga_jual' => (int)$jual
		);

		DB::table('daftar_barang')->insert($data);
		return redirect()->back();
	}

	public function gantiHarga(Request $request)
	{
		$id = $request->input('aidi');
		$hargaB = str_replace(",", '', $request->input('hrg_baru')) ;

		$data = array('harga_jual' => (int)$hargaB );

		DB::table('daftar_barang')->where('id', $id)->update($data);
		return redirect()->back();
	}
}
