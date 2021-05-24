@extends('testing')
@section('title', "Transaksi")
@section('judul', "Transaksi")
@section('buttonAtas')
<button id="perin" type="submit" class="btn btn-primary btn-sm float-right"><i class="fa fa-print"></i> Print Terakhir</button>
@endsection
@section('content')
<style type="text/css">
.ui-autocomplete {
z-index: 100;

}
table.fixed{
    table-layout: auto;
    width: 100%;
}
.ui-autocomplete
{
    position:absolute;
    cursor:default;
    z-index:1001 !important
}
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi</h6>
            </div>
            <div class="card-body">
                <form action="{{url('/tambahTransaksi')}}" method="post">
                    {{csrf_field()}}
                    <div id="kumpulan_Transaksi" style="margin-bottom: 20px;margin-left: 12px">
                        <div class="row" style="margin-bottom: 5px">
                            <input id="aidi" type="hidden" name="">
                            <input id="transaksi" type="text" name="" class="form-control col-lg-3 autoData" placeholder="Ketik Nama Barang" style="margin-right: 20px" >
                            <input id="jumlah" class="form-control col-lg-1" type="number" placeholder="QTY" style="margin-right: 20px;">
                            <input id="beli" type="hidden" >
                            <input id="harga" class="form-control col-lg-2" type="number" placeholder="Harga" disabled style="margin-right: 20px;">
                            <input id="ongkir" type="text" name="ongkos" class="form-control col-lg-2 duit" style="margin-right: 20px" placeholder="Ongkir" value="0">
                            <input id="total" class="form-control col-lg-2" type="number" placeholder="Total" disabled style="margin-right: 20px;">
                            <button id="addTransaksi" class="btn btn-success" type="button" style="margin-right: 10px;"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th>No</th> -->
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="body">
                            </tbody>
                            <tfoot>
                            <tr style="text-align: center;">
                                <th colspan="3">Total</th>
                                <th id="jumlahHarga"></th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                    <h4>Bayar : </h4>
                    <input class="form-control col-lg-3 duit" type="text" name="bayar" placeholder="Cash" required>
                    <input type="checkbox" name="cetak" value="iya"> Cetak Struk
                    <input class="btn btn-primary float-right" type="submit" name="submit" value="Tambah">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Riwayat_Transaksi" class="table table-bordere">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->nama_barang}}</td>
                                <td>{{$data->merk}}</td>
                                <td>{{$data->jumlah}}</td>
                                <td>@currency($data->harga)</td>
                                <td>{{$data->tanggal}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <h4>Omset = @currency($total)</h4> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

  
<script type="text/javascript">
    var config = {
        routes: {
            zone: "{{url('/find')}}",
            code: "T"
        }
    };
    $(document).ready(function(){
        var barang = <?php echo json_encode($barang);?>;
        var perint = <?php echo json_encode($cetak);?>;
        
        if (perint.toString() === "iya") {

            printJS('http://localhost/toko/public/struk.pdf');
        }
        $('#transaksi').focus();

        function getID(params) {
            var id, idAttr;
            idAttr = params.attr('id');
            id = idAttr.split("_");
            return id[id.length - 1];
        }

        function hilangKoma(argument) {
            return argument.replace(/[\,]/g,'');
        }

        function kasihKoma(argument) {
            return argument.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        function ganti() {
            var angka = $(this).val();

            var a = hilangKoma(angka);
            var hasil = kasihKoma(a) ;
            
            return $(this).val(hasil);
        }

        function hps() {
            var id = getID($(this));
            var jum = hilangKoma($('#total_'+id).val());

            total = total - parseInt(jum);

            var converTot = kasihKoma(total.toString());

            $('#jumlahHarga').html("Rp. "+converTot);
            $('#tr_'+id).empty();
        }

        function setTot() {
            $('#total').val($('#harga').val() * $('#jumlah').val());
        }

        function getTot() {
            var a = $('#harga').val() * $('#jumlah').val();
            var b = $('#jumlah').val() * parseInt(hilangKoma($('#ongkir').val()));
            return a + b;
        }
        $('#perin').click(function () {
            
            printJS('http://localhost/toko/public/struk.pdf');
        })

        $('#jumlah').keyup(function () {
            $('#total').val(getTot());
        })

        $('#ongkir').keyup(function () {
            $('#total').val(getTot());
            
        })
        $('#Riwayat_Transaksi').DataTable({
            "order": [[0, "desc"]]
        });

        $('#transaksi').click(function () {
            $('#transaksi').load(location.href + " #transaksi");
        });

        var IT = 1;
        var total = 0;

        $('#addTransaksi').click(function(){
            $('#body').append(
                    '<tr id="tr_'+IT+'">'+
                        // '<td>'+IT+'</td>'+
                        '<td><input id="id_'+IT+'" type="hidden" name="id[]"><input id="nama_'+IT+'" type="text" name="nama[]" readonly="true" class="form-control"></td>'+
                        '<td><input id="jumlah_'+IT+'" type="text" name="jumlah[]" readonly="true" class="form-control"></td>'+
                        '<td><input id="beli_'+IT+'" type="hidden" name="beli[]"><input id="harga_'+IT+'" type="text" name="harga[]" readonly="true" class="form-control"><input id="ongkir_'+IT+'"" type="text" name="ongkir[]" class="form-control" readonly></td>'+
                        '<td><input id="total_'+IT+'" type="text" name="total[]" readonly="true" class="form-control total"></td>'+
                        '<td><button type="button" id="h_'+IT+'" class="btn btn-danger hapus_T"><i class="fa fa-trash"></i></button type="button"></td>'+
                    '</tr>'
                );

            $('#id_'+IT).val($('#aidi').val());
            $('#nama_'+IT).val($('#transaksi').val());
            $('#jumlah_'+IT).val($('#jumlah').val());
            $('#beli_'+IT).val($('#beli').val());
            $('#harga_'+IT).val(kasihKoma($('#harga').val()));
            $('#ongkir_'+IT).val($('#ongkir').val());
            $('#total_'+IT).val(kasihKoma($('#total').val()));
            total += parseInt($('#total').val());
            IT++;

            $('#transaksi').val('');
            $('#jumlah').val('');
            $('#harga').val('');
            $('#ongkir').val(0);
            $('#total').val('');
            var converTot = kasihKoma(total.toString());
            $('#jumlahHarga').html('Rp. '+converTot);
            $('#transaksi').focus();
        });

        $(document).on('click', '.hapus_T', hps);
        // $(document).on('change', '#jumlah', setTot);
        $(document).on("keyup", ".duit", ganti);

    });

</script>
@endsection