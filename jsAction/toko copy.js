var tabelToko;
$(document).ready(function(){
	//active menu pengirim
	$("#activeToko").addClass('active');

	tabelToko = $('#tabelToko').DataTable({
		'ajax' : 'action/toko/fetchToko.php',
		'order':[]
	});//manage tabel 

	$("#addTokoBtnModal").unbind('click').bind('click', function()
	{

	$("#submitToko").unbind('submit').bind('submit', function()
	{

		var namaToko = $("#namaToko").val();
		var alamat   = $("#alamat").val();

		if (namaToko == "")
		{
			$("#namaToko").after('<span class="help-inline">Nama Toko Masih Kosong</span>');
			$("#namaToko").closest('.control-group').addClass('error');
		}
		else
		{
			$("#namaToko").closest('.control-group').addClass('success');
			$("span").remove(":contains('Nama Toko Masih Kosong')");
		}

		if (alamat == "")
		{
			$("#alamat").after('<span class="help-inline">Alamat Toko Masih Kosong</span>');
			$("#alamat").closest('.control-group').addClass('error');
		}
		else
		{
			$("#alamat").closest('.control-group').addClass('success');
			$("span").remove(":contains('Alamat Toko Masih Kosong')");
		}

		if (namaToko && alamat) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanTokoBtn").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response){
					//button reset
					$("#simpanTokoBtn").button('reset');

					if (response.success == true) {

						tabelToko.ajax.reload(null, false);

						//reset the form text
						$("#submitToko")[0].reset();
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
					else if (response.success == false) {
						//tabelPengirim.ajax.reload(null, false);

						//reset the form text
						//$("#submitPengirim")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
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


	$("#submitEditToko").unbind('submit').bind('submit', function(){

		var editNamaToko = $("#editNamaToko").val();
		var editAlamat   = $("#editAlamat").val();

		if (editNamaToko == "")
		{
			$("#editNamaToko").after('<span class="help-inline">Edit Nama Toko Masih Kosong</span>');
			$("#editNamaToko").closest('.control-group').addClass('error');
		}
		else
		{
			$("#editNamaToko").closest('.control-group').addClass('success');
			$("span").remove(":contains('Edit Nama Toko Masih Kosong')");
		}

		if (editAlamat == "")
		{
			$("#editAlamat").after('<span class="help-inline">Edit Alamat Toko Masih Kosong</span>');
			$("#editAlamat").closest('.control-group').addClass('error');
		}
		else
		{
			$("#editAlamat").closest('.control-group').addClass('success');
			$("span").remove(":contains('Edit Alamat Toko Masih Kosong')");
		}

		if (editNamaToko && editAlamat) {

			var form = $(this);

			$("#editTokoBtn").button('loading');

			$.ajax({

				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){

					$("#editTokoBtn").button('reset');

					if (response.success == true)
					{

						tabelToko.ajax.reload(null, false);

						//reset the form text
						//$("#submitToko")[0].reset();
						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesanEdit').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});

					}
					else if (response.success == false )
					{

						//remove the error text
						$(".help-inline").remove();
						//remove teh error form
						$(".control-group").removeClass('error').removeClass('success');
						
						//show messages pesan
						$('#pesanEdit').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');

					}

				}

			});

		}

		return false;

	});

});


function editToko(id_toko = null){

	if (id_toko)
	{

		$.ajax({
			url  : 'action/toko/fetchSelectedToko.php',
			type : 'POST',
			data : {id_toko : id_toko},
			dataType : 'json',
			success:function(data){

				$("#editNamaToko").val(data.toko);

				$("#editAlamat").val(data.alamat);

				$("#editIdToko").val(data.id_toko);

		}


		});

	}	

}

function hapusToko(id_toko = null){

	if (id_toko)
	{

		$.ajax({

			url  : 'action/toko/fetchSelectedToko.php',
			type : 'POST',
			data : {id_toko : id_toko},
			dataType : 'json',
			success:function(data){
				$('.modal-footer').removeClass('hidden');
				$('#pesanHapus').html('<strong>Anda Yakin Ingin Menghapus '+ data.toko +'?</strong>');

				$('#hapusTokoBtn').unbind('click').bind('click', function(){

					$("#hapusTokoBtn").button('loading');

					$.ajax({

						url  : 'action/toko/hapusToko.php',
						type : 'POST',
						data : {id_toko : id_toko},
						dataType : 'json',
						success:function(response){

							if (response.success == true)
							{
								tabelToko.ajax.reload(null, false);

								$("#hapusTokoBtn").button('reset');
								// close the modal 
								$("#hapusModalToko").modal('hide');
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

						         }, 6000);
							}
							else if(response.success == false)
							{
								$("#hapusTokoBtn").button('reset');
								// $("#hapusModalToko").modal('hide');
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

						         }, 8000)
							}

						}

					});

				});

			}

		});

	}
	else
	{

		alert('Terjadi Kesalahan, Lakukan Refresh Halaman.');

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