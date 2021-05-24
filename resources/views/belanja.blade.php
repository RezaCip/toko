@extends('testing')
@section('title', "Kulak")
@section('judul', "Kulak")
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kulak</h6>
            </div>
            <div class="card-body">
                <form action="{{url('/tambah_brg')}}" method="post">
                    {{csrf_field()}}
                        <div class="row" style="margin-bottom: 20px; margin-left: 1px">
                            <!-- <select id="belanja" class="form-control col-lg-3 belanja" name="belanja[]" style="margin-right: 20px;">
                                <option>Nama Barang</option>
                            </select> -->
                            <input id="aidi" type="hidden" name="">
                            <input id="belanja" type="text" name="" class="form-control col-lg-3 autoData" placeholder="Ketik Nama Barang" style="margin-right: 20px">
                            <input id="jumlah" class="form-control col-lg-2" type="number" placeholder="Jumlah" name="jumla" style="margin-right: 20px;">
                            <input id="harga" class="form-control col-lg-3 duit" type="text" placeholder="Harga Beli" name="harga_bel" style="margin-right: 20px;">

                            <button id="addBarang" class="btn btn-success" type="button" style="margin-right: 10px;"><i class="fa fa-plus"></i></button>
                            <!-- <button id="delBarang" class="btn btn-danger" type="button"><i class="fa fa-minus"></i></button> -->
                        </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Beli</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                
                            </tbody>
                        </table>
                    </div>
                    
                    <input class="btn btn-primary float-right" type="submit" name="submit" value="Tambah">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Kulak</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Riwayat_Belanja" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Tanggal Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $belanja)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$belanja->nama_barang}}</td>
                                <td>{{$belanja->merk}}</td>
                                <td>{{$belanja->jumlah}}</td>
                                <td>@currency($belanja->harga_beli)</td>
                                <td>{{$belanja->tgl_beli}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            code: "K"
        }
    };
    $(document).ready(function(){
        var data = <?php echo json_encode($barang);?>;
        $('#belanja').focus();

        function getID(params) {
            var id, idAttr;
            idAttr = params.attr('id');
            id = idAttr.split("_");
            return id[id.length - 1];
        }
        
        var IB = 1;

        $('#addBarang').click(function () {
            $('#body').append(
                '<tr id="tr_'+IB+'">'+
                '<td><input id="idB_'+IB+'" type="hidden" name="idB[]"><input id="nama_'+IB+'" class="form-control" type="text" readonly></td>'+
                '<td><input id="jumlahB_'+IB+'" class="form-control" type="number" name="jumlahB[]" readonly></td>'+
                '<td><input id="hargaB_'+IB+'" class="form-control" type="text" name="hargaB[]" readonly></td>'+
                '<td><button id="hps_'+IB+'" class="btn btn-danger hps" type="button"><i class="fa fa-trash"></i></button></td>'+
                '</tr>');

            $('#idB_'+IB).val($('#aidi').val());
            $('#nama_'+IB).val($('#belanja').val());
            $('#hargaB_'+IB).val($('#harga').val());
            $('#jumlahB_'+IB).val($('#jumlah').val());
            IB++;
            $('#aidi').val();
            $('#belanja').val();
            $('#harga').val();
            $('#jumla').val();

            $('#belanja').focus();
        });

        $(document).on("click", ".hps", del);
        $(document).on("keyup", ".duit", format);

        function format() {
            var val = $(this).val().replace(/[\,]/g,'');
            var hasil = val.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");

            return $(this).val(hasil);

        }

        function del() {
            var id = getID($(this));
            $('#tr_'+id).empty();
        }

        $.each(data, function(i, val){
            $('#belanja').append(new Option(data[i].nama_barang + " ("+data[i].merk+")", i));
        });

        $('#Riwayat_Belanja').DataTable({
            "order": [[5, "desc"]]
        });
    })
</script>
@endsection