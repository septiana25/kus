var tabel;
$(document).ready(function() {
	$(".choiceChosen, .productChosen").chosen();
	//mengambil data div class div-request
	var divRequest = $(".div-request").text();
	// active manu barang
	$('#activeMaster').addClass('active');
	
	if (divRequest == 'barang') {
		// active submenu barang masuk
		$('#activeBarang').addClass('active');

		tabel = $('#tabelBarang').DataTable({
		'ajax' : 'action/barang/fetchBarang.php',
		'order':[]
	});// manage TabelMasuk

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

	$('#addBarangBtnModal').unbind('click').bind('click', function() {

	$('#submitBarang').unbind('submit').bind('submit', function() {

		var kategori = $("#kategori").val();
		var KDbarang = $("#KDbarang").val();
		var barang = $("#barang").val();
		var NOurut = $("#NOurut").val();

		if (kategori == "") {
			$("#kategori").after('<span class="help-inline">Kategori Masih Kosong</span>');
			$("#kategori").closest('.control-group').addClass('error');
		}else{
			$("#kategori").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}
		if (KDbarang == "") {
			$("#KDbarang").after('<span class="help-inline">Kode Barang Masih Kosong</span>');
			$("#KDbarang").closest('.control-group').addClass('error');
		}else{
			$("#KDbarang").closest('.control-group').addClass('success');
			
		}
		if (barang == "") {
			$("#barang").after('<span class="help-inline">Nama Barang Masih Kosong</span>');
			$("#barang").closest('.control-group').addClass('error');
		}else{
			$("#barang").closest('.control-group').addClass('success');
			
		}
		if (NOurut == "") {
			$("#NOurut").after('<span class="help-inline">Nomor Urut Masih Kosong</span>');
			$("#NOurut").closest('.control-group').addClass('error');
		}else{
			$("#NOurut").closest('.control-group').addClass('success');
			
		}
		
		if (kategori && KDbarang && barang && NOurut) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanBarangBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {

					$("#simpanBarangBtn").button('reset');

					if (response.success == true) {

						tabel.ajax.reload(null, false);

						//reset the form text
						$("#submitBarang")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//reset combobox
						$("#kategori").trigger("chosen:updated");
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
					if (response.success == false) {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
					if (response.success == 'cek_brg') {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');

						$("#barang").closest('.control-group').addClass('error');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
				}
			});
		}

		return false;
	});//submit categories form function
	});//on click on submit barang form modal

	}
	
	$("#reload").unbind('click').bind('click', function(){
		tabel.ajax.reload(null, false);

		$("#reload").button('loading');

		$("#reload").delay(300).show(10, function() {
				$("#reload").button('reset');
			// $(this).delay(4000).hide(10, function() {
			// });
		});

		
	});

	/*$("#exportLapBrgExcelBtn").unbind('click').bind('click', function(){

			var mywindow = window.open('action/laporan/exportExcelMasterBarang.php', 'Laporan Master Barang', '');

	});*/

	$('#exportLapBrgExcelBtn').unbind('click').bind('click', function(){

		$('#modalCariData').on('shown', function () {
		  $('#cariBulan').focus();
		});

		$('#submitLapExcelBrg').unbind('submit').bind('submit', function(){

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
				$("#modalCariData").modal('hide');

				var mywindow = window.open('action/laporan/exportExcelMasterBarang.php?b='+cariBulan+'&t='+cariTahun, 'Laporan Transaksi', '');

			}

			return false;
		});
		
	});


});// /document

// edit barang function
function editBarang(id_brg = null){
	// remove the added barang id 
	$('#editBarangId').remove();
	if (id_brg) {
		// remove the added barang id 
		$('#editBarangId').remove();
		// reset the form text
		$("#editBarangForm")[0].reset();
		//modal footer
		$(".modal-footer").addClass('div-hide');
		// reset the form group errro		
		$(".control-group").removeClass('error').removeClass('success');

		
		$.ajax({
			url: 'action/barang/fetchSelectedBarang.php',
			type: 'post',
			data: {id_brg: id_brg},
			dataType: 'json',
			success:function(response) {
				// alert('tes');
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// set the categories name
				$("#editKategori").val(response.id_kat);
				// set the barang status
				$("#editNOurut").val(response.nourt);
				// set the barang status
				$("#editKDbarang").val(response.kdbrg);
				// set the barang status
				$("#editBarang").val(response.brg);
				// add the categories id
				$(".modal-footer").after('<input type="hidden" name="editBarangId" id="editBarangId" value="'+response.id_brg+'" />');
				$(".help-inline").remove();
				// submit of edit categories form
				$("#editBarangForm").unbind('submit').bind('submit', function() {
					
					var editKategori = $("#editKategori").val();
					var editKDbarang = $("#editKDbarang").val();
					var editBarang = $("#editBarang").val();
					var editNOurut = $("#editNOurut").val();

					if (editKategori == "") {
						$("#editKategori").after('<span class="help-inline">Kategori Masih Kosong</span>');
						$("#editKategori").closest('.control-group').addClass('error');
					}else{
						$("#editKategori").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}
					if (editKDbarang == "") {
						$("#editKDbarang").after('<span class="help-inline">Kode Barang Masih Kosong</span>');
						$("#editKDbarang").closest('.control-group').addClass('error');
					}else{
						$("#editKDbarang").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}
					if (editBarang == "") {
						$("#editBarang").after('<span class="help-inline">Barang Masih Kosong</span>');
						$("#editBarang").closest('.control-group').addClass('error');
					}else{
						$("#editBarang").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}if (editNOurut == "") {
						$("#editNOurut").after('<span class="help-inline">Nomor Urut Masih Kosong</span>');
						$("#editNOurut").closest('.control-group').addClass('error');
					}else{
						$("#editNOurut").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editKategori && editKDbarang && editBarang && editNOurut) {
						//ambil data form
						var form = $(this);
						//button loading
						$("#editBarangBtn").button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response) {
								// button reset
								$("#editBarangBtn").button('reset');
								//jika berhasil disimpan
								if (response.success == true) {

									tabel.ajax.reload(null, false);

									//reset the form text
									//$("#editBarangForm")[0].reset();
									//remove the error text
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
								//jika gagal disimpan
								if (response.success == false) {
									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');
									//show messages pesan
									$('#edit-pesan').html('<div class="alert alert-error">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response.messages+'</div>');
								}
							}
						});
					}
				return false;
				});// /submit of edit categories form
			}

		});

	} else {
		alert('Oops!! Refresh the page');
	}
}

function hapusBarang(id_brg = null){
	$.ajax({
			url: 'action/barang/fetchSelectedBarang.php',
			type: 'post',
			data: {id_brg: id_brg},
			dataType: 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusBarangBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusBarangBtn").button('loading');

					$.ajax({
					url: 'action/barang/hapusBarang.php',
					type: 'post',
					data: {id_brg: id_brg},
					dataType: 'json',
					success:function(response) {
						
						if (response.success == 'cek_brg') {
							tabel.ajax.reload(null, false);
							$("#hapusBarangBtn").button('reset');
							// close the modal 
							$("#hapusModalBarang").modal('hide');
							//show messages pesan
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
						if (response.success == true) {
							tabel.ajax.reload(null, false);
							$("#hapusBarangBtn").button('reset');
							// close the modal 
							$("#hapusModalBarang").modal('hide');
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
						}if (response.success == false){
							$("#hapusBarangBtn").button('reset');
							// close the modal 
							$("#hapusModalBarang").modal('hide');
							//show messages pesan
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

function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
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