var tabelMasuk;
$(document).ready(function() {
    const config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      
    }
    for (const selector in config) {
      $(selector).chosen(config[selector]);
    }
    $(".chosen-select").chosen({width: "200%"});

	//mengambil data div class div-request
	const divRequest = $(".div-request").text();
	// active manu barang
	$('#activeTransaksi').addClass('active');
	
	if (divRequest == 'masuk') {
		// active submenu barang masuk
		$('#activeBarangMasuk').addClass('active');

		tabelMasuk = $('#tabelMasuk').DataTable({
			'ajax' : 'action/barangMasuk/fetchMasuk.php',
			'order':[]
		});// manage TabelMasuk

		$('.datepicker').datepicker();

		// on click on submit barang masuk dari modal
		$('#addBarangMskBtnModal').unbind('click').bind('click', function() {

		// submit kategori dari function
			$('#submitBarangMsk').unbind('submit').bind('submit', function() {
				
				const barang   	= $("#barang").val();
				const rak      	= $("#rak").val();
				const suratJLN 	= $("#suratJLN").val();
				const tahunprod = $("#tahunprod").val();
				const jumlah   	= $("#jml").val();

				//cek barang rak jumlah jika kosong
				validateField('barang', barang, 'Nama Barang Masuk Masih Kosong');
				validateField('rak', rak, 'Lokasi Rak Masuk Masih Kosong');
				validateField('suratJLN', suratJLN, 'Surat JaLan Masuk Masih Kosong');
				validateField('tahunprod', tahunprod, 'Tahun Produksi Masih Kosong');
				validateField('jml', jumlah, 'Jumlah Masih Kosong');
	
				//jika semua data terisi
				if (barang && rak && suratJLN && tahunprod &&  jumlah) {
					//ambil data form
					const form = $(this);
					//button loading
					//$("#simpanBarangMskBtn").button('loading');

					$.ajax({//proses simpan
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),
						dataType: 'json',
						success:function(response) {
							//button reset
							//$("#simpanBarangMskBtn").button('reset');

							if (response.success == true) {

								tabelMasuk.ajax.reload(null, false);
								//reset the form text
								$("#ket").val("");
								$("#jml").val("");

								//reset combobox
								//$("#barang").trigger("chosen:updated");
								$("#rak").val("");
								$("#rak").trigger("chosen:updated");

								document.getElementById("suratJLN").focus();

								//remove the error text
								removeError();

								//show messages pesan
								showMessage('success', response.messages);
								displayMessagePopup(response.messages, 'success');
							}

							else if (response.success == false) {
								showMessage('error', response.messages);
								displayMessagePopup(response.messages, 'error');
							}else{
								alert("Error");
							}
						}
					});
				}
				return false;
			});
		});


		$('#cariDataLama').unbind('click').bind('click', function(){

			$('#modalCariData').on('shown', function () {
			$('#cariBulan').focus();
			});

			$('#submitCariData').unbind('submit').bind('submit', function(){

				const cariBulan = $('#cariBulan').val();
				const cariTahun = $('#cariTahun').val();

				validateField('cariBulan', cariBulan, 'Bulan Masih Kosong');
				validateField('cariTahun', cariTahun, 'Tahun Masih Kosong');

				if (cariBulan && cariTahun) {

					const form = $(this);
					$('#simpanCariBtn').button('loading');
					$.ajax({

						url  : form.attr('action'),
						type : form.attr('method'),
						data : form.serialize(),
						dataType : 'text',
						success:function(response){

							$("#modalCariData").modal('hide');
							$('#simpanCariBtn').button('reset');


							const mywindow = window.open('', 'Laporan Transaksi', 'height=380,width=700');
							mywindow.document.write(response);
							
						}


					});

				}

				return false;
			});
			
		});


	} //end if div-request

	function validateField(id, value, errorMessage) {
		if (value == "") {
			$(`#${id}`).after(`<span class="help-inline">${errorMessage}</span>`);
			$(`#${id}`).closest('.control-group').removeClass('success');
			$(`#${id}`).closest('.control-group').addClass('error');
		} else {
			$(`#${id}`).closest('.control-group').removeClass('error');
			$(`#${id}`).closest('.control-group').addClass('success');
			$(`span:contains('${errorMessage}')`).remove();
		}
	}

	function removeError() {
		//remove the error text
		$(".help-inline").remove();
		//remove the form error
		$(".control-group").removeClass('error').removeClass('success');
	}
	
	function showMessage(type, message) {
		//show messages
		$('#pesan').html(`<div class="alert alert-${type}">`+
			'<button class="close" data-dismiss="alert">×</button>'+
			message+'</div>');
	
		//fungsi tampil pesan delay
		$(`.alert-${type}`).delay(500).show(10, function() {
			$(this).delay(4000).hide(10, function() {
				$(this).remove();
			});
		});
	}

});//end document ready

