var tabelLimit;
$(document).ready(function() {
	var divRequest = $(".div-request").text();

	$("#activeLaporan").addClass('active');

	if (divRequest == 'laporanLimit') {
	$("#activeLaporanLimit").addClass('active');

	tabelLimit = $("#tabelLimit").DataTable({
		'ajax' : 'action/cekLimit/fetchCekLimit.php',
		'order': []
	});

	$('#formPrintLimit').unbind('submit').bind('submit', function() {
		// var date = $('#date').val();
		// if (batsLimit == "") {
		// 	$("#batsLimit").after('<span class="help-inline">Batas Limit Masih Kosong</span>');
		// 	$("#batsLimit").closest('.control-group').addClass('error');
		// }else{
		// 	$("#batsLimit").removeClass('help-inline');
		// 	$("#batsLimit").closest('.control-group').addClass('success');
		// }
		// alert("tes");
		// if (batsLimit) {
			var form = $(this);
			$('#batsLimitBtn').button('loading');

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'text',
				success:function(response){
					$('#batsLimitBtn').button('reset');
					var cetak = window.open('', 'Aplikasi Inventori Gudang KTA', 'height=400, width=600');
					cetak.document.write('<html><head>');
					cetak.document.write('</head><body>');
					cetak.document.write(response);
					cetak.document.write('</body></html>');

					// cetak.document.close();//nercessary for IE >= 10
					// cetak.focus();

					// cetak.print();
					// cetak.close();

				}
			});
		// }

		return false;
	});


	$("#exportExcel").unbind('click').bind('click', function(){
		var mywindow = window.open('action/cekLimit/exportexcelLimit.php', 'Laporan Transaksi', '');
	});

	}
});

function editLimit(id_brg = null){
	if (id_brg) {
		//reset id_brg
		$("#editBarangId").remove();
		//hidden modal footer
		$(".modal-footer").addClass('div-hide');
		//reset form
		$("#editLimitForm")[0].reset();
		$.ajax({
			url : 'action/cekLimit/fetchSelectedLimit.php',
			type: 'POST',
			data: {id_brg: id_brg},
			dataType: 'json',
			success:function(response){
				//tampil button modal
				$(".modal-footer").removeClass('div-hide');
				//tampil nama barang
				$("#editBarang").val(response.brg);
				//tampil limit
				$("#setLimit").val(response.btsLimit);
				//tambah text input id_brg
				$(".modal-footer").after('<input type="hidden" name="editBarangId" id="editBarangId" value="'+response.id_brg+'" />');
				
				$("#editLimitForm").unbind('submit').bind('submit', function() {
					var setLimit = $("#setLimit").val();

					if (setLimit == "") {
						$("#setLimit").after('<span class="help-inline">Limit Masih Kosong</span>');
						$("#setLimit").closest('.control-group').addClass('error');
					}else{
						$("#setLimit").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}
					if (setLimit) {
						//ambil data form
						var form = $(this);

						$("#editLimitBtn").button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response){
								//reset button submit
								$("#editLimitBtn").button('reset');
								//jika berhasil disimpan
								if (response.success == true){
									tabelLimit.ajax.reload(null, false);

									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');
									//show messages pesan
									$('#edit-pesan').html('<div class="alert alert-success">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
									//fungsi tampil pesan delay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});									
								}
							}
						});
					}
					return false;
				});
			}
		});
	}else{
		alert("Oops! Refresh Halaman");
	}
}

function harusAnggka(a){
	if (!/^[0-9.]+$/.test(a.value)) {
		a.value = a.value.substring(0,a.value.length-1000);
	}
}

function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});