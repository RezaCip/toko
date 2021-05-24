function auto() {
	// console.log(config.routes.code);
	$(this).autocomplete({
		source: function (request, response) {
			$.ajax({
				headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
				url: config.routes.zone,
				method: "POST",
				dataType: "json",
				data: {
					cari: request.term
				},
				success: function (data) {
					response($.map(data.data, function (barang) {
						return {
							label: barang.nama_barang,
							value: barang.nama_barang,
							id: barang.id,
							hj: barang.harga_jual
						}
					}))
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			// console.log(ui.item.id);
			$('#aidi').val(ui.item.id);
			if (config.routes.code === "T") {
				// $('#aidi').val(ui.item.id);
            	$('#transaksi').val(ui.item.value);
            	$('#jumlah').val(1);
            	$('#harga').val(ui.item.hj);
            	$('#total').val(ui.item.hj);
			} else if (config.routes.code === "K") {
				// console.log(ui.item.id)


			} else if (config.routes.code === "P"){
				$('#hargaL').val(ui.item.hj);
			}
			

		}
	})
}
$(document).on('keyup', ".autoData", auto);