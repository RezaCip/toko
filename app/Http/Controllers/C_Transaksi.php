<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

date_default_timezone_set('Asia/Jakarta');
class C_Transaksi extends Controller
{
    
	private $fpdf;
    private $per;
    public function index(Request $request){
        $barang = DB::table('daftar_barang')->select('id', 'nama_barang', 'merk', 'harga_beli', 'harga_jual')->get();
        $date = date("Y-m-d H:i:s", mktime(0,0,0));
        $transaksi = DB::table('transaksi')->select('*')->where('tanggal', ">", $date)->get();

        if ($request->session()->has('cetak')) {
            $cetak = $request->session()->get('cetak');
        } else {
            $cetak = "tidak";
        }

        if($transaksi != NULL){
        	$dt = array();
            $total = 0;

        	foreach ($transaksi as $key => $value) {
        		$o = new \stdClass();
                
        		$barange = DB::table('daftar_barang')->select('nama_barang', 'merk')->where('id', '=', $value->id_barang)->first();
        		$o->id = $value->id_barang;
        		$o->nama_barang = $barange->nama_barang;
        		$o->merk = $barange->merk;
        		$o->jumlah = $value->jumlah;
        		$o->harga = $value->total;
        		$o->tanggal = $value->tanggal;
                $total += $value->total;

        		array_push($dt, $o);
        	}

        }

        return  view('transaksi', ['barang' => $barang, 'riwayat' => $dt, 'total' => $total, 'cetak' => $cetak]);
    }

    public function tambah(Request $request)
    {
        //array
        $nama = $request->input('nama');
    	$jumlah = $request->input('jumlah');
        $beli = $request->input('beli');
        $harga = $request->input('harga');
        $ongkir = $request->input('ongkir');

        //string
        $total = str_replace(",", '', $request->input('total')) ;
        $bayar = str_replace(",", '', $request->input('bayar')) ;
        $cetak = $request->input('cetak');
        // print_r($cetak);exit();
        $tohar = 0;
        $kembalian = 0;
        $struk = array();

    	foreach ($request->input('id') as $key => $value) {
            $conHarga = str_replace(",", '', $harga[$key]) ;
            $conOngkir = str_replace(",", '', $ongkir[$key]);
    		$data = array(
    			'id_barang' => $value,
    			'jumlah' => $jumlah[$key],
                'harga_beli' => $beli[$key],
                'ongkir' => $conOngkir,
    			'harga_jual' => $conHarga,
    			'total' => (int)$total[$key]
    		);

            $o = new \stdClass();
            $o->id = $value;
            $o->nama = $nama[$key];
            $o->harga = $harga[$key];
            $o->jumlah = $jumlah[$key];
            $o->total = (int)$total[$key];
            $tohar += (int)$total[$key];

            array_push($struk, $o);
    		DB::table('daftar_barang')->where('id', $value)->decrement('stok', $jumlah[$key]);
            DB::table('transaksi')->insert($data);
    	}
        $kembalian = (int)$bayar - $tohar;

        $kemba = array(
                    'total' => $tohar,
                    'kembali' => $kembalian,
                    'bayar' => $bayar);

        $this->pdfa($struk,$kemba);  
        // return $this->index();
        $request->session()->put('cetak', $cetak);

    	return redirect()->back()->with(compact("cetak"));
    }

    public function cariAuto(Request $request)
    {
        $hasil = '';
        $cari = $request->input('cari');
        $data = DB::table('daftar_barang')->where('nama_barang','like','%'.$cari.'%')->select('*')->get();
        return response()->json(['data' => $data]);
    }

    public function pdfa($data,$kemba)
    {
        $this->fpdf = new Fpdf;
        $this->fpdf->AddFont('dejavu','','DejaVuSansMono.php');
        $this->fpdf->AddFont('dejavu','B','dejavu-sans-mono.bold.php');
        $this->fpdf->AddPage("P", [100,300]);
        $this->fpdf->SetFont('times','B',25);
        $this->fpdf->Cell(0,10,"TOKO ORI",'','','C');
        $this->fpdf->Ln();
        $this->fpdf->SetFont('dejavu', '', 15);
        $this->fpdf->Cell(0,7, "JAYA SIMANDARAN 6G8",'','','C');
        $this->fpdf->Ln();
        $this->fpdf->Cell(0,7, "WA : 081515576376",'','','C');
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->SetFont('dejavu', 'B', 13);
        $this->fpdf->Cell(0,7,date("d-m-Y H:i:s"));
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        foreach ($data as $key => $value) {
            $this->fpdf->Cell(45,7, $value->nama);
            $this->fpdf->Ln();
            $this->fpdf->Cell(15,7, $value->jumlah." X",0);
            $this->fpdf->Cell(15,7, $value->harga,0);
            $this->fpdf->Cell(0,7, number_format((int)$value->total),0,'','R');
            $this->fpdf->Ln();
        }
        $this->fpdf->Ln();
        $this->fpdf->Cell(45,7, "TOTAL",'T');
        $this->fpdf->Cell(0,7, number_format((int)$kemba['total']),'T','','R');
        $this->fpdf->Ln();
        $this->fpdf->Cell(45,7, "TUNAI",'');
        $this->fpdf->Cell(0,7, number_format((int)$kemba['bayar']),'','','R');
        $this->fpdf->Ln();
        $this->fpdf->Cell(45,7, "KEMBALI",'');
        $this->fpdf->Cell(0,7, number_format((int)$kemba['kembali']),'','','R');
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Cell(0,5, "TERIMA KASIH",'','','C');
        $this->fpdf->Ln();
        $this->fpdf->Cell(0,5, "ATAS KUNJUNGAN ANDA.",'','','C');

        // $this->fpdf->Close();
        $this->fpdf->Output('F',"struk.pdf");
        
    }

}
