var tabelMinus;
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

	//mengambil data div class div-request
	var divRequest = $(".div-request").text();
	// active manu barang
	$('#activeMaster').addClass('active');

	if (divRequest == 'minus') {

		tabelMinus = $("#tabelMinus").DataTable({
			'ajax' : 'action/barang/fetchMinus.php',
			'order' : [],
		});

		$('#activeKoreksiMin').addClass('active');

		$("#addMinusBtnModal").unbind('click').bind('click', function() {

			$("#submitMinus").unbind('submit').bind('submit', function() {

				var brg = $("#id_brgMinus").val();
				var rak = $("#id_rakMinus").val();
				var jml = $("#jmlMinus").val();
/*				var ket = $("#ketMinus").val();

				if (ket == "") {
					$("#ketMinus").before('<span class="help-inline bawah">Nama Barang Masih Kosong</span>');
					$('#ketMinus').closest('.control-group').addClass('error');
				}
				else
				{
					$("#ketMinus").find('.help-inline').remove();
					$("span").remove(":contains('Nama Barang Masih Kosong')");
				}*/

				if (brg == "") {
					$("#id_brgMinus").before('<span class="help-inline bawah">Nama Barang Masih Kosong</span>');
					$('#id_brgMinus').closest('.control-group').addClass('error');
				}
				else
				{
					$("#id_brgMinus").find('.help-inline').remove();
					$("span").remove(":contains('Nama Barang Masih Kosong')");
				}

				if (rak == "") {
					$("#id_rakMinus").before('<span class="help-inline bawah">Nama Barang Masih Kosong</span>');
					$('#id_rakMinus').closest('.control-group').addClass('error');
				}
				else
				{
					$("#id_rakMinus").find('.help-inline').remove();
					$("span").remove(":contains('Nama Barang Masih Kosong')");
				}

				if (jml == "") {
					$("#jmlMinus").before('<span class="help-inline bawah">Jumlah Koreksi Masih Kosong</span>');
					$('#jmlMinus').closest('.control-group').addClass('error');
				}
				else
				{
					$("#jmlMinus").find('.help-inline').remove();
					$("span").remove(":contains('Jumlah Koreksi Masih Kosong')");
				}


				if (brg && rak && jml) {


					$("#simpanMinusBtn").button('loading');
					var form = $(this);

					$.ajax({
						url   : form.attr('action'),
						type  : form.attr('method'),
						data  : form.serialize(),
						dataType : 'json',
						success:function(response)
						{

							$("#simpanMinusBtn").button('reset');

							if (response.success == true) {

								tabelMinus.ajax.reload(null, false);

								//hapus pesan error di filed
								$('.help-inline').remove();
								//hapus warna error di filed
								$(".control-group").removeClass('error').removeClass('success');

								//tampil pesan true
								$('#pesanMinus').html('<div class="alert alert-success">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');

							}

							else if (response.success == false) {

								//hapus pesan error di filed
								$('.help-inline').remove();
								//hapus warna error di filed
								$(".control-group").removeClass('error').removeClass('success');
								//tampil pesan false
								$('#pesanMinus').html('<div class="alert alert-error">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
							}

						}
					});


				}

				return false;



			});

		});//akhir fungsi klik tambah data

		$("#editMinusForm").unbind('submit').bind('submit', function() {
				
			var form = $(this);

			$("#editMinusBtn").button('loading');

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType :'json',
				success:function(response){

					$("#editMinusBtn").button('reset');

					if (response.success === true)
					{

						tabelMinus.ajax.reload(null, false);
						//hapus pesan error di filed
						$('.help-inline').remove();
						//hapus warna error di filed
						$(".control-group").removeClass('error').removeClass('success');

						//tampil pesan true
						$('#pesanEditMinus').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});

					}
					else if (response.success === false)
					{

						//hapus pesan error di filed
						$('.help-inline').remove();
						//hapus warna error di filed
						$(".control-group").removeClass('error').removeClass('success');
						//tampil pesan false
						$('#pesanEditMinus').html('<div class="alert alert-error">'+
						'<button class="close" data-dismiss="alert">×</button>'+
						response.messages+'</div>');

					}

				}
			});

			return false;				

		});

	}

	$("#hapusKoreksiBtn").unbind('click').bind('click', function(){

		$("#hapusKoreksiBtn").button('loading');

		var status_klr = $("#status_klr").val();
		var id_klr     = $("#hapusId_klr").val();
		var hapusId     = $("#hapusId").val();
		var jml_klr    = $("#hapusJml_klr").val();
		var url;

		if ( status_klr == 1 )
		{
			url1 = "action/barang/hapusMinus.php";
		}
		else if ( status_klr == 2 )
		{
			url1 = "action/barang/hapusPlus.php";
		}

		$.ajax({
			url  : url1,
			type : "POST",
			data : {id_klr : id_klr, jml_klr : jml_klr, hapusId : hapusId},
			dataType : "json",
			success:function(response){

				$("#hapusKoreksiBtn").button('loading');

				if (response.success == true)
				{

					//button reset
					$("#hapusKoreksiBtn").button('reset');
					// close the modal 
					$("#hapusModalKoreksi").modal('hide');

					tabelMinus.ajax.reload(null, false);
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
				else if (response.success == false)
				{

					//button reset
					$("#hapusKoreksiBtn").button('reset');
					// close the modal 
					$("#hapusModalKoreksi").modal('hide');
					
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

});


function editMinus(id_det_klr = null)
{
	
	if (id_det_klr)
	{

		$.ajax({
			url : "action/barang/fetchSelectedMinus.php",
			type: "POST",
			data: {id_det_klr : id_det_klr},
			dataType : "json",
			success:function(response){
				//modal footer
				$(".modal-footer").removeClass('hidden');

				$("#editIdDetKlr").val(response.id_det_klr);
				$("#editketMinus").val(response.ket);
				$("#edittglMinus").val(response.tgl);
				$("#editBrgMinus").val(response.brg);
				$("#editRakMinus").val(response.rak);
				$("#editJmlMinus").val(response.jml_klr);

			}
		});

	}

}

function hapusMinus(id_det_klr = null)
{
	
	if (id_det_klr)
	{

		$.ajax({
			url : "action/barang/fetchSelectedMinus.php",
			type: "POST",
			data: {id_det_klr : id_det_klr},
			dataType : "json",
			success:function(response){
				//modal footer
				$(".modal-footer").removeClass('hidden');

				$("#status_klr").val(response.status_klr);
				$("#hapusId_klr").val(response.id_klr);
				$("#hapusId").val(response.id_brg_det);
				$("#hapusJml_klr").val(response.jml_klr);
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_klr+'?</strong>');

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