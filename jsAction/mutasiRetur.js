var tabelRetur;
var tabelMutasi;
$(document).ready(function() {
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
	
	$('.datepicker').datepicker();

	if (divRequest == 'retur')
	{
		// active submenu barang masuk
		$('#activeRetur').addClass('active');

		    tabelRetur = $('#tabelRetur').DataTable({
			'ajax' : 'action/return/fetchReturn.php',
			'order':[]
		});// manage TabelMasuk

		$("#bulanfaktur").change(function () {
			$("#bulanfaktur option:selected").each(function () {
				blnfak = $(this).val();
				window.location ='mutasiRetur.php?p=retur&bln='+blnfak;
			});
		});

		var nilaifak = $("#nilaibulanfak").val();
		$("#bulanfaktur").val(nilaifak);

		$("#addReturnBtnModal").unbind('click').bind('click', function() {

			$("#NofakAwal").change(function () {
				$("#NofakAwal option:selected").each(function () {
					nofak = $(this).val();
					$.post("action/return/fetchSelectedUkuran.php", { nofak: nofak }, function(data){
						$("#id_det_klr").html(data);
					});            
				});		
			});

		$("#submitRetur").unbind('submit').bind('submit', function() {
			//variabel
			var NofakAwal    = $("#NofakAwal").val();
			var fakturRetur  = $("#fakturRetur").val();
			//var keterangan   = $("#keterangan").val();
			var id_det_klr   = $("#id_det_klr").val();
			var id_rakRtr    = $("#id_rakRtr").val();
			var jmlRtr       = $("#jmlRtr").val();

			//cek setiap filed input
			if (NofakAwal == "") {
				$("#NofakAwal").before('<span class="help-inline bawah">No Faktur Retur Masih Kosong</span>');
				$('#NofakAwal').closest('.control-group').addClass('error');
			}else{
				$("#NofakAwal").find('.help-inline').remove();
				$("span").remove(":contains('No Faktur Retur Masih Kosong')");
			}

			if (fakturRetur == "") {
				$("#fakturRetur").before('<span class="help-inline bawah">No Return Masih Kosong</span>');
				$('#fakturRetur').closest('.control-group').addClass('error');
			}else{
				$("#fakturRetur").find('.help-inline').remove();
				$("span").remove(":contains('No Return Masih Kosong')");
			}

			// if (keterangan == "") {
			// 	$("#keterangan").before('<span class="help-inline ">Keterangan Retur Masih Kosong</span>');
			// 	$('#keterangan').closest('.control-group').addClass('error');
			// }else{
			// 	$("#keterangan").find('.help-inline').remove();
			// 	$("span").remove(":contains('Keterangan Retur Masih Kosong')");
			// }

			if (id_det_klr == "") {
				$("#id_det_klr").before('<span class="help-inline ">Ukuran Retur Masih Kosong</span>');
				$('#id_det_klr').closest('.control-group').addClass('error');
			}else{
				$("#id_det_klr").find('.help-inline').remove();
				$("span").remove(":contains('Ukuran Retur Masih Kosong')");
			}

			if (id_rakRtr == "") {
				$("#id_rakRtr").before('<span class="help-inline ">Rak Retur Masih Kosong</span>');
				$('#id_rakRtr').closest('.control-group').addClass('error');
			}else{
				$("#id_rakRtr").find('.help-inline').remove();
				$("span").remove(":contains('Rak Retur Masih Kosong')");
			}

			if (jmlRtr == "") {
				$("#jmlRtr").before('<span class="help-inline ">Jumlah Retur Masih Kosong</span>');
				$('#jmlRtr').closest('.control-group').addClass('error');
			}else{
				$("#jmlRtr").find('.help-inline').remove();
				$("span").remove(":contains('Jumlah Retur Masih Kosong')");
			}
			//end cek jika kosong
			
			if (NofakAwal && fakturRetur && id_det_klr && id_rakRtr && jmlRtr)
			{
				//ambil data form
				var form = $(this);
				//button simpan loading
				$("#simpanReturnBtr").button('loading');

				// fungsi ajax
				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType: 'json',
					success:function(response){
						//button simpan loading reset
						$("#simpanReturnBtr").button('reset');

						if (response.success == true)
						{

							tabelRetur.ajax.reload(null, false);


							
							$("#keterangan").val("");
							$("#jmlRtr").val("");

							//reset combobox
							//$("#barang").trigger("chosen:updated");
							$("#id_det_klr").val("");
							$("#id_det_klr").trigger("chosen:updated");

							$("#id_rakRtr").val("");
							$("#id_rakRtr").trigger("chosen:updated");

							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');

							//tampil pesan true
							$('#pesanRtr').html('<div class="alert alert-success">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
							
							//fungsi tampil pesan delay
							$(".alert-success").delay(500).show(10, function() {
								$(this).delay(4000).hide(10, function() {
									$(this).remove();
								});
							});
						}
						else if(response.success == false)
						{
							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');
							//tampil pesan false
							$('#pesanRtr').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');							
						}
					}
				});
			}

			return false;
		});
		});

		$("#alternatfRetur").unbind('click').bind('click', function() {

		$("#submitAlternatfRetur").unbind('submit').bind('submit', function() {
			//variabel
			var alterFaktur1 = $("#alterFaktur1").val();
			var alterRetur   = $("#alterRetur").val();
			var alterBrg     = $("#alterBrg").val();
			var alterId_rak  = $("#alterId_rak").val();
			var alterJmlRtr  = $("#alterJmlRtr").val();
			var id_toko      = $("#id_toko").val();

			if (alterFaktur1 == "") {
				$("#alterFaktur1").before('<span class="help-inline bawah">No Faktur Alternatif Masih Kosong</span>');
				$('#alterFaktur1').closest('.control-group').addClass('error');
			}else{
				$("#alterFaktur1").find('.help-inline').remove();
				$("span").remove(":contains('No Faktur Alternatif Masih Kosong')");
			}

			if (alterRetur == "") {
				$("#alterRetur").before('<span class="help-inline bawah">No Return Alternatif Masih Kosong</span>');
				$('#alterRetur').closest('.control-group').addClass('error');
			}else{
				$("#alterRetur").find('.help-inline').remove();
				$("span").remove(":contains('No Return Alternatif Masih Kosong')");
			}

			if (alterBrg == "") {
				$("#alterBrg").before('<span class="help-inline ">Ukuran Retur Alternatif Masih Kosong</span>');
				$('#alterBrg').closest('.control-group').addClass('error');
			}else{
				$("#alterBrg").find('.help-inline').remove();
				$("span").remove(":contains('Ukuran Retur Alternatif Masih Kosong')");
			}

			if (alterId_rak == "") {
				$("#alterId_rak").before('<span class="help-inline ">Rak Retur Alternatif Masih Kosong</span>');
				$('#alterId_rak').closest('.control-group').addClass('error');
			}else{
				$("#alterId_rak").find('.help-inline').remove();
				$("span").remove(":contains('Rak Retur Alternatif Masih Kosong')");
			}

			if (alterJmlRtr == "") {
				$("#alterJmlRtr").before('<span class="help-inline ">Jumlah Retur Alternatif Masih Kosong</span>');
				$('#alterJmlRtr').closest('.control-group').addClass('error');
			}else{
				$("#alterJmlRtr").find('.help-inline').remove();
				$("span").remove(":contains('Jumlah Retur Alternatif Masih Kosong')");
			}

			if (id_toko == "") {
				$("#id_toko").before('<span class="help-inline ">Nama Toko Retur Masih Kosong</span>');
				$('#id_toko').closest('.control-group').addClass('error');
			}else{
				$("#id_toko").find('.help-inline').remove();
				$("span").remove(":contains('Nama Toko Retur Masih Kosong')");
			}
			//end cek jika kosong
			
			if (alterFaktur1 && alterRetur && alterBrg && alterId_rak && alterJmlRtr && id_toko)
			{
				//ambil data form
				var form = $(this);
				//button simpan loading
				$("#simpanReturnBtr").button('loading');

				// fungsi ajax
				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType: 'json',
					success:function(response){
						//button simpan loading reset
						$("#simpanReturnBtr").button('reset');

						if (response.success == true)
						{

							tabelRetur.ajax.reload(null, false);


							
							$("#alterKet").val("");
							$("#alterJmlRtr").val("");

							//reset combobox
							//$("#barang").trigger("chosen:updated");
							$("#alterBrg").val("");
							$("#alterBrg").trigger("chosen:updated");

							$("#alterId_rak").val("");
							$("#alterId_rak").trigger("chosen:updated");

							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');

							//tampil pesan true
							$('#pesanRtrAlter').html('<div class="alert alert-success">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
							
							//fungsi tampil pesan delay
							$(".alert-success").delay(500).show(10, function() {
								$(this).delay(4000).hide(10, function() {
									$(this).remove();
								});
							});
						}
						else if(response.success == false)
						{
							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');
							//tampil pesan false
							$('#pesanRtrAlter').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');							
						}
					}
				});
			}

			return false;
		});
		});


		$("#submitEditRetur").unbind('submit').bind('submit', function() {
			
			var form = $(this);

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){
					
					if (response.success == true)
					{

						tabelRetur.ajax.reload(null, false);

						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesanEditRetur').html('<div class="alert alert-success">'+
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
						$('#pesanEditRetur').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

					}

				}
			});

			return false;
		});


	}
	else if(divRequest == 'mutasi')
	{
		$('#activeMutasi').addClass('active');

		tabelMutasi = $('#tabelMutasi').DataTable({
			'ajax' : 'action/barangMasuk/fetchMutasi.php',
			'order':[]
		});// manage TabelMasuk
		   // 
		/*========================================> Mutasi Antar Rak <=============================*/

			$("#addMTSRakBtnModal").unbind('click').bind('click', function()
			{

			$("#submitMTSRak").unbind('submit').bind('submit', function()
			{

				var asalRakMTSRak = $("#asalRakMTSRak").val();
				//var ketMTSRak   = $("#ketMTSRak").val();
				var NoMTSRak      = $("#NoMTSRak").val();
				var id_brgMTSRak  = $("#id_brgMTSRak").val();
				var id_rakMTSRak  = $("#id_rakMTSRak").val();
				var jmlMTSRak     = $("#jmlMTSRak").val();

				if (asalRakMTSRak == "") {
					$("#asalRakMTSRak").before('<span class="help-inline ">Asal Rak Mutasi Masih Kosong. (Rak)</span>');
					$('#asalRakMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#asalRakMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('Asal Rak Mutasi Masih Kosong. (Rak)')");
				}

				if (NoMTSRak == "") {
					$("#NoMTSRak").before('<span class="help-inline ">No Mutasi Masih Kosong. (Rak)</span>');
					$('#NoMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#NoMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('No Mutasi Masih Kosong. (Rak)')");
				}

		/*		if (ketMTSRak == "") {
					$("#ketMTSRak").before('<span class="help-inline ">Keterangan Mutasi Masih Kosong. (Rak)</span>');
					$('#ketMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#ketMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('Keterangan Mutasi Masih Kosong. (Rak)')");
				}*/

				if (id_brgMTSRak == "") {
					$("#id_brgMTSRak").before('<span class="help-inline ">Ukuran Mutasi Masih Kosong. (Rak)</span>');
					$('#id_brgMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#id_brgMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('Ukuran Mutasi Masih Kosong. (Rak)')");
				}

				if (id_rakMTSRak == "") {
					$("#id_rakMTSRak").before('<span class="help-inline ">Rak Mutasi Masih Kosong. (Rak)</span>');
					$('#id_rakMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#id_rakMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('Rak Mutasi Masih Kosong')");
				}

				if (jmlMTSRak == "") {
					$("#jmlMTSRak").before('<span class="help-inline ">Jumlah Mutasi Masih Kosong. (Rak)</span>');
					$('#jmlMTSRak').closest('.control-group').addClass('error');
				}else{
					$("#jmlMTSRak").find('.help-inline').remove();
					$("span").remove(":contains('Jumlah Mutasi Masih Kosong. (Rak)')");
				}

				if (asalRakMTSRak && NoMTSRak && id_brgMTSRak && id_rakMTSRak && jmlMTSRak)
				{

					var form = $(this);
					$("#simpanMTSRakBtr").button('loading');

					$.ajax({
						url  : form.attr('action'),
						type : form.attr('method'),
						data : form.serialize(),
						dataType: 'json',
						success:function(response){

							$("#simpanMTSRakBtr").button('reset');

							if (response.success == true)
							{

								tabelMutasi.ajax.reload(null, false);
								
								//$("#keterangan").val("");
								$("#jmlMTSRak").val("");

								//reset combobox
								//$("#barang").trigger("chosen:updated");
								$("#id_brgMTSRak").val("");
								$("#id_brgMTSRak").trigger("chosen:updated");

								$("#id_rakMTSRak").val("");
								$("#id_rakMTSRak").trigger("chosen:updated");

								//hapus pesan error di filed
								$('.help-inline').remove();
								//hapus warna error di filed
								$(".control-group").removeClass('error').removeClass('success');

								//tampil pesan true
								$('#pesanMTSRak').html('<div class="alert alert-success">'+
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

								//hapus pesan error di filed
								$('.help-inline').remove();
								//hapus warna error di filed
								$(".control-group").removeClass('error').removeClass('success');
								//tampil pesan false
								$('#pesanMTSRak').html('<div class="alert alert-error">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');	

							}
						}
					});
				}

				return false;
			});//submitMTSRak

			});	//addMTSRakBtnModal


			$("#editMTSRak").unbind('submit').bind('submit', function() {

				var form = $(this);

				$.ajax({

					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType : 'json',
					success:function(response){

						if (response.success == true)
						{

							tabelMutasi.ajax.reload(null, false);

							//remove the error text
							$(".help-inline").remove();
							//remove the form error
							$(".control-group").removeClass('error').removeClass('success');
							//show messages pesan
							$('#pesanEditMutasi').html('<div class="alert alert-success">'+
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
							$('#pesanEditMutasi').html('<div class="alert alert-error">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');

						}						

					}

				});

				return false;

			});

		/*========================================> Mutasi Antar Rak <=============================*/

	}
	else{ //end if div-request
	      
		alert("Halaman Yang Anda Minta Tidak Ada");

	}


});//end document ready

function hapusRetur(id_det_msk = null){

	if (id_det_msk) {

		$.ajax({
			url: 'action/barangMasuk/fetchSelectedMasuk.php',
			type: 'post',
			data: {id_det_msk: id_det_msk},
			dataType: 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_msk+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusMasukBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusMasukBtn").button('loading');

					$.ajax({
					url: 'action/return/hapusRetur.php',
					type: 'post',
					data: {id_det_msk: id_det_msk},
					dataType: 'json',
					success:function(response) {
						
						if(response.success == true) 
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');

							tabelRetur.ajax.reload(null, false);
							//show messages pesan

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
						else if(response.success == false)
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');
							
							//show messages simapanKategori

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

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
						}
					}
					});
				});

			}
		});

	}
	else{

		$(".modal-footer").addClass('hidden');

		$("#pesanHapus").html('<strong>Data Tidak Bisa di Hapus/Ubah. Karena Sudah Perpindahan Saldo</strong>');

	}

}

