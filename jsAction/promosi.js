$(document).ready(function(){
	let tabelEkspedisi;
	//active menu pengirim
	$("#activePromosi").addClass('active');
	$("#activeDataPromosi").addClass('active');

	tabelEkspedisi = $('#tabelEkspedisi').DataTable({
		'ajax' : 'action/promosi/fetchpromosi.php',
		'order':[]
	});//manage tabel 

	$('#submitAddPromosi').unbind('submit').bind('submit', function() {
		const item = $('#item').val().trim();
		const jenis = $('#jenis').val().trim();
		const divisi = $('#divisi').val().trim();

		validateInput(item, '#item', 'Item harus diisi');
		validateInput(jenis, '#jenis', 'jenis harus diisi');
		validateInput(divisi, '#divisi', 'divisi harus diisi');

		if (item && jenis && divisi) {
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
	
		tabelEkspedisi.ajax.reload();
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


function HurufBesar(a){
setTimeout(function(){
    a.value = a.value.toUpperCase();
}, 1);
}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});