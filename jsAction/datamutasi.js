$(document).ready(function() {
	$(".choiceChosen").chosen();
	//mengambil data div class div-request
	const divRequest = $(".div-request").text();
	// active manu barang
	$('#activeMaster').addClass('active');
	
	const convertToUpperCase = (inputElement) => {
		setTimeout(() => {
			inputElement.value = inputElement.value.toUpperCase();
		}, 1);
	}

	const tabelDataMutasi= $('#tabelDataMutasi').DataTable({
		'ajax' : 'action/return/fetchDataMutasi.php',
		'order':[],
		'columnDefs': [
			{
				'targets': -1, // This targets the last column
				'className': 'd-none-mobile' // This adds the class
			}
		]
	});


	$('#submitCloseMutasi').unbind('submit').bind('submit', function() {
		const id = $('#id_mutasi').val().trim();

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

	$('#modalProsesMutasi').unbind('click').bind('click', function() {

		$('#submitProsesMutasi').unbind('submit').bind('submit', function() {
			const form = $(this);
			$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(),
			dataType: 'json',
			success: handleResponse
		});

			return false;
		});
	});

	function handleCheckDataMutasi() {
		const isEmpty = $('#id_mutasi').val().trim() === '';
		if (isEmpty) {
			return true;
		}
		return false;
	}

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
	
		tabelDataMutasi.ajax.reload();
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

});// /document


function reject(id_mutasi) {
	$.ajax({
		url: 'action/return/fetchDataMutasiById.php',
		type: 'POST',
		data: { id_mutasi: id_mutasi },
		dataType: 'json',
		success: function(data) {
			$('#id_mutasi').val(data.id_mutasi);
			$('#brg').val(data.brg);
			$('#rak').val(data.rak_tujuan);
			$('#qty').val(data.qty);
		}
	});
}

function hapusBarcodebrg(id_brg) {
	$.ajax({
		url: 'action/barcodebrg/fetchBarcodeById.php',
		type: 'POST',
		data: { id_brg: id_brg },
		dataType: 'json',
		success: function(data) {
			$('#hapusid').val(data.id_brg);
			$('#pesanHapus').text('Apakah anda yakin ingin menghapus data '+ data.brg + ' ?');

		}
	});
}


function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
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