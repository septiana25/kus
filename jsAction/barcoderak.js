$(document).ready(function() {
	$(".choiceChosen").chosen();

	const divRequest = $(".div-request").text();

	$('#activeMaster').addClass('active');
	
	if (divRequest == 'barcoderak') {
		$('#activeBarcodeRak').addClass('active');
	}
	
	const tabel = $('#tabelBarcode').DataTable({
		'ajax' : 'action/barcoderak/fetchBarcode.php',
		'order':[]
	});

	$('#rak').typeahead({
		source: function(rak, result) {
			$.ajax({
				url: "action/barcoderak/fetchAutoCompleteRak.php",
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
	});
	
	$('#submitBarcode').unbind('submit').bind('submit', function() {
		const rak = $("#rak").val().trim();
		const barcode = $("#barcode").val().trim();
	
	
		validateInput(rak, "#rak", "Rak Masih Kosong");
	
		validateInput(barcode, "#barcode", "Barcode Masih Kosong");
	
		if (rak && barcode) {
			const form = $(this);
			$("#save").button('loading');
	
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
		$("#save").button('reset');
	
		$(".help-inline").remove();
		$(".control-group").removeClass('error').removeClass('success');
	
		if (response.success === true) {
			tabel.ajax.reload(null, false);
			$("#submitBarcode")[0].reset();
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

$(document).ajaxError(function(event, jqXHR, settings, thrownError){
    console.error(`An error occurred with the AJAX request. 
    URL: ${settings.url} 
    Error: ${thrownError}`);
    alert("An error occurred. Please try again later.");
});