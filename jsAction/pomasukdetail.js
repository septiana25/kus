$(document).ready(function() {
	$(".choiceChosen").chosen();
	//mengambil data div class div-request
	const divRequest = $(".div-request").text();
	// active manu barang
	$('#activeTransaksi').addClass('active');
	
	if (divRequest == 'pomasuk') {
		// active submenu barang masuk
		$('#activePOMasuk').addClass('active');

	}
	
	const id = $('.div-request-id').text();
	function fetch_data(id) {
		$.ajax({
			url: 'action/pomasuk/fetchScanMasukApi.php',
			data: {id: id},
			method: 'GET',
			success: function(response) {
				const data = JSON.parse(response);
				const ids_to_filter = data.filter;

				newData = data.data.filter(function(data) {
					return !ids_to_filter.includes(data.id_masuk_det);
				});

				let formHTML = '';
				newData.forEach(function(value) {
					formHTML += generateForm(value);
				});
				$('#dataPosting').html(formHTML);
			}
		});
	}

	const generateForm = (data) => {
		return `
			<form method='post' id='submitPoMasuk' action='action/barangMasuk/simpanMasukPosting.php'>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Barang</th>
							<th>Rak</th>
							<th>QTY</th>
							<th>Note</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<input type='hidden' id="suratJLN" name='suratJLN' value='${data.suratJalan}' />
							<input type='hidden' id='id_brg' name='id_brg' value='${data.id_item}' />
							<input type='hidden' id='id_rak' name='id_rak' value='${data.id_rak}' />
							<input type='hidden' name='tgl' value='${data.tanggal_masuk}' />
							<input type='hidden' name='idPoMsk' value='${id}' />
							<input type='hidden' name='idPoMskScanDetail' value='${data.id_masuk_det}' />
							<td>${data.item}</td>
							<td width='10%'>${data.rak}</td>
							<td width='4%'><input type='text' id='jml' name='jml' value='${data.qty}' class='input-small'/></td>
							<td width='8%'><input type='text' name='ket' class='input-small'/></td>
							<td width='5%'><button type='submit' class='btn btn-primary'>Simpan</button></td>
						</tr>
					</tbody>
				</table>
			</form>
		`;
	}

	fetch_data(id);

	$('#masihBelumFungsi').unbind('submit').bind('submit', function() {
		const tgl = $("#tgl").val().trim();
		const nopo = $("#suratJLN").val().trim();
		const nopol = $("#id_brg").val().trim();
		const item = $("#id_rak").val().trim();
		const qty = $("#jml").val().trim();

		if(tgl && nopo && nopol && item && qty) {
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
		fetch_data(27);
		$(".help-inline").remove();
		$(".control-group").removeClass('error').removeClass('success');
	
		if (response.success === true) {
			
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

});// /document


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