function hapusReturAlter(id_det_msk = null)
{

	if (id_det_msk) {

		$.ajax({
			url: 'action/barangMasuk/fetchSelectedMasuk.php',
			type: 'post',
			data: {id_det_msk: id_det_msk},
			dataType: 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_msk+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusMasukBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusMasukBtn").button('loading');

					$.ajax({
					url: 'action/return/hapusReturAlter.php',
					type: 'post',
					data: {id_det_msk: id_det_msk},
					dataType: 'json',
					success:function(response) {
						
						if(response.success == true) 
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');

							tabelRetur.ajax.reload(null, false);
							//show messages pesan

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
						else if(response.success == false)
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');
							
							//show messages simapanKategori

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

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
						}
					}
					});
				});

			}
		});

	}
	else{

		$(".modal-footer").addClass('hidden');

		$("#pesanHapus").html('<strong>Data Tidak Bisa di Hapus/Ubah. Karena Sudah Perpindahan Saldo</strong>');

	}

}

function hapusMutasi(id_det_msk = null){

	if (id_det_msk) {

		$.ajax({
			url: 'action/barangMasuk/fetchSelectedMasuk.php',
			type: 'post',
			data: {id_det_msk: id_det_msk},
			dataType: 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_msk+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusMasukBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusMasukBtn").button('loading');

					$.ajax({
					url: 'action/barangMasuk/hapusMutasiRak.php',
					type: 'post',
					data: {id_det_msk: id_det_msk},
					dataType: 'json',
					success:function(response) {
						
						if(response.success == true) 
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');

							tabelMutasi.ajax.reload(null, false);
							//show messages pesan

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
						else if(response.success == false)
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');
							
							//show messages simapanKategori

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

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
						}
					}
					});
				});

			}
		});

	}
	else{

		$(".modal-footer").addClass('hidden');

		$("#pesanHapus").html('<strong>Data Tidak Bisa di Hapus/Ubah. Karena Sudah Perpindahan Saldo</strong>');

	}

}


