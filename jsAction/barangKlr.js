var tabelKeluar;
$(document).ready(function() {

	$('#noFaktur').on('keyup', function (e) {
	        if(e.ctrlKey && e.which == 39) {

	    		$(this).prev().prev().focus('#keterangan');
	    		alert('tes');
	    
	  		}

	});
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $(".chosen-select").chosen({width: "95%"});
	//mengambil data div class div-request
	var divRequest = $(".div-request").text();
	// active manu barang
	$('#activeTransaksi').addClass('active');
	
	if(divRequest == 'keluar'){
		// active submenu barang keluar
		$('#activeBarangKeluar').addClass('active');

		tabelKeluar = $('#tabelKeluar').DataTable({
			'ajax' : 'action/barangKeluar/fetchKeluar.php',
			'order': []
		});// manage TabelKeluar

	$('.datepicker').datepicker();

	$("#id_brg").change(function () {
		$("#id_brg option:selected").each(function () {
			const id_brg = $(this).val();
			console.log(id_brg);
			$.post("action/barangKeluar/fetchSelectedRakSaldo.php", { id_brg: id_brg }, function(data){
				$("#id_rak").html(data);
			});            
		});		
	});

	$('#submitBarangKlr').unbind('submit').bind('submit', function()
	{

		//var nama     = $("#nama").val();
		var noFaktur   = $("#noFaktur").val();
		var toko       = $("#id_toko").val();
		//var keterangan = $("#keterangan").val();
		var id_brg     = $("#id_brg").val();
		var rak        = $("#id_rak").val();
		var jumlah     = $("#jumlah").val();


		//cek barang rak jumlah jika kosong
		/*if (nama == "") {
			$("#nama").before('<span class="help-inline bawah">Pengirim Keluar Masih Kosong</span>');
			$('#nama').closest('.control-group').removeClass('success');
			$('#nama').closest('.control-group').addClass('error');
		}else{
			$('#nama').closest('.control-group').removeClass('error');
			$("#nama").closest('.control-group').addClass('success');
			$("span").remove(":contains('Pengirim Keluar Masih Kosong')");
		}*/

		if (noFaktur == "") {
			$("#noFaktur").after('<span class="help-inline">No Faktur Keluar Masih Kosong</span>');
			$('#noFaktur').closest('.control-group').removeClass('success');
			$('#noFaktur').closest('.control-group').addClass('error');
		}else{
			$('#noFaktur').closest('.control-group').removeClass('error');
			$("#noFaktur").closest('.control-group').addClass('success');
			$("span").remove(":contains('No Faktur Keluar Masih Kosong')");
		}

		if (toko == "") {
			$("#id_toko").after('<span class="help-inline">Nama Toko Keluar Masih Kosong</span>');
			$('#id_toko').closest('.control-group').removeClass('success');
			$('#id_toko').closest('.control-group').addClass('error');
		}else{
			$('#id_toko').closest('.control-group').removeClass('error');
			$("#id_toko").closest('.control-group').addClass('success');
			$("span").remove(":contains('Nama Toko Keluar Masih Kosong')");
		}

		// if (keterangan == "") {
		// 	$("#keterangan").after('<span class="help-inline">Keterangan Keluar Masih Kosong</span>');
		// 	$('#keterangan').closest('.control-group').removeClass('success');
		// 	$('#keterangan').closest('.control-group').addClass('error');
		// }else{
		// 	$('#keterangan').closest('.control-group').removeClass('error');
		// 	$("#keterangan").closest('.control-group').addClass('success');
		// 	$("span").remove(":contains('Keterangan Keluar Masih Kosong')");
		// }

		if (id_brg == "") {
			$("#id_brg").after('<span class="help-inline">Nama Barang Keluar Masih Kosong</span>');
			$('#id_brg').closest('.control-group').removeClass('success');
			$('#id_brg').closest('.control-group').addClass('error');
		}else{
			$('#id_brg').closest('.control-group').removeClass('error');
			$("#id_brg").closest('.control-group').addClass('success');
			$("span").remove(":contains('Nama Barang Keluar Masih Kosong')");

		}

		if (rak == "") {
			$("#id_rak").after('<span class="help-inline">Lokasi Rak Keluar Masih Kosong</span>');
			$('#id_rak').closest('.control-group').removeClass('success');
			$('#id_rak').closest('.control-group').addClass('error');
		}else{
			$('#id_rak').closest('.control-group').removeClass('error');
			$("#id_rak").closest('.control-group').addClass('success');
			$("span").remove(":contains('Lokasi Rak Keluar Masih Kosong')");
			
		}

		if (jumlah == "") {
			$("#jumlah").after('<span class="help-inline">Jumlah Keluar Masih Kosong</span>');
			$('#jumlah').closest('.control-group').removeClass('success');
			$('#jumlah').closest('.control-group').addClass('error');
		}else{
			$('#jumlah').closest('.control-group').removeClass('error');
			$("#jumlah").closest('.control-group').addClass('success');
			$("span").remove(":contains('Jumlah Keluar Masih Kosong')");
			
		}
		//end cek barang rak jumlah jika kosong
		
		if (noFaktur && toko && id_brg && rak && jumlah) {
			//
			var form = $(this);
			//
			$("#simpanBarangKlrBtn").button('loading');

			$.ajax({//
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					//
					$("#simpanBarangKlrBtn").button('reset');

					 if (response.success == true) {
						tabelKeluar.ajax.reload(null, false);

						//reset the form text
						//$("#submitBarangKlr")[0].reset();
						$('#id_brg').val('');
						$('#id_rak').val('');
						$('#jumlah').val('');
						//remove the error text
						$(".help-inline").remove();
						//reset combobox
						$("#id_brg").trigger("chosen:updated");
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});
					}

					 else if (response.success == false) {

						//reset the form text
						//$("#submitBarangKlr")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

						//fungsi tampil pesan delay
						// $(".alert-success").delay(500).show(10, function() {
						// 	$(this).delay(4000).hide(10, function() {
						// 		$(this).remove();
						// 	});
						// });
					}
				}
			});
		}
		return false;
	});

	$("#editSeriPJKBtnModal").unbind('click').bind('click', function(){

		$.ajax({
			url  : 'action/barangKeluar/fetchSeriPajak.php',
			dataType : 'json',
			success:function(data){
				$('#noSeriPJKLama').val(data.seriPajak);
			}
		});

		$("#submitSeriPajak").unbind('submit').bind('submit', function(){

			var noSeriPJKBaru = $("#noSeriPJKBaru").val();

			if (noSeriPJKBaru == "") {
				$("#noSeriPJKBaru").after('<span class="help-inline">No Seri Pajak Masih Kosong</span>');
				$('#noSeriPJKBaru').closest('.control-group').removeClass('success');
				$('#noSeriPJKBaru').closest('.control-group').addClass('error');
			}else{
				$('#noSeriPJKBaru').closest('.control-group').removeClass('error');
				$("#noSeriPJKBaru").closest('.control-group').addClass('success');
				$("span").remove(":contains('No Seri Pajak Masih Kosong')");
			}

			if (noSeriPJKBaru)
			{
				var form = $(this);
				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType: 'json',
					success:function(response){

						if (response.success == true) {

							$.ajax({
								url  : 'action/barangKeluar/fetchSeriPajak.php',
								dataType : 'json',
								success:function(data){
									$('#noSeriPJKLama').val(data.seriPajak);
								}
							});

							$('#pesanSeriPJK').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

							setTimeout(function(){ window.location.reload(1); }, 1500);

						}
						else
						{
							alert('asdas');
						}
					}
				});
			}

			return false;
		});
	});

	//});

	//$('#addEfakturBtnModal').unbind('click').bind('click', function() {
	$('#submitEfaktur').unbind('submit').bind('submit', function() {
		var efaktur = $("#efaktur").val();
		var asal = $("#asal").val();

		if (efaktur == "") {
			$("#efaktur").after('<span class="help-inline">No Faktur Masih Kosong</span>');
			$("#efaktur").closest('.control-group').addClass('error');
		}else{
			$("#efaktur").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (asal == "") {
			$("#asal").after('<span class="help-inline">Asal Ban Masih Kosong</span>');
			$("#asal").closest('.control-group').addClass('error');
		}else{
			$("#asal").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (efaktur && asal) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanEfakturBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					$("#simpanEfakturBtn").button('reset');

					if (response.success == true) {
						tabelKeluar.ajax.reload(null, false);

						//reset the form text
						$("#submitEfaktur")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan12').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});
					}else{
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan12').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
				}
			});
		}

		return false;
	});
	//});

	$("#submitEditBarangKlr").unbind('submit').bind('submit', function() {

			var form = $(this);	
			$('#editBarangKlrBtn').button('loading');

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){
					
					if (response.success == true)
					{

						tabelKeluar.ajax.reload(null, false);

						$('#editBarangKlrBtn').button('reset');
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesanEdit').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});
						
					}
					else if (response.success == false)
					{
						
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesanEdit').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

					}

				}
			});

		return false;
	});

	$('#cariDataLama').unbind('click').bind('click', function(){

		$('#modalCariData').on('shown', function () {
		  $('#cariBulan').focus();
		});

		$('#submitCariData').unbind('submit').bind('submit', function(){

			var cariBulan = $('#cariBulan').val();
			var cariTahun = $('#cariTahun').val();

			if (cariBulan == "") {
				$("#cariBulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$('#cariBulan').closest('.control-group').removeClass('success');
				$('#cariBulan').closest('.control-group').addClass('error');
			}else{
				$('#cariBulan').closest('.control-group').removeClass('error');
				$("#cariBulan").closest('.control-group').addClass('success');
				$("span").remove(":contains('Bulan Masih Kosong')");
			}

			if (cariTahun == "") {
				$("#cariTahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$('#cariTahun').closest('.control-group').removeClass('success');
				$('#cariTahun').closest('.control-group').addClass('error');
			}else{
				$('#cariTahun').closest('.control-group').removeClass('error');
				$("#cariTahun").closest('.control-group').addClass('success');
				$("span").remove(":contains('Tahun Masih Kosong')");
			}

			if (cariBulan && cariTahun) {

				var form = $(this);
				$('#simpanCariBtn').button('loading');
				$.ajax({

					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType : 'text',
					success:function(response){

						$("#modalCariData").modal('hide');
						$('#simpanCariBtn').button('reset');


						var mywindow = window.open('', 'Laporan Transaksi', 'height=380,width=700');
						mywindow.document.write(response);
						
					}


				});

			}

			return false;
		});
		
	});
	
	}



});

