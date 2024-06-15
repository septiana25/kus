$(document).ready(function() {
	let tabelKoresiSaldo;
	$('#activeUpload').addClass('active');
	$('#activeUploadKoreksiSaldo').addClass('active');

	tabelKoresiSaldo = $('#tabelKoreksiSaldo').DataTable({
		'ajax' : 'action/upload/fetchkoreksisaldo.php',
		'order':[],
	});
	
	$('#checkingData').click(function() {
		$.ajax({
			url: 'action/upload/checkingkoreksisaldo.php',
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#processData').click(function() {
		$.ajax({
			url: 'action/upload/processkoreksisaldo.php',
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#submitUploadKoreksiSaldo').unbind('submit').bind('submit', function() {
		const file = $('#file-csv').val().trim();

		validateInput(file, '#file-csv', 'File CSV harus diisi');

		if (file !== '') {
			const form = $(this);
			const formData = new FormData(this);

			$.ajax({
				url: 'action/upload/koreksisaldo.php',
				type: 'POST',
				data: formData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: handleResponse
			});
		}
		return false;

	});

	$('#submitEditKoreksiSaldo').unbind('submit').bind('submit', function() {
		const id = $('#id').val().trim();
		const kdbrg = $('#kdbrg').val().trim();
		const rak = $('#rak').val().trim();

		validateInput(kdbrg, '#kdbrg', 'Kode Barang harus diisi');
		validateInput(rak, '#rak', 'Rak harus diisi');

		if (id && kdbrg && rak) {
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

	$('#submitDeleteKoreksiSaldo').unbind('submit').bind('submit', function() {
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
	
		tabelKoresiSaldo.ajax.reload();
		if (response.success === true) {
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


//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});
	
});

function editKoreksiSaldo(id) {
	$.ajax({
		url: 'action/upload/fetchkoreksisaldobyid.php',
		type: 'POST',
		data: { id: id },
		dataType: 'json',
		success: function(data) {
			$('#id').val(data.id);
			$('#kdbrg').val(data.kdbrg);
			$('#rak').val(data.rak);
		}
	});
}

function deleteKoreksiSaldo(id) {
	$.ajax({
		url: 'action/upload/fetchkoreksisaldobyid.php',
		type: 'POST',
		data: { id: id },
		dataType: 'json',
		success: function(data) {
			$('#hapusid').val(data.id);
			$('#pesanHapus').text('Apakah anda yakin ingin menghapus data ' + data.brg + ' ?');
		}
	});
}