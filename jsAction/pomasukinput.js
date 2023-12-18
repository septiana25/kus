$(document).ready(function() {
	const divRequest = $(".div-request").text();
	$('#activeTransaksi').addClass('active');
	
	if (divRequest === 'pomasuk') {
		$('#activePOMasuk').addClass('active');
	}

	$('.datepicker').datepicker();


	const validateNumericInput = (inputElement) => {
		if (!/^[0-9.]+$/.test(inputElement.value)) {
			inputElement.value = inputElement.value.substring(0, inputElement.value.length - 1);
		}
	}

	$('#item').typeahead({
		source: function(item, result) {
			$.ajax({
				url: "action/barcodebrg/fetchAutoCompleteItemBarcode.php",
				method: "POST",
				data: {item: item},
				dataType: "json",
				success: function(data) {
					result($.map(data, function(item) {
						return item;
					}));
				}
			});
		},
		items: 10,
		minLength: 2,
	});

	$('#nopol').typeahead({
		source: function(nopol, result) {
			$.ajax({
				url: "action/pomasuk/fetchAutoCompleteINopol.php",
				method: "POST",
				data: {nopol: nopol},
				dataType: "json",
				success: function(data) {
					result($.map(data, function(item) {
						return item;
					}));
				}
			});
		},
		items: 10,
		minLength: 1,
	});

	$('#submitPoMasuk').unbind('submit').bind('submit', function() {
		const tgl = $("#tgl").val().trim();
		const nopo = $("#nopo").val().trim();
		const nopol = $("#nopol").val().trim();
		const item = $("#item").val().trim();
		const qty = $("#qty").val().trim();
	
		validateInput(tgl, "#tgl", "Tanggal Masih Kosong");
		validateInput(nopo, "#nopo", "Surat Jalan Masih Kosong");
		validateInput(nopol, "#nopol", "No Polisi Masih Kosong");
		validateInput(item, "#item", "Item Masih Kosong");
		validateInput(qty, "#qty", "Qunatiti Masih Kosong");
	
		if (tgl && nopo && nopol && item && qty ) {
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
			$("#item").val("");
			$("#qty").val("");
			$("#note").val("");
			
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

function convertToUpperCase(inputElement) {
	inputElement.value = inputElement.value.toUpperCase();
}

$(document).ajaxError(function(event, jqXHR, settings, thrownError){
    console.error(`An error occurred with the AJAX request. 
    URL: ${settings.url} 
    Error: ${thrownError}`);
    alert("An error occurred. Please try again later.");
});