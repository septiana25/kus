$(document).ready(function(){
	let tabelPromosiKeluar;
	//active menu pengirim
	$("#activePromosi").addClass('active');
	$("#activePromosiKeluar").addClass('active');

	tabelPromosiKeluar = $('#tabelPromosiKeluar').DataTable({
		'ajax' : 'action/promosi/fetchpromosikeluar.php',
		'order':[]
	});//manage tabel

	$(".chosen-select").chosen({width: "95%"});

	function generatePromoCode() {
        const now = new Date();
        const year = now.getFullYear().toString().slice(-2);
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const prefix = 'BK' + year + month;
        
        $('#noAwal').val(prefix);
    }

	generatePromoCode();

	$("#addModalPromosi").unbind('click').bind('click', function() {
		// Hapus event handler yang mungkin sudah ada sebelumnya
		$("#divisi").off('change');
		$("#divisi").on('change', function () {
			const divisi = $(this).val();
			if (divisi) {
				$.ajax({
					url: "action/promosi/fetchSelectedUkuran.php",
					type: "POST",
					dataType: "json",
					data: { divisi: divisi },
					success: function(response) {
						if (response.data && response.data.length > 0) {
							let options = '<option value="">Pilih Item...</option>';
							$.each(response.data, function(index, item) {
								options += '<option value="' + item.id + '">' + item.item + ' - ' + item.saldo +'</option>';
							});
							$("#item").html(options).prop('disabled', false);
						} else {
							$("#item").html('<option value="">Tidak ada data</option>').prop('disabled', true);
						}
					},
					error: function(xhr, status, error) {
						console.error("Error: " + error);
						$("#item").html('<option value="">Error mengambil data</option>').prop('disabled', true);
					}
				});
			} else {
				$("#item").html('<option value="">Pilih Divisi terlebih dahulu</option>').prop('disabled', true);
			}
		});
	});

	$('#submitAddPromosi').unbind('submit').bind('submit', function() {
		const qty = $('#qty').val().trim();
		const toko = $('#toko').val().trim();
		const item = $('#item').val().trim();
		const sales = $('#sales').val().trim();
		const divisi = $('#divisi').val().trim();
		const noAwal = $('#noAwal').val().trim();
		const noAkhir = $('#noAkhir').val().trim();

		validateInput(qty, '#qty', 'Qty harus diisi');
		validateInput(item, '#item', 'Item harus diisi');
		validateInput(toko, '#toko', 'Toko harus diisi');
		validateInput(sales, '#sales', 'Sales harus diisi');
		validateInput(divisi, '#divisi', 'divisi harus diisi');
		validateInput(noAwal, '#noAwal', 'No Awal harus diisi');
		validateInput(noAkhir, '#noAkhir', 'No Akhir harus diisi');


		if (item && sales && divisi && noAkhir && noAwal && qty && toko) {
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

	$('#printNotaBtn').click(function() {
		const noTrans = $('#noTrans').val();
		if(noTrans){
			$.ajax({
				url: 'action/promosi/printnotapromosikeluar.php',
			type: 'POST',
			data: { noTrans: noTrans },
			dataType: 'text',
			success: handlePrintPromosiNotaKeluar
			});
		} else {
			alert("No Nota Tidak Ada");
		}
	});

	function handlePrintPromosiNotaKeluar(response) {
		const printWindow = window.open('', '_blank', 'height=600,width=800,scrollbars=yes,resizable=yes');
		
		if (!printWindow) {
			alert('Popup blocker mungkin mencegah pencetakan. Mohon izinkan popup untuk situs ini dan coba lagi.');
			return;
		}

		const htmlContent = `
			<!DOCTYPE html>
			<html lang="id">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Cetak Bukti Terima - Aplikasi Inventori Gudang KUS</title>
				<style>
					body { font-family: Arial, sans-serif; }
					@media print {
						body { width: 21cm; height: 29.7cm; }
					}
				</style>
			</head>
			<body>
				${response}
			</body>
			</html>
		`;

		printWindow.document.write(htmlContent);
		printWindow.document.close();

		printWindow.onload = function() {
			setTimeout(function() {
				printWindow.focus();
				printWindow.print();
				printWindow.onafterprint = function() {
					printWindow.close();
				};
			}, 250);
		};
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
	
		tabelPromosiKeluar.ajax.reload();
		if (response.success === true) {
			$('#qty').val('');
			$('#note').val('');
			$('#item').val('');
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

function editEkpedisi(id_so) {
	/* $.ajax({
		url: 'action/upload/fetchsalesorderbyid.php',
		type: 'POST',
		data: { id_so: id_so },
		dataType: 'json',
		success: function(data) {
			$('#id_so').val(data.id_so);
			$('#nopol').val(data.nopol);
			$('#kode_toko').val(data.kode_toko);
			$('#kdbrg').val(data.kdbrg);
			$('#qty').val(data.qty);
		}
	}); */
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

function printNota(idProKlr) {
	$.ajax({
		url: 'action/promosi/fetchpromosikeluarbyid.php',
		type: 'POST',
		data: { id_proklr: idProKlr },
		dataType: 'json',
		success: function(data) {
			$('#noTrans').val(data.no_trank);
			$('#noNota').text('Print No Nota '+ data.no_trank);
		}
	});
}

function generatePromoCode() {
    const now = new Date();
    const year = now.getFullYear().toString().slice(-2); // Mengambil 2 digit terakhir tahun
    const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Bulan dalam format 2 digit

    const prefix = 'BM' + year + month;
    
    // Atur nilai input
    document.getElementById('noAwal').value = prefix;
}

function HurufBesar(a){
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