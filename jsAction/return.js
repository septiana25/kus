var tableReturn;
$(document).ready(function() {

	//aktifkan combobox dropdown
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
	//aktive menu transaksi
	$('#activeTransaksi').addClass('active');

	//mengambil data div class div-request
	var divRequest = $(".div-request").text();

	if (divRequest == 'return') {
		//active submenu return
		$('#activeReturn').addClass('active');

		tableReturn = $('#tabelReturn').DataTable({
			'ajax'	: 'action/return/fetchReturn.php',
			'order' : []
		});//manage TabaleReturn

		$("#nofak").change(function () {
			$("#nofak option:selected").each(function () {
				nofak = $(this).val();
				$.post("action/return/fetchSelectedUkuran.php", { nofak: nofak }, function(data){
					$("#id_brg").html(data);
				});            
			});		
		});


		$("#addReturnBtnModal").unbind('click').bind('click', function() {

			// $('#myModal1').on('shown.bs.modal', function () {
			//   // get the locator for an input in your modal. Here I'm focusing on
			//   // the element with the id of myInput
			//   $('#jml').focus()
			// })

		$("#submitReturn").unbind('submit').bind('submit', function() {
			//variabel
			var nofak        = $("#nofak").val();
			var fakturReturn = $("#fakturReturn").val();
			//var keterangan   = $("#keterangan").val();
			var id_brg       = $("#id_brg").val();
			var id_rak       = $("#id_rak").val();
			var jml          = $("#jml").val();

			//cek setiap filed input
			if (nofak == "") {
				$("#nofak").before('<span class="help-inline bawah">No Faktur Masih Kosong</span>');
				$('#nofak').closest('.control-group').addClass('error');
			}else{
				$("#nofak").find('.help-inline').remove();
				$("#nofak").closest('.control-group').addClass('success');
			}

			if (fakturReturn == "") {
				$("#fakturReturn").before('<span class="help-inline bawah">No Return Masih Kosong</span>');
				$('#fakturReturn').closest('.control-group').addClass('error');
			}else{
				$("#fakturReturn").find('.help-inline').remove();
				$("#fakturReturn").closest('.control-group').addClass('success');
			}

			// if (keterangan == "") {
			// 	$("#keterangan").before('<span class="help-inline ">keteranganerangan Masih Kosong</span>');
			// 	$('#keterangan').closest('.control-group').addClass('error');
			// }else{
			// 	$("#keterangan").find('.help-inline').remove();
			// 	$("#keterangan").closest('.control-group').addClass('success');
			// }

			if (id_brg == "") {
				$("#id_brg").before('<span class="help-inline ">Ukuran Masih Kosong</span>');
				$('#id_brg').closest('.control-group').addClass('error');
			}else{
				$("#id_brg").find('.help-inline').remove();
				$("#id_brg").closest('.control-group').addClass('success');
			}

			if (id_rak == "") {
				$("#id_rak").before('<span class="help-inline ">Rak Masih Kosong</span>');
				$('#id_rak').closest('.control-group').addClass('error');
			}else{
				$("#id_rak").find('.help-inline').remove();
				$("#id_rak").closest('.control-group').addClass('success');
			}

			if (jml == "") {
				$("#jml").before('<span class="help-inline ">Jumlah Masih Kosong</span>');
				$('#jml').closest('.control-group').addClass('error');
			}else{
				$("#jml").find('.help-inline').remove();
				$("#jml").closest('.control-group').addClass('success');
			}
			//end cek jika kosong
			
			if (nofak && fakturReturn && id_brg && id_rak && jml) {
				//ambil data form
				var form = $(this);
				//button simpan loading
				$("#simpanReturnBtn").button('loading');

				// fungsi ajax
				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType: 'json',
					success:function(response){
						//button simpan loading reset
						$("#simpanReturnBtn").button('reset');

						if (response.success == true) {
							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');

							//tampil pesan true
							$('#pesan').html('<div class="alert alert-success">'+
								'<button class="close" data-dismiss="alert">×</button>'+
								response.messages+'</div>');
						}else if(response.success == false){
							//hapus pesan error di filed
							$('.help-inline').remove();
							//hapus warna error di filed
							$(".control-group").removeClass('error').removeClass('success');
							//tampil pesan false
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
});

//fungsi menampilkan jumlah berdasarkan no faktur dan ukuran
/*function getId_brg(){
	var nofak = $('#nofak').val();
	var id_brg = $('#id_brg').val();

	if (nofak && id_brg) {
		//alert("nofak "+nofak+" id_brg "+id_brg);
		$.ajax({
			url  : 'action/return/fetchSelectedJml.php',
			type : 'POST',
			data : {nofak: nofak, id_brg: id_brg},
			dataType: 'json',
			success:function(response){
				$("#jml").val(response.jml_klr);
				//alert(response.jml_klr);
			}
		});		
	}

}*/

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});