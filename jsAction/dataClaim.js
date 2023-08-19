var tabelClaim;
$(document).ready(function() {
	var divRequest = $(".div-request").text();
	$('#activeClaim').addClass('active');//active menu

	$('.datepicker').datepicker();

	if (divRequest == 'dataClaim') {
		$('#activeDataClaim').addClass('active');//active sub menu

		tabelClaim = $('#tabelClaim').DataTable({
			'ajax' : 'action/claim/fetchClaim.php',
			'order':[]
		});//manage TableClaim
	}else if(divRequest == 'Nota') {
		$('#activeDataClaim').addClass('active');//active sub menu
		
		var hiddenBtn = $(".hiddenBtn").text();
		if (hiddenBtn == 'hiddenBtn') {
			$("#simpanNotaBtn").addClass('hidden');
			alert('Nota Sudah Di Buat. Untuk Print Ulang Di Menu Nota Penggantian Atau Nota Tolakan');
			window.location.href="notaPenggantian.php";
		}

		$("#submitNota").unbind('submit').bind('submit', function() {
			var noReg = $("#noReg").val();
			var toko = $("#toko").val();
			var totalID = $("#totalID").val();

			if (toko == "") {
				$("#toko").after('<span class="error help-inline">Nama Toko Masih Kosong</span>');
				$("#toko").closest('#toko').addClass('error');
			}else{
				$("#toko").closest('.control-group').addClass('success');
				$("#toko").find('.error .help-inline').remove();				
			}
/*			var noCM = document.getElementsByName('noCM[]');
			var validateNoCM;
				// alert(noCM);
			for (var x = 0; x < noCM.length; x++) {
				var productNameId = noCM[x].id;
				alert("tes "+productNameId);
			}
*/

			// if (noReg == "") {
			// 	$("#noReg").after('<span class="error help-inline">No Register Masih Kosong</span>');
			// 	$("#noReg").closest('#noReg').addClass('error');
			// }else{
			// 	$("#noReg").closest('#noReg').addClass('success');
			// 	$("#noReg").find('.error .help-inline').remove();
			// }

			if (toko) {
				//ambil data form
				var form = $(this);
				//button loading
				$("#simpanNotaBtn").button('loading');

				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType: 'json',
					success:function(response){
						$("#simpanNotaBtn").button('reset');//button reset ketika success

						if (response.success == true) {
							$("#simpanNotaBtn").addClass('hidden');
							// $("#printNotaBtn").removeClass('hidden');
							// 
							
							$(".print").html('<a href="" role="button" class="btn btn-success tambah"'+
							'onclick="printPenggantian('+response.idNota+')"> <i class="fa fa-print"></i> Print</a>');

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
						}else if (response.success == false){
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
						}else if (response.success == 'cek_nota'){

						}
					}
				});

			}
			return false;
		});
	}

});

function printPenggantian(idNota = null){
	if (idNota) {
		$.ajax({
			url  : 'action/claim/printNota.php',
			type : 'POST',
			data : {idNota: idNota},
			dataType : 'text',
			success:function(response){
				var mywindow = window.open('', 'Apliksai Inventori Gudang KTA', 'height=400,width=600');
				mywindow.document.write('<html><head>');
				mywindow.document.write('</head><body>');
				mywindow.document.write(response);
				mywindow.document.write('</body></html>');

				mywindow.document.close();// necessary for IE >= 10
				mywindow.focus();// necessary for IE >= 10

				mywindow.print();
				mywindow.close();
			}
		});
	}else{
		alert('Nota Tidak Bisa Di Print');
	}
}

function editClaim(id_claim = null){
	if (id_claim) {
		//hapus input editIdClaim
		$('#editIdClaim').remove();
		// reset the form text
		$("#submitEditClaim")[0].reset();
		//modal footer
		$(".modal-footer").addClass('div-hide');

		$.ajax({
			url : 'action/claim/fetchSelectedClaim.php',
			type: 'post',
			data: {id_claim : id_claim},
			dataType: 'json',
			success:function(response){
				//unhide modal footer 
				$('.modal-footer').removeClass('div-hide');
				//isi input toko
				$('#editToko').val(response.toko);
				//isi input ukuran
				$('#editUkuran').val(response.brg);
				//isi input keputusan
				$('#editKeputusan').val(response.keputusan);
				//isi input nomonal
				if (response.keputusan == 'Proses') {
					$('#editNominal').val('');
				}else{
					$('#editNominal').val(response.nominal);
				}
				//tambaha input id_claim
				$(".modal-footer").after('<input type="hidden" name="editIdClaim" id="editIdClaim" value="'+response.id_claim+'" />');

				$('#submitEditClaim').unbind('submit').bind('submit', function() {

					var editKeputusan = $('#editKeputusan').val();
					var editNominal   = $('#editNominal').val();

					if (editKeputusan == '') {
						$("#editKeputusan").after('<span class="help-inline">Keputusan Masih Kosong</span>');
						$("#editKeputusan").closest('.control-group').addClass('error');
					}else{
						$("#editKeputusan").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editNominal == '') {
						$("#editNominal").after('<span class="help-inline">Nominal Masih Kosong</span>');
						$("#editNominal").closest('.control-group').addClass('error');
					}else{
						$("#editNominal").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editKeputusan && editNominal) {
						//ambil data form
						var form = $(this);

						$('#simpanEditClaimBtn').button('loading');

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: form.serialize(),
							dataType: 'json',
							success:function(response){
								$('#simpanEditClaimBtn').button('reset');

								if (response.success == true) {
									tabelClaim.ajax.reload(null, false);

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
	}else{
		alert('Oops!! Refresh the page');
	}
}

function hapusClaim(id_claim = null){
	if (id_claim) {
		$.ajax({
			url : 'action/claim/fetchSelectedClaim.php',
			type: 'post',
			data: {id_claim: id_claim},
			dataType:'json',
			success:function(response){
				$(".modal-footer").removeClass('div-hide');	
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus Nomor Pengaduan : '+response.pengaduan+'?</strong>');

				$('#hapusClaimBtn').unbind('click').bind('click', function() {
					$.ajax({
						url : 'action/claim/hapusClaim.php',
						type: 'post',
						data: {id_claim: id_claim},
						dataType:'json',
						success:function(response){
							if (response.success == true) {
								$('#hapusModalClaim').modal('hide');

								tabelClaim.ajax.reload(null, false);

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
								$('#hapusModalClaim').modal('hide');

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
	}else{
		alert('Oops!! Refresh the page');
	}
}


function HurufBesar(a){
	setTimeout(function() {
		a.value = a.value.toUpperCase();
	}, 1);
}

function validAngka(a)
{
	if (!/^[0-9.]+$/.test(a.value))
	{
		a.value = a.value.substring(0,a.value.length-1000);
	}
}