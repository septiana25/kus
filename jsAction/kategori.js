var tabelKategori;

$(document).ready(function(){
	//mengambil data div class div-request
	var divRequest = $(".div-request").text();
	// active manu barang
	$('#activeMaster').addClass('active');
	//jika class div-request ada data kategori
	if (divRequest == 'kategori') {
		// active submenu barang kategori
		$('#activeKategori').addClass('active');

		tabelKategori = $('#tabelKategori').DataTable({
			'ajax' : 'action/kategori/fetchKategori.php',
			'order': []
		});// manage kategori Data Table

		// on click on submit kategori dari modal
		$('#addKategoriBtnModal').unbind('click').bind('click', function() {
			// reset the form text
			//$("#submitKategori")[0].reset();
			//remove the error text
			//$(".help-inline").remove();
			//$(".control-group").removeClass('error').remove('success');
			// submit kategori dari function
			$('#submitKategori').unbind('submit').bind('submit', function(){
				//mengambil data id namaKat di form
				var namaKat = $("#namaKat").val();
				//cek variabel namaKat apakah kosong
				
				if (namaKat == "") {
					$("#namaKat").after('<span class="help-inline">Nama Kategori Masih Kosong</span>');
					$("#namaKat").closest('.control-group').addClass('error');
				}else{
					$("#editKategori").closest('.control-group').addClass('success');
					$(".help-inline").remove();
				}
				//jika semua data terisi
				if(namaKat){
					var form = $(this);
					//button loading
					$("#simpanKategoriBtn").button('loading');

					$.ajax({//proses simpan
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),
						dataType: 'json',
						success:function(response) {
							//button loading
							$("#simpanKategoriBtn").button('reset');

							if (response.success == true) {
								//reload manage kategori Data Table
								tabelKategori.ajax.reload(null, false);

								// reset the form text
								$("#submitKategori")[0].reset();
								//remove the error text
								$(".help-inline").remove();
								//remove the form error
								$(".control-group").removeClass('error').remove('success');
								//show messages simapanKategori
								$('#pesan').html('<div class="alert alert-success">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');

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
								$(".control-group").removeClass('error').remove('success');
								//show messages simapanKategori
								$('#pesan').html('<div class="alert alert-error">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
							}
							if (response.success == 'cek_kat') {
								//remove the error text
								$(".help-inline").remove();
								//remove the form error
								$(".control-group").removeClass('error').remove('success');

								$("#namaKat").closest('.control-group').addClass('error');
								//show messages simapanKategori
								$('#pesan').html('<div class="alert alert-error">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
							}
						}
					});
				}

				
				return false;
			});
		});
	}//end jika class div-request ada data kategori
});

function editKategori(id_kat = null){
	// remove the added barang id 
	$('#editKategoriId').remove();
	if (id_kat) {
		// remove the added barang id 
		$('#editKategoriId').remove();
		// reset the form text
		$("#editKategoriForm")[0].reset();
		//modal footer
		$(".modal-footer").addClass('div-hide');

		$.ajax({
			url: 'action/kategori/fetchSelectedKategori.php',
			type: 'post',
			data: {id_kat: id_kat},
			dataType: 'json',
			success:function(response) {
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// set the barang status
				$("#editKategori").val(response.kat);
				// add the categories id
				$(".modal-footer").after('<input type="hidden" name="editKategoriId" id="editKategoriId" value="'+response.id_kat+'" />');
				// submit of edit categories form
				$("#editKategoriForm").unbind('submit').bind('submit', function() {
					var editKategori = $("#editKategori").val();

					if (editKategori == "") {
						$("#editKategori").after('<span class="help-inline">Kategori Masih Kosong</span>');
						$("#editKategori").closest('.control-group').addClass('error');
					}else{
						$("#editKategori").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}
					if (editKategori) {
						//ambil data form
						var form = $(this);
						//button loading
						$("#editKategoriBtn").button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response) {
								if (response.success == true) {
									//button loading
									$("#editKategoriBtn").button('reset');
									//reload manage kategori Data Table
									tabelKategori.ajax.reload(null, false);
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
								}else{
									//remove the error text
									$(".help-inline").remove();
									//show messages pesan
									$('#edit-pesan').html('<div class="alert alert-error">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
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
function hapusKategori(id_kat = null){
	$.ajax({
			url: 'action/kategori/fetchSelectedKategori.php',
			type: 'post',
			data: {id_kat: id_kat},
			dataType: 'json',
			success:function(response) {
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.kat+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusKategoriBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusKategoriBtn").button('loading');

					$.ajax({
						url: 'action/kategori/hapusKategori.php',
						type: 'post',
						data: {id_kat: id_kat},
						dataType: 'json',
						success:function(response) {

							if (response.success == 'cek_kat') {
								
								tabelKategori.ajax.reload(null, false);
								$("#hapusKategoriBtn").button('reset');
								// close the modal 
								$("#hapusModalKategori").modal('hide');
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

						        // You can have it return a unique id, this can be used to manually remove it later using
						        
							}
							if (response.success == true) {

								tabelKategori.ajax.reload(null, false);
								$("#hapusKategoriBtn").button('reset');
								// close the modal 
								$("#hapusModalKategori").modal('hide');
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
							}if (response.success == false) {

								tabelKategori.ajax.reload(null, false);
								
								$("#hapusKategoriBtn").button('reset');
								// close the modal 
								$("#hapusModalKategori").modal('hide');
								//remove the error text
								$(".help-inline").remove();
								//remove the form error
								$(".control-group").removeClass('error').removeClass('success');
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

						        // You can have it return a unique id, this can be used to manually remove it later using
						        
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

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});