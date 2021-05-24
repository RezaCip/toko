<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class C_Belanja extends Controller
{
    public function index(){
        $barang = DB::table('daftar_barang')->select('id','nama_barang','merk')->get();
        $date = date("Y-m-d H:i:s", mktime(0,0,0));

        $data_bel = DB::table('belanja')->where('tanggal_beli',">",$date)->get();
        
        if ($data_bel != NULL) {

            $data_new = array();

            foreach ($data_bel as $key) {
                $o = new \stdClass();
                $daftar_brg = DB::table('daftar_barang')->select('nama_barang','merk')->where('id','=',$key->id_barang)->first();
                $o->id_barang = $key->id_barang;
                $o->nama_barang = $daftar_brg->nama_barang;
                $o->merk = $daftar_brg->merk;
                $o->jumlah = $key->jumlah;
                $o->harga_beli = $key->harga_beli;
                $o->tgl_beli = $key->tanggal_beli;
                
                array_push($data_new,$o);
            }

            return view('belanja',['data' => $data_new, 'barang' => $barang]);
        }
        
        return view('belanja');
    }

    public function tambah( Request $request){
        $idB = $request->input('idB');
        $jumlahB = $request->input('jumlahB');
        $hargaB = $request->input('hargaB');



        foreach ($idB as $key => $value) {
            $harB = str_replace(",", '', $hargaB[$key]) ;
            // echo $harB;exit();
            $data_belanja = array(
                'id_barang' => $value,
                'jumlah' => $jumlahB[$key],
                'harga_beli' => $harB
            );
            DB::table('daftar_barang')->where('id',$value)->increment('stok',$jumlahB[$key]);
            DB::table('daftar_barang')->where('id', $value)->update(['harga_beli' => $harB]);
            DB::table('belanja')->insert($data_belanja);
        }
        
        return redirect()->back();
    }
}
