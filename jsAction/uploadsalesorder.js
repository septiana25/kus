$(document).ready(function() {
	let tabelSalesOrder;
	$('#activeUpload').addClass('active');
	$('#activeUploadSalesOrder').addClass('active');

	tabelSalesOrder = $('#tabelSalesOrder').DataTable({
		'ajax' : 'action/upload/fetchuplodesalesorder.php',
		'order':[],
	});
	
	$('#checkingData').click(function() {
		$.ajax({
			url: 'action/upload/checkingkoreksisaldo.php',
			type: 'POST',
			data: { type: '3' },
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#processData').click(function() {
		$.ajax({
			url: 'action/upload/processkoreksiminus.php',
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#submitUploadSalesOrder').unbind('submit').bind('submit', function() {
		const file = $('#file-csv').val().trim();
		const type = $('#type').val().trim();

		validateInput(file, '#file-csv', 'File CSV harus diisi');
		validateInput(type, '#file-csv', 'Relode Page');

		if (file !== '' && type !== '') {
			const form = $(this);
			const formData = new FormData(this);

			$.ajax({
				url: 'action/upload/uploadsalesorder.php',
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

	$('#submitEditKoreksiMinus').unbind('submit').bind('submit', function() {
		const id = $('#id').val().trim();
		const kdbrg = $('#kdbrg').val().trim();
		const rak = $('#rak').val().trim();
		const tahunprod = $('#tahunprod').val().trim();

		validateInput(kdbrg, '#kdbrg', 'Kode Barang harus diisi');
		validateInput(rak, '#rak', 'Rak harus diisi');
		validateInput(tahunprod, '#tahunprod', 'Tahun harus diisi');

		if (id && kdbrg && rak && tahunprod) {
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

	$('#submitDeleteKoreksiMinus').unbind('submit').bind('submit', function() {
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
	
		tabelSalesOrder.ajax.reload();
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


//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});
	
});

function editKoreksiSaldo(id) {
	$.ajax({
		url: 'action/upload/fetchsalesorderbyid.php',
		type: 'POST',
		data: { id: id },
		dataType: 'json',
		success: function(data) {
			$('#id').val(data.id);
			$('#kdbrg').val(data.kdbrg);
			$('#rak').val(data.rak);
			$('#tahunprod').val(data.tahunprod);
		}
	});
}

function deleteKoreksiSaldo(id_so) {
	$.ajax({
		url: 'action/upload/fetchsalesorderbyid.php',
		type: 'POST',
		data: { id_so: id_so },
		dataType: 'json',
		success: function(data) {
			$('#hapusid').val(data.id_so);
			$('#pesanHapus').text('Apakah anda yakin ingin menghapus data '+ data.no_faktur + ' & ' + data.brg + ' ?');
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