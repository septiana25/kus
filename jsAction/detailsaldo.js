let table;
$(document).ready(function() {
	$(".choiceChosen").chosen();

	const divRequest = $(".div-request").text();
	const id = $(".div-idsaldo").text();

	$('#activeMaster').addClass('active');
	
	if (divRequest == 'barang') {
		$('#activeBarang').addClass('active');
	}
	
	tabel = $('#tabelDetailSaldo').DataTable({
		'ajax' : `action/saldo/fetchDetailSaldo.php?id=${id}`,
		'order':[]
	});

	$('#addDetailSaldo').unbind('click').bind('click', function() {

		document.getElementById("tahunprod").focus();
		$.ajax({
			url: `action/saldo/fetchSaldoAndDetailSaldo.php?id=${id}`,
			type: 'get',
			dataType: 'json',
			success: function(response) {
				const sisa = response.data.saldo_akhir - response.data.subtotal;

				$('#infosaldo').html(
					`<p>Total Saldo : ${response.data.saldo_akhir}</p>
					 <p>Sisa : ${sisa}</p>`
				);
				$('#brg').val(response.data.brg);
				$('#rak').val(response.data.rak);
			}
		});
	})
	
	$('#submitDetailSaldo').unbind('submit').bind('submit', function() {
		const tahunprod = $("#tahunprod").val().trim();
		const qty = $("#qty").val().trim();
	
	
		validateInput(tahunprod, "#tahunprod", "Tahun Produksi Masih Kosong");
	
		validateInput(qty, "#qty", "Qty Masih Kosong");
	
		if (tahunprod && qty) {
			const form = $(this);
			$("#save").button('loading');
	
			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success: function(data) {
					handleResponse(data, '#addModalDetailSaldo', '#submitDetailSaldo', 'add');
				}
			});
		}
		return false;
	});

});

function editTahunProd(idDetail) {
	if (!idDetail) {
		alert("Data Tidak Ditemukan");
	}
	
	$('#editModalDetailSaldo').modal('show');
	$.ajax({
		url: 'action/saldo/fetchDetailSaldoById.php',
		type: 'post',
		data: {idDetail: idDetail},
		dataType: 'json',
		success: function(response) {
			$('#editIdDetail').val(response.id_detailsaldo);
			$('#editTahunprod').val(response.tahunprod);
			$('#editQty').val(response.jumlah);

			$('#editDetailSaldo').unbind('submit').bind('submit', function() {
				const tahunprod = $("#editTahunprod").val().trim();
			
				validateInput(tahunprod, "#editTahunprod", "Tahun Produksi Masih Kosong");
			
				if (tahunprod) {
					const form = $(this);
					$("#save").button('loading');
			
					$.ajax({
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),
						dataType: 'json',
						success: function(data) {
							handleResponse(data, '#editModalDetailSaldoTahun', '#editDetailSaldo', 'edit');
						}
					});
				}
				return false;
			});
		}
	});

}

function editQtyProd(idDetail) {
	if (!idDetail) {
		displayMessagePopup("Data Tidak Ditemukan", 'error');
	}
	
	$('#editModalDetailSaldo').modal('show');
	$.ajax({
		url: 'action/saldo/fetchDetailSaldoById.php',
		type: 'post',
		data: {idDetail: idDetail},
		dataType: 'json',
		success: function(response) {
			$('#editIdDetailQty').val(response.id_detailsaldo);
			$('#editTahunprodQty').val(response.tahunprod);
			$('#editQtyDetailSaldo').val(response.jumlah);

			$('#editDetailSaldoQty').unbind('submit').bind('submit', function() {
				const qty = $("#editQtyDetailSaldo").val().trim();
			
				validateInput(qty, "#editQtyDetailSaldo", "Tahun Produksi Masih Kosong");
			
				if (qty) {
					const form = $(this);
					$("#save").button('loading');
			
					$.ajax({
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),
						dataType: 'json',
						success: function(data) {
							handleResponse(data, '#editModalDetailSaldoQty', '#editDetailSaldoQty', 'edit');
						}
					});
				}
				return false;
			});
		}
	});

}

function handleResponse(response, modalBtn,submitBtn, typeFrom) {
	$(".help-inline").remove();
	$(".control-group").removeClass('error').removeClass('success');
	$("#save").button('reset');

	if (response.success === true) {
		$(modalBtn).modal('hide');
		tabel.ajax.reload(null, false);
		if (typeFrom === 'add') {
			$(submitBtn).closest("form")[0].reset();
		}
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

function validateInput(value, selector, errorMessage) {
	if (value === "") {
		$(selector).after(`<span class="help-inline">${errorMessage}</span>`);
		$(selector).closest('.control-group').addClass('error');
	} else {
		$(selector).closest('.control-group').addClass('success');
		$(".help-inline").remove();
	}
}

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}

$(document).ajaxError(function(event, jqXHR, settings, thrownError){
    console.error(`An error occurred with the AJAX request. 
    URL: ${settings.url} 
    Error: ${thrownError}`);
    alert("An error occurred. Please try again later.");
});