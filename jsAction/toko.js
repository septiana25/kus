$(document).ready(function(){
	let tabelToko;
	//active menu pengirim
	$("#activeToko").addClass('active');

	tabelToko = $('#tabelToko').DataTable({
		'ajax' : 'action/toko/fetchToko.php',
		'order':[]
	});//manage tabel 

	$('#submitToko').unbind('submit').bind('submit', function() {
		const kode_toko = $('#kode_toko').val().trim();
		const toko = $('#toko').val().trim();
		const alamat = $('#alamat').val().trim();

		validateInput(kode_toko, '#kode_toko', 'Kode Toko harus diisi');
		validateInput(toko, '#toko', 'Nama Toko harus diisi');
		validateInput(alamat, '#alamat', 'Alamat harus diisi');

		if (kode_toko && toko && alamat) {
			const form = $(this);

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success: handleResponse
			});
		}
		return false;

	});
	
	$('#submitEditToko').unbind('submit').bind('submit', function() {
		const id_toko = $('#id_toko').val().trim();
		const kode_toko = $('#editkode_toko').val().trim();
		const toko = $('#edittoko').val().trim();
		const alamat = $('#editalamat').val().trim();

		validateInput(kode_toko, '#editkode_toko', 'Kode Toko harus diisi');
		validateInput(toko, '#edittoko', 'Nama Toko harus diisi');
		validateInput(alamat, '#editalamat', 'Alamat harus diisi');

		if (id_toko && kode_toko && toko && alamat) {
			const form = $(this);

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success: handleResponse
			});
		}
		return false;

	});

	$('#submitDeleteSalesOrder').unbind('submit').bind('submit', function() {
		const id = $('#hapusid').val().trim();

		if (id) {
			const form = $(this);

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success: handleResponse
			});
		}
		return false;
	});


	function validateInput(value, selector, errorMessage) {
		if (value === "") {
			$(selector).after(`<span class="help-inline">${errorMessage}</span>`);
			$(selector).closest('.control-group').addClass('error');
		} else {
			$(selector).closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}
	}
	function handleResponse(response) {
	
		tabelToko.ajax.reload();
		if (response.success === true) {
			$('.modal').modal('hide');
			displayMessagePopup(response.messages, 'success');
		} else if (response.success === false) {
			displayMessagePopup(response.messages, 'error');
		}
	}
	
	function displayMessagePopup(messages, type) {
		const isSuccessful = type === 'success';
		const data = {
			title: isSuccessful ? 'Success!' : 'Error!',
			image: isSuccessful ? 'img/success-mini.png' : 'img/error-mini.png',
			class_name: isSuccessful ? 'my-sticky-class' : 'gritter-light'
		};
	
		const unique_id = $.gritter.add({
			title: data.title,
			text: messages,
			image: data.image,
			class_name: data.class_name,
			time: ''
		});
	
		setTimeout(function() {
			$.gritter.remove(unique_id, {
				fade: true,
				speed: 'slow'
			});
		}, 6000);
	}

});

function editToko(id_toko) {
	$.ajax({
		url: 'action/toko/fetchtokobyid.php',
		type: 'POST',
		data: { id_toko: id_toko },
		dataType: 'json',
		success: function(data) {
			$('#id_toko').val(data.id_toko);
			$('#editkode_toko').val(data.kode_toko);
			$('#edittoko').val(data.toko);
			$('#editalamat').val(data.alamat);
		}
	});
}

function deleteEkspedisi(id_so) {
	/* $.ajax({
		url: 'action/upload/fetchsalesorderbyid.php',
		type: 'POST',
		data: { id_so: id_so },
		dataType: 'json',
		success: function(data) {
			$('#hapusid').val(data.id_so);
			$('#pesanHapus').text('Apakah anda yakin ingin menghapus data '+ data.no_faktur + ' & ' + data.brg + ' ?');
		}
	}); */
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