//function edit retur
function editRetur(id_det_msk = null)
{

	if (id_det_msk)
	{

		$.ajax({

			url  : 'action/return/fetchSelectedRetur.php',
			type : 'post',
			data : {id_det_msk: id_det_msk},
			dataType : 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');

				$("#editFaktur").val(response.no_faktur);
				$("#editTgl").val(response.tgl);
				$("#editRetur").val(response.suratJln);
				$("#editKet").val(response.ket);
				$("#editBrg").val(response.brg);
				$("#editRak").val(response.rak);
				$("#editJml").val(response.jml_msk);
				$("#editId").val(response.id_det_msk);

			}

		});

	}

}

function editMutasi(id_det_msk = null)
{

	if (id_det_msk)
	{

		$.ajax({

			url  : 'action/barangMasuk/fetchSelectedMutasi.php',
			type : 'post',
			data : {id_det_msk: id_det_msk},
			dataType : 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');

				$("#editAsalRak").val(response.MskRak);
				$("#editTglM").val(response.tgl);
				$("#editMutasi").val(response.suratJln);
				$("#editKet").val(response.ket);
				$("#editBrgM").val(response.brg);
				$("#editRakM").val(response.rak);
				$("#editJmlM").val(response.jml_msk);
				$("#editIdDetMsk").val(response.id_det_msk);

			}

		});
	}
	
}


