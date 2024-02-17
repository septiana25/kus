$(document).ready(function() {
	$(".choiceChosen").chosen();

	const divRequest = $(".div-request").text();
	const id = $(".div-idsaldo").text();

	$('#activeMaster').addClass('active');
	
	if (divRequest == 'barang') {
		$('#activeBarang').addClass('active');
	}
	
	const tabel = $('#tabelDetailSaldo').DataTable({
		'ajax' : `action/saldo/fetchDetailSaldo.php?id=${id}`,
		'order':[]
	});

/* 	$('#rak').typeahead({
		source: function(rak, result) {
			$.ajax({
				url: "action/barcoderak/fetchDetailSaldo.php",
				method: "POST",
				data: {rak: rak},
				dataType: "json",
				success: function(data) {
					result($.map(data, function(rak) {
						return rak;
					}));
				}
			});
		},
		items: 10,
		minLength: 2,
	}); */

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
			//$("#save").button('loading');
	
			$.ajax({
				url : form.attr('action'),
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
		$(".help-inline").remove();
		$(".control-group").removeClass('error').removeClass('success');
	
		if (response.success === true) {
			$("#addModalDetailSaldo").modal('hide');
			tabel.ajax.reload(null, false);
			$("#submitDetailSaldo")[0].reset();
			displayMessage('#pesan', 'alert alert-success', response.messages);
		} else if (response.success === false) {			
			displayMessage('#pesan', 'alert alert-error', response.messages);
		}
	}
	
	function displayMessage(selector, className, message) {
		$(selector).html(`<div class="${className}">
			<button class="close" data-dismiss="alert">×</button>
			${message}
		</div>`);
	
		$(".alert-success").delay(500).show(10, function() {
			$(this).delay(4000).hide(10, function() {
				$(this).remove();
			});
		});
	}

});

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