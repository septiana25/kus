var tabelRak;
$(document).ready(function() {

	//
	var divRequest = $(".div-request").text();
	//
	$('#activeMaster').addClass('active');

	if (divRequest == 'rak') {
		$('#activeRak').addClass('active');

		tabelRak = $('#tabelRak').DataTable({
		'ajax' : 'action/rak/fetchRak.php',
		'order': []
		});//manage Table Rak
	
	$('#addRakBtnModal').unbind('click').bind('click', function() {

	$('#submitRak').unbind('submit').bind('submit', function() {

		var rak = $("#rak").val();

		if (rak == "") {
			$("#rak").after('<span class="help-inline">Lokasi Rak Masih Kosong</span>');
			$("#rak").closest('.control-group').addClass('error');
		}else{
			$("#rak").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (rak) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanRakBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					$("#simpanRakBtn").button('reset');
					if (response.success == true) {

						tabelRak.ajax.reload(null, false);

						//reset the form text
						$("#submitRak")[0].reset();
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
					if (response.success == 'cek_rak') {
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
	});
	});

	}
});//document

function editRak(id_rak = null){
	// remove the added barang id 
	$('#editRakId').remove();
	if (id_rak) {
		// remove the added barang id 
		$('#editRakId').remove();
		// reset the form text
		$("#editRakForm")[0].reset();
		//modal footer
		$(".modal-footer").addClass('div-hide');
		$.ajax({
			url: 'action/rak/fetchSelectedRak.php',
			type: 'post',
			data: {id_rak: id_rak},
			dataType: 'json',
			success:function(response) {
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// set the rak status
				$("#editNamaRak").val(response.rak);
				// add the categories id
				$(".modal-footer").after('<input type="hidden" name="editRak" id="editRak" value="'+response.id_rak+'" />');
				//remove 
				$(".help-inline").remove();
				$("#editRakForm").unbind('submit').bind('submit', function() {
					var editNamaRak = $("#editNamaRak").val();

					if (editNamaRak =="") {
						$("#editNamaRak").after('<span class="help-inline">Kategori Masih Kosong</span>');
						$("#editNamaRak").closest('.control-group').addClass('error');
					}else{
						$("#editNamaRak").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editNamaRak) {
						//ambil data form
						var form = $(this);
						//button loading
						$("#editRakBtn").button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response) {
								$("#editRakBtn").button('reset');

								if (response.success == true) {
									//reload tabel
									tabelRak.ajax.reload(null, false);
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
				});
			}
		});
	}
}

function hapusRak(id_rak = null){
	$.ajax({
			url: 'action/rak/fetchSelectedRak.php',
			type: 'post',
			data: {id_rak: id_rak},
			dataType: 'json',
			success:function(response) {
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.rak+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusRakBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusRakBtn").button('loading');
					
					$.ajax({
					url: 'action/rak/hapusRak.php',
					type: 'post',
					data: {id_rak: id_rak},
					dataType: 'json',
					success:function(response) {
						if (response.success == 'cek_rak') {
							tabelRak.ajax.reload(null, false);
							$("#hapusRakBtn").button('reset');
							// close the modal 
							$("#hapusModalRak").modal('hide');
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
						if (response.success == true) {

							tabelRak.ajax.reload(null, false);

							$("#hapusRakBtn").button('reset');
							// close the modal 
							$("#hapusModalRak").modal('hide');
							//remove the error text
							$(".help-inline").remove();
							//remove the form error
							$(".control-group").removeClass('error').removeClass('success');
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

							tabelRak.ajax.reload(null, false);

							$("#hapusRakBtn").button('reset');
							// close the modal 
							$("#hapusModalRak").modal('hide');
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