function hapusMasuk(id_det_msk = null){

	if (id_det_msk) {

		$.ajax({
			url: 'action/barangMasuk/fetchSelectedMasuk.php',
			type: 'post',
			data: {id_det_msk: id_det_msk},
			dataType: 'json',
			success:function(response) {

				//modal footer
				$(".modal-footer").removeClass('hidden');	
				// add the categories id
				$("#pesanHapus").html('<strong>Yakin Ingin Menghapus '+response.brg+' Dengan Total '+response.jml_msk+'?</strong>');
				// remove categories btn clicked to remove the categories function
				$("#hapusMasukBtn").unbind('click').bind('click', function() {
					// remove categories btn
					$("#hapusMasukBtn").button('loading');

					$.ajax({
					url: 'action/barangMasuk/hapusMasuk.php',
					type: 'post',
					data: {id_det_msk: id_det_msk},
					dataType: 'json',
					success:function(response) {

						if(response.success == true) 
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							// close the modal 
							$("#hapusModalMasuk").modal('hide');

							tabelMasuk.ajax.reload(null, false);

							//show messages pesan
							var unique_id = $.gritter.add({
					            // (string | mandatory) the heading of the notification
					            title: 'Pesan Success!',
					            // (string | mandatory) the text inside the notification
					            text: response.messages,
					            // (string | optional) the image to display on the left
					            image: 'img/success-mini.png',
					            // (bool | optional) if you want it to fade out on its own or just sit there
					            sticky: true,
					            // (int | optional) the time you want it to be alive for before fading out
					            time: '',
					            // (string | optional) the class name you want to apply to that specific message
					            class_name: 'my-sticky-class'
					        });

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
					         setTimeout(function(){

						         $.gritter.remove(unique_id, {
						         fade: true,
						         speed: 'slow'
						         });

					         }, 6000)
						}
						else if(response.success == false)
						{
							//button reset
							$("#hapusMasukBtn").button('reset');
							
							// close the modal 
							$("#hapusModalMasuk").modal('hide');

							//show messages simapanKategori
							var unique_id = $.gritter.add({
					            // (string | mandatory) the heading of the notification
					            title: 'Pesan Error!',
					            // (string | mandatory) the text inside the notification
					            text: response.messages,
					            // (string | optional) the image to display on the left
					            image: 'img/error-mini.png',
					            // (bool | optional) if you want it to fade out on its own or just sit there
					            sticky: true,
					            // (int | optional) the time you want it to be alive for before fading out
					            time: '',
					            // (string | optional) the class name you want to apply to that specific message
					            class_name: 'gritter-light'
						    });

					        // You can have it return a unique id, this can be used to manually remove it later using
					        
						}
					}
					});
				});

			}
		});

	}
	else{

		$(".modal-footer").addClass('hidden');

		$("#pesanHapus").html('<strong>Data Tidak Bisa di Hapus/Ubah. Karena Sudah Perpindahan Saldo</strong>');

	}

}

function editMasuk(id_det_msk = null){

	if (id_det_msk) 
	{

		$.ajax(
		{
			url  : 'action/barangMasuk/fetchSelectedMasuk.php',
			type : 'POST',
			data : {id_det_msk: id_det_msk},
			dataType : 'json',
			success:function(data)
			{

				$("#editTgl").val(data.tgl);

				$("#editSuratJLN").val(data.suratJln);

				$("#editBrg").val(data.brg);

				$("#editRak").val(data.rak);

				$("#editKet").val(data.ket);

				$("#editJml").val(data.jml_msk);

				$("#editIdDetMsk").val(data.id_det_msk);

				$("#editId").val(data.id);

				$("#submitEditBarangMsk").unbind('submit').bind('submit', function()
				{

					var editKet = $("#editKet").val();

					if (editKet == "") {
						$("#editKet").after('<span class="help-inline">Edit Keterangan Masih Kosong</span>');
						$("#editKet").closest('.control-group').addClass('error');
					}else{
						//$("#editKet").closest('.control-group').removeClass('error');
						$("#editKet").closest('.control-group').addClass('success');
						$("span").remove(":contains('Edit Keterangan Masih Kosong')");
					}

					if (editKet) {

						var form = $(this);

						$("#editBarangMskBtn").button('loading');

						$.ajax(
						{
							url  : form.attr('action'),
							type : form.attr('method'),
							data : form.serialize(),
							dataType : 'json',
							success:function(response1)
							{
								$("#editBarangMskBtn").button('reset');

								if (response1.success == true)
								{

									tabelMasuk.ajax.reload(null, false);

									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');

									//show messages pesan
									$('#pesan-edit').html('<div class="alert alert-success">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response1.messages+'</div>');
									//fungsi tampil pesan delay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});

								}
								else
								{
									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');
									//show messages simapanKategori
									$('#pesan-edit').html('<div class="alert alert-error">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response1.messages+'</div>');
								}
							}

						});

					}

					return false;

				});
			}
		});
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

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
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