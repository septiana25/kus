$(document).ready(function(){
	let tabelPromosiMasuk;
	//active menu pengirim
	$("#activePromosi").addClass('active');
	$("#activePromosiMasuk").addClass('active');

	tabelPromosiMasuk = $('#tabelPromosiMasuk').DataTable({
		'ajax' : 'action/promosi/fetchpromosimasuk.php',
		'order':[]
	});//manage tabel 

	function generatePromoCode() {
        const now = new Date();
        const year = now.getFullYear().toString().slice(-2);
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const prefix = 'BM' + year + month;
        
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
		const item = $('#item').val().trim();
		const divisi = $('#divisi').val().trim();
		const noAwal = $('#noAwal').val().trim();
		const noAkhir = $('#noAkhir').val().trim();

		validateInput(qty, '#qty', 'Qty harus diisi');
		validateInput(item, '#item', 'Item harus diisi');
		validateInput(divisi, '#divisi', 'divisi harus diisi');
		validateInput(noAwal, '#noAwal', 'No Awal harus diisi');
		validateInput(noAkhir, '#noAkhir', 'No Akhir harus diisi');


		if (item && divisi && noAkhir && noAwal && qty) {
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
	
		tabelPromosiMasuk.ajax.reload();
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