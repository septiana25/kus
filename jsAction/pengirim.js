var tabelPengirim;
$(document).ready(function(){
	//active menu pengirim
	$("#activePengirim").addClass('active');

	tabelPengirim = $('#tabelPengirim').DataTable({
		'ajax' : 'action/pengirim/fetchPengirim.php',
		'order':[]
	});//manage tabel 

	$("#submitPengirim").unbind('submit').bind('submit', function(){
		var pengirim = $("#pengirim").val();

		if (pengirim == "") {
			$("#pengirim").after('<span class="help-inline">Nama Masih Kosong</span>');
			$("#pengirim").closest('.control-group').addClass('error');
		}else{
			$("#pengirim").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (pengirim) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanPengirimBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response){
					//button reset
					$("#simpanPengirimBtn").button('reset');

					if (response.success == 'cek_pengirim') {
						//tabelPengirim.ajax.reload(null, false);

						//reset the form text
						//$("#submitPengirim")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
						$(".control-group").removeClass('error').removeClass('success');
						$("#pengirim").closest('.control-group').addClass('error');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
					if (response.success == true) {
						tabelPengirim.ajax.reload(null, false);

						//reset the form text
						$("#submitPengirim")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
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
						//tabelPengirim.ajax.reload(null, false);

						//reset the form text
						//$("#submitPengirim")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
						$(".control-group").removeClass('error').removeClass('success');
						$("#pengirim").closest('.control-group').addClass('error');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
				}
			});
		}

		return false;
	});
});

function upperCaseF(a){
setTimeout(function(){
    a.value = a.value.toUpperCase();
}, 1);
}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});