//function edit keluar
function editKeluar(id_det_klr = null)
{

	if (id_det_klr)
	{

		$.ajax({

			url  : 'action/barangKeluar/fetchSelectedKeluar.php',
			type : 'post',
			data : {id_det_klr: id_det_klr},
			dataType : 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');

				$("#editToko").val(response.toko);
				$("#editFaktur").val(response.no_faktur);
				$("#editTgl").val(response.tgl);
				$("#editKet").val(response.ket);
				$("#editBrg").val(response.brg);
				$("#editRak").val(response.rak);
				$("#editJml").val(response.jml_klr);
				$("#editId").val(response.id_det_klr);

			}

		});

	}

}

//fungsi hapus tabel keluar
function hapusKeluar(id_det_klr = null)
{

	if (id_det_klr)
	{

		$.ajax({
			url: 'action/barangKeluar/fetchSelectedKeluar.php',
			type: 'post',
			data: {id_det_klr: id_det_klr},
			dataType: 'json',
			success:function(response) {
				//modal footer
				$(".modal-footer").removeClass('hidden');	
				// add the keluar id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_klr+'?</strong>');
				// remove keluar btn clicked to remove the keluar function
				$("#hapusKeluarBtn").unbind('click').bind('click', function() {
				// remove keluar btn
				$("#hapusKeluarBtn").button('loading');

					$.ajax({
					url: 'action/barangKeluar/hapusKeluar.php',
					type: 'post',
					data: {id_det_klr: id_det_klr},
					dataType: 'json',
					success:function(response) {
						if(response.success == true) {
							//button reset
							$("#hapusKeluarBtn").button('reset');
							// close the modal 
							$("#hapusModalKeluar").modal('hide');

								tabelKeluar.ajax.reload(null, false);
							//show messages pesan
							/*$('#hapus-pesan').html('<div class="alert alert-success">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
							//fungsi tampil pesan delay
							$(".alert-success").delay(500).show(10, function() {
								$(this).delay(4000).hide(10, function() {
									$(this).remove();
								});
							});*/
							var unique_id = $.gritter.add({
					            // (string | mandatory) the heading of the notification
					            title: 'Pesan Success!',
					            // (string | mandatory) the text inside the notification
					            text: response.messages,
					            // (string | optional) the image to display on the left
					            image: 'img/success-mini.png',
					            // (bool | optional) if you want it to fade out on its own or just sit there
					            sticky: true,
					            // (int | optional) the time you want it to be alive for before fading out
					            time: '',
					            // (string | optional) the class name you want to apply to that specific message
					            class_name: 'my-sticky-class'
					        });

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
					         setTimeout(function(){

						         $.gritter.remove(unique_id, {
						         fade: true,
						         speed: 'slow'
						         });

					         }, 6000)
						}
						else if (response.success == false)
						{
							//button reset
							$("#hapusKeluarBtn").button('reset');
							// close the modal 
							$("#hapusModalKeluar").modal('hide');
							//show messages simapanKategori
							/*$('#hapus-pesan').html('<div class="alert alert-error">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
							//fungsi tampil pesan delay
								$(".alert-error").delay(500).show(10, function() {
									$(this).delay(4000).hide(10, function() {
										$(this).remove();
									});
								});*/
							var unique_id = $.gritter.add({
					            // (string | mandatory) the heading of the notification
					            title: 'Pesan Error!',
					            // (string | mandatory) the text inside the notification
					            text: response.messages,
					            // (string | optional) the image to display on the left
					            image: 'img/error-mini.png',
					            // (bool | optional) if you want it to fade out on its own or just sit there
					            sticky: true,
					            // (int | optional) the time you want it to be alive for before fading out
					            time: '',
					            // (string | optional) the class name you want to apply to that specific message
					            class_name: 'gritter-light'
						    });
						}
					}
					});
				});

			}
		});

	}
	else
	{
		$(".modal-footer").addClass('hidden');

		$("#pesanHapus").html('<strong>Data Tidak Bisa di Hapus/Ubah. Sudah Lewat Bulan/Tahun</strong>');

	}

}

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});