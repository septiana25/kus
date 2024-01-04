var tabelMasuk;
$(document).ready(function() {
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $(".chosen-select").chosen({width: "200%"});
   /* $(".chosen-select1").chosen({width: "95%"});
    $(".chosen-select1").on('change', function (event,el) {
      var selected_element = $(".chosen-select1 option:contains("+el.selected+")");
      
      var selected_value  = selected_element.val();
      var parent_optgroup = selected_element.closest('optgroup').attr('label');
      
      selected_element.text(parent_optgroup+' - '+selected_value).trigger("chosen:updated");
    });*/
	//mengambil data div class div-request
	var divRequest = $(".div-request").text();
	// active manu barang
	$('#activeTransaksi').addClass('active');
	
	if (divRequest == 'masuk') {
		// active submenu barang masuk
		$('#activeBarangMasuk').addClass('active');

		tabelMasuk = $('#tabelMasuk').DataTable({
		'ajax' : 'action/barangMasuk/fetchMasuk.php',
		'order':[]
	});// manage TabelMasuk

	$('.datepicker').datepicker();

	// on click on submit barang masuk dari modal
	$('#addBarangMskBtnModal').unbind('click').bind('click', function() {

	// reset the form text
	//$("#submitBarangMsk")[0].reset();
	//remove the error text
	//$(".help-inline").remove();

	//$(".control-group").removeClass('error').removeClass('success');
	// submit kategori dari function
	$('#submitBarangMsk').unbind('submit').bind('submit', function() {
		
		var barang   = $("#barang").val();
		var rak      = $("#rak").val();
		var suratJLN = $("#suratJLN").val();
		//var ket      = $("#ket").val();
		var jumlah   = $("#jml").val();

		//cek barang rak jumlah jika kosong
		if (barang == "") {
			$("#barang").after('<span class="help-inline">Nama Barang Masuk Masih Kosong</span>');
			$("#barang").closest('.control-group').removeClass('success');
			$("#barang").closest('.control-group').addClass('error');
		}else{
			$("#barang").closest('.control-group').removeClass('error');
			$("#barang").closest('.control-group').addClass('success');
			$("span").remove(":contains('Nama Barang Masuk Masih Kosong')");
		}

		if (rak == "") {
			$("#rak").after('<span class="help-inline">Lokasi Rak Masuk Masih Kosong</span>');
			$("#rak").closest('.control-group').removeClass('success');
			$("#rak").closest('.control-group').addClass('error');
		}else{
			$("#rak").closest('.control-group').removeClass('error');
			$("#rak").closest('.control-group').addClass('success');
			$("span").remove(":contains('Lokasi Rak Masuk Masih Kosong')");
		}

		if (suratJLN == "") {
			$("#suratJLN").after('<span class="help-inline">Surat JaLan Masuk Masih Kosong</span>');
			$("#suratJLN").closest('.control-group').removeClass('success');
			$("#suratJLN").closest('.control-group').addClass('error');
		}else{
			$("#suratJLN").closest('.control-group').removeClass('error');
			$("#suratJLN").closest('.control-group').addClass('success');
			//$("#suratJLN").closest('span').removeClass();
			$("span").remove(":contains('Surat JaLan Masuk Masih Kosong')");
		}

/*		if (ket == "") {
			$("#ket").after('<span class="help-inline">Keterangan Masuk Masih Kosong</span>');
			$("#ket").closest('.control-group').removeClass('success');
			$("#ket").closest('.control-group').addClass('error');
		}else{
			$("#ket").closest('.control-group').removeClass('error');
			$("#ket").closest('.control-group').addClass('success');
			$("span").remove(":contains('Keterangan Masuk Masih Kosong')");
		}
*/
		if (jumlah == "") {
			$("#jml").after('<span class="help-inline">Jumlah Masih Kosong</span>');
			$("#jml").closest('.control-group').removeClass('success');
			$("#jml").closest('.control-group').addClass('error');
		}else{
			$("#jml").closest('.control-group').removeClass('error');
			$("#jml").closest('.control-group').addClass('success');
			$("span").remove(":contains('Jumlah Masih Kosong')");
		}
		//end cek barang rak jumlah jika kosong
		//jika semua data terisi
		if (barang && rak && suratJLN && jumlah) {
			//ambil data form
			var form = $(this);
			//button loading
			//$("#simpanBarangMskBtn").button('loading');

			$.ajax({//proses simpan
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					//button reset
					$("#simpanBarangMskBtn").button('reset');

					if (response.success == true) {

						tabelMasuk.ajax.reload(null, false);


						//reset the form text
						$("#ket").val("");
						$("#jml").val("");

						//reset combobox
						//$("#barang").trigger("chosen:updated");
						$("#rak").val("");
						$("#rak").trigger("chosen:updated");

						document.getElementById("suratJLN").focus();

						//remove the error text
						$(".help-inline").remove();
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
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages simapanKategori
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}

					else{
						alert("Error");
					}
				}

			});

		}

		return false;
	});
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


	} //end if div-request


});//end document ready

function hapusMasuk(id_det_msk = null){

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
					url: 'action/barangMasuk/hapusMasuk.php',
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

							tabelMasuk.ajax.reload(null, false);

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

function editMasuk(id_det_msk = null){

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