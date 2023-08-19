var tableEfaktur;
$(document).ready(function() {

	$("#activeEfaktur").addClass('active');

	tableEfaktur = $('#tableEfaktur').DataTable({
		'ajax' : 'action/efaktur/fetchEfaktur.php',
		'order': []
	});//manage Table Efaktur

	$('#submitEfaktur').unbind('submit').bind('submit', function() {
		var efaktur = $("#efaktur").val();

		if (efaktur == "") {
			$("#efaktur").after('<span class="help-inline">No Faktur Masih Kosong</span>');
			$("#efaktur").closest('.control-group').addClass('error');
		}else{
			$("#efaktur").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (efaktur) {
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
					if (response.success == 'cek_faktur') {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');

						$("#efaktur").closest('.control-group').addClass('error');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
					if (response.success == true) {
						tableEfaktur.ajax.reload(null, false);

						//reset the form text
						$("#submitEfaktur")[0].reset();
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
					}if (response.success == false) {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
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

function editEfaktur(id_klr = null){
	//hapus input id_klr
	$("#editIdKlr").remove();
	if(id_klr){
		//hapus input id_klr
		$("#editIdKlr").remove();
		//reset form
		$("#editEfaktur")[0].reset();
		//modal foter
		$(".modal-footer").addClass('div-hide');
		//reset class group error
		$(".control-group").removeClass('error').removeClass('success');

		$.ajax({
			url : 'action/efaktur/fetchSelectedEfaktur.php',
			type: 'POST',
			data: {id_klr: id_klr},
			dataType: 'json',
			success:function(response){
				//modal footer
				$(".modal-footer").removeClass('div-hide');
				//set faktur awal
				$("#editAwalFaktur").val(response.awal);
				//set faktur akhir
				$("#editAkhirFaktur").val(response.akhir);
				//set id klr
				$(".modal-footer").after('<input type="hidden" name="editIdKlr" id="editIdKlr" value="'+response.id_klr+'" />');
				//remove error
				$(".help-inline").remove();
				//submit form
				$("#editEfaktur").unbind('submit').bind('submit', function(){
					var editAkhirFaktur = $("#editAkhirFaktur").val();

					if (editAkhirFaktur == "") {
						$("#editAkhirFaktur").after('<span class="help-inline">No Faktur Masih Kosong</span>');
						$("#editAkhirFaktur").closest('.control-group').addClass('error');
					}else{
						$("#editAkhirFaktur").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editAkhirFaktur) {
						//ambil data form
						var form = $(this);
						//button loading
						$("#editEfakturBtn").button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response){
								//button reset
								$("#editEfakturBtn").button('reset');

								if (response.success == true) {

									//tableEfaktur.ajax.reload(null, false);
									tableEfaktur.ajax.reload(null, false);

									//remove error
									$(".help-inline").remove();
									//remove group
									$(".control-group").removeClass('error').removeClass('success');
									//show messages
									$("#edit-pesan").html('<div class="alert alert-success">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
									//fungsi tampil pesan delay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});
								}else {
									//remove error
									$(".help-inline").remove();
									//remove group
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

function hapusEfaktur(id_klr = null){

	$.ajax({
		url :'action/efaktur/fetchSelectedEfaktur.php',
		type:'POST',
		data:{id_klr: id_klr},
		dataType:'json',
		success:function(response){
			// add the keluar id
			$("#pesanHapus").html('<strong>Yakin Ingin Menghapus No Faktur '+response.no_faktur+' ?</strong>');
			//jika tombol hapus di klik
			$("#hapusEfakturBtn").unbind('click').bind('click', function(){
				$("#hapusEfakturBtn").button('loading');

				$.ajax({
					url : 'action/efaktur/hapusEfaktur.php',
					type: 'POST',
					data: {id_klr: id_klr},
					dataType: 'json',
					success:function(response){
						if (response.success == true) {
							$("#hapusEfakturBtn").button('reset');
							$("#hapusModalEfaktur").modal('hide');
							tableEfaktur.ajax.reload(null, false);
							//show pesan
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
						}else{
							$("#hapusEfakturBtn").button('reset');
							$("#hapusModalEfaktur").modal('hide');

							//show pesan
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
					        
					         setTimeout(function(){

						         $.gritter.remove(unique_id, {
						         fade: true,
						         speed: 'slow'
						         });

					         }, 6000)
						}
					}
				});
			});
			
		}
	});
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