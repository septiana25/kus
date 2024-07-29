$(document).ready(function() {
	let tabelDetailSO;
	$('#activeUpload').addClass('active');
	$('#activeUploadSalesOrder').addClass('active');

    const nopol = $(".div-nopol").text();
	tabelDetailSO = $('#tabelDetailSO').DataTable({
		'ajax' : `action/upload/fetchdetailprosesssalesorder.php?expedition=${nopol}`,
		'order':[],
	});
	
	$('#checkingData').click(function() {
		$.ajax({
			url: 'action/upload/checkingsalesorder.php',
			type: 'POST',
			data: { type: '3' },
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#processData').click(function() {
		$.ajax({
			url: 'action/upload/processsalesorder.php',
			dataType: 'json',
			success: handleResponse
		});
	});

	$('#submitEditQtyDetailSO').unbind('submit').bind('submit', function() {
		const id_pro = $('#qtyid_pro').val().trim();
		const qty = $('#qty').val().trim();

		validateInput(qty, '#qty', 'Quantiti harus diisi');

		if (id_pro && qty) {
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
		tabelDetailSO.ajax.reload();
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

function editQty(id_pro) {
	$.ajax({
		url: 'action/upload/fetchdetailprosesssalesorderbyid.php',
		type: 'POST',
		data: { id_pro: id_pro },
		dataType: 'json',
		success: function(data) {
			$('#qtyid_pro').val(data.id_pro);
			$('#qtybrg').val(data.brg);
			$('#qtytahunprod').val(data.tahunprod);
            $('#qty').val(data.qty_pro);
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