@extends('testing')
@section('title', "Daftar Barang")
@section('judul', "Daftar Barang")
@section('content')
<div class="row">
	<div class="col-xl-12 col-lg-7">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Tambah Barang</h6>
			</div>
			<div class="card-body">
				<form action="{{url('/tambah')}}" method="post">
				{{csrf_field()}}
				<div class="row" style="margin-bottom: 10px">
					<input id="nB" class="form-control col-xl-3" type="text" placeholder="Nama Barang" style="margin-right: 5px">
					<input id="merk" class="form-control col-xl-2" type="text" placeholder="Merk" style="margin-right: 5px">
					<input id="stok" class="form-control col-xl-1" type="number" placeholder="Stok" style="margin-right: 5px">
					<input id="hb" class="form-control col-xl-2 duit" type="text" placeholder="Harga Beli" style="margin-right: 5px">
					<input id="hj" class="form-control col-xl-2 duit" type="text" placeholder="Harga Jual" style="margin-right: 5px">
					<button id="addTransaksi" type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered">
						
						<thead>
							<tr>
								<th>Nama Barang</th>
								<th>Merk</th>
								<th>Jumlah</th>
								<th>Harga Beli</th>
								<th>Harga Jual</th>
							</tr>
						</thead>
						<tbody id="body"></tbody>

					</table>
				</div>
				<input class="btn btn-primary float-right" type="submit" name="sumbit" value="Tambah">
				</form>
			</div>
		</div>
	</div>

	<!-- <div class="col-xl-3 col-lg-7">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Ganti Stok</h6>
			</div>
			<div class="card-body">
				<form action="{{url('/tambahStok')}}" method="post">
				{{csrf_field()}}
					<select id="tambahStok" class="form-control" name="barang">
						<option value="">Daftar Barang</option>
					</select><br>
					<input class="form-control" type="text" name="jml" placeholder="Jumlah Stok"><br>
					<input class="btn btn-primary float-right" type="submit" name="sumbit" value="Tambah">
				</form>
			</div>
		</div>
	</div> -->

	<div class="col-xl-3 col-lg-7">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Ganti Harga Jual</h6>
			</div>
			<div class="card-body">
				<form action="{{url('/gntHarga')}}" method="post">
					{{csrf_field()}}
					<!-- <select id="gantiHarga" class="form-control">
						<option>Nama Barang</option>
					</select> -->
					<input id="aidi" type="hidden" name="aidi">
					<input id="barang" type="text" name="" class="form-control autoData" placeholder="Ketik Nama Barang"><br>
					<input id="hargaL" class="form-control duit" type="text" name="hrg_lama" readonly value="0"><br>
					<!-- <input id="idB" type="hidden" name="idB"> --><input id="hargaB" class="form-control duit" type="text" name="hrg_baru" placeholder="Harga Baru"><br>
					<input class="btn btn-primary float-right" type="submit" name="sumbit" value="Ganti">
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 col-lg-7">
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="daftarB" class="table table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Barang</th>
								<th>Merk</th>
								<th>Stok</th>
								<th>Harga Beli</th>
								<th>Harga Jual</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $barang)
							<tr>
								<td>{{$loop->iteration}}</td>
								<td>{{$barang->nama_barang}}</td>
								<td>{{$barang->merk}}</td>
								<td>{{$barang->stok}}</td>
								<td>@currency($barang->harga_beli)</td>
								<td>@currency($barang->harga_jual)</td>
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

<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript">
	var config = {
		routes: {
			zone: "{{url('/find')}}",
			code: "P"
		}
	};
	$(document).ready(function () {
		$('#nB').focus();
		$('#dataB').DataTable();

		$('#daftarB').DataTable();

		var barang = <?php echo json_encode($data);?>;
		// console.log(barang);

		$(document).on("keyup", ".duit", ganti);
		function ganti() {
			var angka = $(this).val();
			// var b = parseInt(angka);
			var a = angka.replace(/[\,]/g,'');
			var hasil = a.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
			
			return $(this).val(hasil);
			// return ;
		}

		$.each(barang,function(i, val){
			$('#tambahStok').append(new Option(barang[i].nama_barang, barang[i].nama_barang));
			$('#gantiHarga').append(new Option(barang[i].nama_barang, i));
		});

		$('#gantiHarga').change(function () {
			$('#hargaL').val(barang[$('#gantiHarga').val()].harga_jual);
			$('#idB').val(barang[$('#gantiHarga').val()].id);
		})

		var IT = 1;

		$('#addTransaksi').click(function(){
			$('#body').append(
				'<tr id="tr_'+IT+'">'+
					'<td><input id="nama_'+IT+'" type="text" name="nama[]" readonly="true" class="form-control"></td>'+
					'<td><input id="merk_'+IT+'" type="text" name="merk[]" readonly="true" class="form-control"></td>'+
					'<td><input id="stok_'+IT+'" type="text" name="stok[]" readonly="true" class="form-control"></td>'+
					'<td><input id="beli_'+IT+'" type="text" name="harga_beli[]" readonly="true" class="form-control"></td>'+
					'<td><input id="jual_'+IT+'" type="text" name="harga_jual[]" readonly="true" class="form-control"></td>'+
				'</tr>'
				);
			$('#nama_'+IT).val($('#nB').val());
			$('#merk_'+IT).val($('#merk').val());
			$('#stok_'+IT).val($('#stok').val());
			$('#beli_'+IT).val($('#hb').val());
			$('#jual_'+IT).val($('#hj').val());

			$('#nB').val('');
			$('#merk').val('');
			$('#stok').val('');
			$('#hb').val('');
			$('#hj').val('');
			IT++;
			
			$('#nB').focus();
		});

	});
</script>
@endsection