function editMasuk(id_det_msk = null)
{

	if (id_det_msk) 
	{

		$.ajax(
		{
			url  : 'action/barangMasuk/fetchSelectedMasuk.php',
			type : 'POST',
			data : {id_det_msk: id_det_msk},
			dataType : 'json',
			success:function(data)
			{

				$("#editTgl").val(data.tgl);

				$("#editSuratJLN").val(data.suratJln);

				$("#editBrg").val(data.brg);

				$("#editRak").val(data.rak);

				$("#editKet").val(data.ket);

				$("#editJml").val(data.jml_msk);

				$("#editIdDetMsk").val(data.id_det_msk);

				$("#editId").val(data.id);

				$("#submitEditBarangMsk").unbind('submit').bind('submit', function()
				{

					var editKet = $("#editKet").val();

					if (editKet == "") {
						$("#editKet").after('<span class="help-inline">Edit Keterangan Masih Kosong</span>');
						$("#editKet").closest('.control-group').addClass('error');
					}else{
						//$("#editKet").closest('.control-group').removeClass('error');
						$("#editKet").closest('.control-group').addClass('success');
						$("span").remove(":contains('Edit Keterangan Masih Kosong')");
					}

					if (editKet) {

						var form = $(this);

						$("#editBarangMskBtn").button('loading');

						$.ajax(
						{
							url  : form.attr('action'),
							type : form.attr('method'),
							data : form.serialize(),
							dataType : 'json',
							success:function(response1)
							{
								$("#editBarangMskBtn").button('reset');

								if (response1.success == true)
								{

									tabelMasuk.ajax.reload(null, false);

									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');

									//show messages pesan
									$('#pesan-edit').html('<div class="alert alert-success">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response1.messages+'</div>');
									//fungsi tampil pesan delay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});

								}
								else
								{
									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');
									//show messages simapanKategori
									$('#pesan-edit').html('<div class="alert alert-error">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response1.messages+'</div>');
								}
							}

						});

					}

					return false;

				});
			}
		});
	}

}

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}

function HurufBesar(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});