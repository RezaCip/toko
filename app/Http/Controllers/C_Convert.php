<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class C_Convert extends Controller

{
	public function index()
	{
		return view('convert');
	}
	public function todb(Request $request)
	{
		ini_set('memory_limit', '-1');
		$files = $request->file('file');
		$path = $files->getRealPath();
		// print_r($path);

		$fileName = $_FILES["file"]["tmp_name"];
		// print_r($fileName);exit();

		if ($_FILES["file"]["size"] > 0) {
			$file = fopen($path, "r");
			// print_r($file);exit();

			while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
				// print($column[0]);exit();
				if($column[0] == ""){
					continue;
				}

				$data = array(
					'nama_barang' => $column[0],
					'harga_beli' => $column[1],
					'harga_jual' => $column[2]
				);
				try {
					DB::table('daftar_barang')->insert($data);
					$type = "sukses";
					
				} catch (Exception $e) {
					$type = $e->getmessage();
				}
				echo $type;
			}
		}
	}
}
