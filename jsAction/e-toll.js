var tabelTrans_Toll;
var tabelTmbhToll;
var tabelToll;
var tabelDataPosting;
$(document).ready(function() {
	// active manu barang
	$('#activeEToll').addClass('active');

	//active combobox
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }

    $('.datepicker').datepicker();

    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $(".chosen-select").chosen({width: "95%"});

	//mengambil data div class div-request
	var divRequest = $(".div-request").text();

/*--------------------------------------------------------*/
	if (divRequest == 'trans_toll') {
	// active manu barang
	$('#activeTrans_toll').addClass('active');

    //menampilkan data di tabel
    tabelTrans_Toll = $("#tabelTrans_Toll").DataTable({
    	'ajax'  : 'action/etoll/fetchTransToll.php',
    	'order' : [],
    });

    $("#addEtollBtnModal").unbind('click').bind('click', function() {

    $("#submitEToll").unbind('submit').bind('submit', function() {
		var NoEtoll    = $("#NoEtoll").val();
		var rute       = $("#rute").val();
		var ruteAkhir  = $("#ruteAkhir").val();
		var bayar      = $("#bayar").val();
		var keterangan = $("#keterangan").val();
		var tgl        = $("#tgl").val();

		//cek data di filed
		if (NoEtoll == "") {
			$("#NoEtoll").after('<span class="help-inline">No E-Toll Masih Kosong</span>');
			$("#NoEtoll").closest('.control-group').addClass('error');
		}else{
			$("#NoEtoll").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (rute == "") {
			$("#rute").after('<span class="help-inline">Rute Awal Masih Kosong</span>');
			$("#rute").closest('.control-group').addClass('error');
		}else{
			$("#rute").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (ruteAkhir == "") {
			$("#ruteAkhir").after('<span class="help-inline">Rute Akhir Masih Kosong</span>');
			$("#ruteAkhir").closest('.control-group').addClass('error');
		}else{
			$("#ruteAkhir").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (bayar == "") {
			$("#bayar").after('<span class="help-inline">Total Bayar Masih Kosong</span>');
			$("#bayar").closest('.control-group').addClass('error');
		}else{
			$("#bayar").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (keterangan == "") {
			$("#keterangan").after('<span class="help-inline">Keterangan Masih Kosong</span>');
			$("#keterangan").closest('.control-group').addClass('error');
		}else{
			$("#keterangan").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (tgl == "") {
			$("#tgl").after('<span class="help-inline">Tanggal Masih Kosong</span>');
			$("#tgl").closest('.control-group').addClass('error');
		}else{
			$("#tgl").closest('.control-group').addClass('success');
			$(".help-inline").remove();			
		}

		if (NoEtoll && rute && ruteAkhir && bayar && keterangan && tgl) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanTransTollBtn").button('loading');

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){
					//button reset
					$("#simpanTransTollBtn").button('reset');
					if (response.success == true) {
						//reload data tabel
						tabelTrans_Toll.ajax.reload(null, false);
						//reset isi form 
						//$("#submitEToll")[0].reset();
						
						$("#rute").val("");
						$("#ruteAkhir").val("");
						$("#bayar").val("");
						$("#keterangan").val("");
						document.getElementById("rute").focus();
						//reset combobox
						//$("#NoEtoll").trigger("chosen:updated");
						//remove pesan error
						$(".control-group").removeClass('error').removeClass('success');
						//tampil pesan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});
					}

					if (response.success == false) {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');						
					}				
				} 
			});

		}

		return false;

    });
    });

    $("#postingBtnModal").unbind('click').bind('click', function() {

    //fungsi menampilkan data table 
    $("#postNoEtoll").change(function (){
    	$("#postNoEtoll option:selected").each(function () {
    		postNoEtoll = $(this).val(); //mengambil data combobox
    		$.post("action/etoll/fetchSelectedViewDetTrans.php", {postNoEtoll: postNoEtoll}, function(data){
    				$("#tabelPost").html(data);
    			});
    		
    	});
    });


    		$("#postingSubmitTrans").unbind('submit').bind('submit', function() {
    		var postNoEtoll = $("#postNoEtoll").val();

			if (postNoEtoll == "") {
				$("#postNoEtoll").after('<span class="help-inline">No EToll Masih Kosong</span>');
				$("#postNoEtoll").closest('.control-group').addClass('error');
			}else{
				$("#postNoEtoll").closest('.control-group').addClass('success');
				$(".help-inline").remove();
			}

			if (postNoEtoll) {
				//ambil data form
				var form = $(this);
				//button loading
				$("#postingTransTollBtn").button('loading');

				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : form.serialize(),
					dataType : 'json',
					success:function(response){
						$("#postingTransTollBtn").button('loading');

						if (response.success == true) {
							
							$('.help-inline').remove();
							$(".control-group").removeClass('error').removeClass('success');
							//close modal
							$("#modalPosting").modal('hide');
							//reset combobox
							$("#postNoEtoll").trigger("chosen:updated");							
							//show pesan
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
						}if (response.success == false) {
						//show messages pesan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');							
						}
					}
				});
			}

    		return false;

    		});

    });


	}

/*--------------------------------------------------------*/
	//sub menu master E-toll
	else if (divRequest == 'masterEToll') {

    //menampilkan data di tabel
    tabelToll = $("#tabelToll").DataTable({
    	'ajax'  : 'action/etoll/fetchMasterToll.php',
    	'order' : [],
    });


	// active manu barang
	$('#activeMasterToll').addClass('active');

	$("#addMasterTollModal").unbind('click').bind('click', function(){
	$("#submitMasterToll").unbind('submit').bind('submit', function(){
		var noEToll   = $("#noEToll").val();
		var pemegang  = $("#pemegang").val();
		var nopol     = $("#nopol").val();
		//var saldoAwal = $("#saldoAwal").val();

		//cek data di filed
		if (noEToll == "") {
			$("#noEToll").after('<span class="help-inline">No E-Toll Masih Kosong</span>');
			$("#noEToll").closest('.control-group').addClass('error');
		}else{
			$("#noEToll").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (pemegang == "") {
			$("#pemegang").after('<span class="help-inline">Pemegang E-Toll Masih Kosong</span>');
			$("#pemegang").closest('.control-group').addClass('error');
		}else{
			$("#pemegang").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (nopol == "") {
			$("#nopol").after('<span class="help-inline">No Polisi Masih Kosong</span>');
			$("#nopol").closest('.control-group').addClass('error');
		}else{
			$("#nopol").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		/*if (saldoAwal == "") {
			$("#saldoAwal").after('<span class="help-inline">Saldo Awal Masih Kosong</span>');
			$("#saldoAwal").closest('.control-group').addClass('error');
		}else{
			$("#saldoAwal").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}*/

		if (noEToll && pemegang && nopol) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanMasterTollBtn").button('loading');

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){
					//button reset
					$("#simpanMasterTollBtn").button('reset');

					if (response.success == true) {
						//relode data table
						//tabelToll.ajax.reload(null, false);
						tabelToll.ajax.reload(null, false);
						//reset the form text
						$("#submitMasterToll")[0].reset();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});																		
					}

					if (response.success == false) {
						//remove the error text
						$(".help-inline").remove();
						//remove the form error
						$(".control-group").removeClass('error').removeClass('success');
						//show messages pesan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
					}
				}
			});			
		}

		return false;
	});
	});
	}

/*--------------------------------------------------------*/
	
	//sub menu master E-toll
	else if (divRequest == 'tmbhSaldoToll') {

    //menampilkan data di tabel
    tabelTmbhToll = $("#tabelTmbhToll").DataTable({
    	'ajax'  : 'action/etoll/fetchTmbhSaldo.php',
    	'order' : [],
    });

    // active manu barang
	$('#activeTambahSaldoToll').addClass('active');

	$("#addTmbhSaldoTollBtn").unbind('click').bind('click', function() {
	$("#submitTmbhSaldo").unbind('submit').bind('submit', function() {

		var NoEtoll      = $("#NoEtoll").val();
		var nominalSaldo = $("#nominalSaldo").val();

		if (NoEtoll == "") {
			$("#NoEtoll").after('<span class="help-inline">No E-Toll Masih Kosong</span>');
			$("#NoEtoll").closest('.control-group').addClass('error');
		}else{
			$("#NoEtoll").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (nominalSaldo == "") {
			$("#nominalSaldo").after('<span class="help-inline">Nominal Masih Kosong</span>');
			$("#nominalSaldo").closest('.control-group').addClass('error');
		}else{
			$("#nominalSaldo").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (NoEtoll && nominalSaldo) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanTmbhSaldoBtn").button('loading');

			$.ajax({
				url  : form.attr('action'),
				type : form.attr('method'),
				data : form.serialize(),
				dataType : 'json',
				success:function(response){
					//button loading
					$("#simpanTmbhSaldoBtn").button('reset');

					if (response.success == true) {
						//reload data tabel
						tabelTmbhToll.ajax.reload(null, false);
						//reset data filed
						$("#submitTmbhSaldo")[0].reset();
						//hapus pesan data kosong
						$(".help-inline").remove();
						//hapus warna data kosng
						$(".control-group").removeClass('error').removeClass('success');
						//tampil pesan simpan
						$('#pesan').html('<div class="alert alert-success">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
						//fungsi tampil pesan delay
						$(".alert-success").delay(500).show(10, function() {
							$(this).delay(4000).hide(10, function() {
								$(this).remove();
							});
						});						
					}

					if (response.success == false) {
						//hapus pesan data kosong
						$(".help-inline").remove();
						//hapus warna data kosng
						$(".control-group").removeClass('error').removeClass('success');
						//tampil pesan simpan
						$('#pesan').html('<div class="alert alert-error">'+
							'<button class="close" data-dismiss="alert">×</button>'+
							response.messages+'</div>');
	
					}
				}
			});

		}

		return false;
	});
	});

	}

	

/*--------------------------------------------------------*/
	
	//sub menu master E-toll
	else if (divRequest == 'DataPosting') {


    // active manu barang
	$('#activeDataPosting').addClass('active');

	tabelDataPosting = $("#tabelDataPosting").DataTable({
		'ajax'  : 'action/etoll/fetchDataPosting.php',
		'order' : []
		});

	$("#printPostingSubmit").unbind('submit').bind('submit', function() {
		var id_post = $("#id_post").val();

		$.ajax({
			url  : 'action/etoll/printPostingEToll.php',
			type : 'POST',
			data : {id_post : id_post},
			dataType : 'text',
			success:function(response){
				//console.log(response);
				var mywindow = window.open('', 'Laporan Pengajuam TOP UP', 'height=400, width=600');
				mywindow.document.write('<html><head>');
				mywindow.document.write('</head><body>');
				mywindow.document.write(response);
				mywindow.document.write('</body></html>');

				mywindow.document.close();// necessary for IE >= 10
				mywindow.focus();// necessary for IE >= 10

				mywindow.print();
				mywindow.close();
			}
		});

		
		return false;
	});

	}


});//end document ready

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}

function editNoToll(id_toll = null){
	// remove the added barang id 
	$("#editNoTollId").remove();
	if (id_toll) {
		// remove the added barang id 
		$("#editNoTollId").remove();
		//modal footer
		$(".modal-footer").addClass('div-hide');
		$.ajax({
			url  : 'action/etoll/fetchSelectedNoToll.php',
			type : 'POST',
			data : {id_toll: id_toll},
			dataType : 'json',
			success:function(response){
				//alert(response.no_toll);
				//modal footer
				$(".modal-footer").removeClass('div-hide');
				//set 
				$("#editNoEToll").val(response.no_toll);
				$("#editPemegang").val(response.pemegang);
				$("#editNopol").val(response.no_pol);
				//tambah filed editNoTollId
				$(".modal-footer").after('<input type="hidden" name="editNoTollId" id="editNoTollId" value="'+response.id_toll+'" />');

				$("#editMasterNoToll").unbind('submit').bind('submit', function(){
					var editNoEToll = $("#editNoEToll").val();
					var editPemegang = $("#editPemegang").val();
					var editNopol = $("#editNopol").val();

					if (editNoEToll == "") {
						$("#editNoEToll").after('<span class="help-inline">No Toll Masih Kosong</span>');
						$("#editNoEToll").closest('.control-group').addClass('error');
					}else{
						$("#editNoEToll").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editPemegang == "") {
						$("#editPemegang").after('<span class="help-inline">Pemegang E-Toll Masih Kosong</span>');
						$("#editPemegang").closest('.control-group').addClass('error');
					}else{
						$("#editPemegang").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editNopol == "") {
						$("#editNopol").after('<span class="help-inline">No Polisi Masih Kosong</span>');
						$("#editNopol").closest('.control-group').addClass('error');
					}else{
						$("#editNopol").closest('.control-group').addClass('success');
						$(".help-inline").remove();
					}

					if (editNoEToll && editPemegang && editNopol) {
						//ambil data form
						form = $(this);
						//button loading
						$("#editMaterTollBtn").button('loading');

						$.ajax({
							url  : form.attr('action'),
							type : form.attr('method'),
							data : form.serialize(),
							dataType : 'json',
							success:function(response){
								//button reset
								$("#editMaterTollBtn").button('reset');

								if (response.success == true) {

									tabelToll.ajax.reload(null, false);
									//hapus pesan di filed error
									$(".help-inline").remove();
									//hapus pesan di filed error
									$(".control-group").removeClass('error').removeClass('success');
									//tampil pesan
									$('#edit-pesan').html('<div class="alert alert-success">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
									//funsi tamil pesan dellay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});
									
								}

								if (response.success == false) {
									//hapus pesan di filed error
									$(".help-inline").remove();
									//hapus pesan di filed error
									$(".control-group").removeClass('error').removeClass('success');
									//tampil pesan 
									$('#edit-pesan').html('<div class="alert alert-error">'+
									'<button class="close" data-dismiss="alert">×</button>'+
									response.messages+'</div>');
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

function editTransToll(id_DetTrans = null){
	$('#editIdDetTrans').remove();
	if (id_DetTrans) {
		$.ajax({
			url  : 'action/etoll/fetchSelectedTrans.php',
			type : 'POST',
			data : {id_DetTrans: id_DetTrans},
			dataType: 'json',
			success:function(response){

				$('#editIdDetTrans').remove();
				//modal footer
				$(".modal-footer").removeClass('div-hide');	
				// set the categories name
				$("#editNoEtoll").val(response.no_toll);
				// set the barang status
				$("#editRute").val(response.rute);			
				$("#editRuteAkhir").val(response.ruteAkhir);
				
				// if (response.stus == 1) {
				// 	$("#editBayar").is(":hidden");
				// }
				$("#editBayar").val(response.bayar);				
				$("#editKeterangan").val(response.ket);
				$(".modal-footer").after('<input type="hidden" name="editIdDetTrans" id="editIdDetTrans" value="'+response.id_DetTrans+'" />');			
				
				$("#editSubmitTrans").unbind('submit').bind('submit', function(){
					var editNoEtoll    = $("#editNoEtoll").val();
					var editRute       = $("#editRute").val();
					var editRuteAkhir  = $("#editRuteAkhir").val();
					var editBayar      = $("#editBayar").val();
					var editKeterangan = $("#editKeterangan").val();

					//cek data di filed
					if (editNoEtoll == "") {
						$("#editNoEtoll").after('<span class="help-inline">No E-Toll Masih Kosong</span>');
						$("#editNoEtoll").closest('.control-group').addClass('error');
					}else{
						$("#editNoEtoll").closest('.control-group').addClass('success');
						$(".help-inline").remove();			
					}

					if (editRute == "") {
						$("#editRute").after('<span class="help-inline">Rute Masih Kosong</span>');
						$("#editRute").closest('.control-group').addClass('error');
					}else{
						$("#editRute").closest('.control-group').addClass('success');
						$(".help-inline").remove();			
					}

					if (editRuteAkhir == "") {
						$("#editRuteAkhir").after('<span class="help-inline">Rute Akhir Masih Kosong</span>');
						$("#editRuteAkhir").closest('.control-group').addClass('error');
					}else{
						$("#editRuteAkhir").closest('.control-group').addClass('success');
						$(".help-inline").remove();			
					}

					if (editBayar == "") {
						$("#editBayar").after('<span class="help-inline">Total Bayar Masih Kosong</span>');
						$("#editBayar").closest('.control-group').addClass('error');
					}else{
						$("#editBayar").closest('.control-group').addClass('success');
						$(".help-inline").remove();			
					}

					if (editKeterangan == "") {
						$("#editKeterangan").after('<span class="help-inline">Keterangan Masih Kosong</span>');
						$("#editKeterangan").closest('.control-group').addClass('error');
					}else{
						$("#editKeterangan").closest('.control-group').addClass('success');
						$(".help-inline").remove();			
					}

					if (editNoEtoll && editRute && editRuteAkhir && editBayar && editKeterangan) {
						//ambil data form
						var form = $(this);
						//button loading
						$("#editTransTollBtn").button('loading');

						$.ajax({
							url  : form.attr('action'),
							type : form.attr('method'),
							data : form.serialize(),
							dataType : 'json',
							success:function(response){
								//button reset
								$("#editTransTollBtn").button('reset');
								if (response.success == true) {
									//reload data tabel
									tabelTrans_Toll.ajax.reload(null, false);
									//remove pesan error
									$(".control-group").removeClass('error').removeClass('success');
									//tampil pesan
									$('#pesan-edit').html('<div class="alert alert-success">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response.messages+'</div>');
									//fungsi tampil pesan delay
									$(".alert-success").delay(500).show(10, function() {
										$(this).delay(4000).hide(10, function() {
											$(this).remove();
										});
									});
								}

								if (response.success == false) {
									//remove the error text
									$(".help-inline").remove();
									//remove the form error
									$(".control-group").removeClass('error').removeClass('success');
									//show messages pesan
									$('#pesan-edit').html('<div class="alert alert-error">'+
										'<button class="close" data-dismiss="alert">×</button>'+
										response.messages+'</div>');						
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


function viewPost(id_post = null){

	$("#tabelViewDataPost").html("");
	$("#NoEtoll").val("");
	$("#pemegang").val("");
	$("#no_pol").val("");
	$("#id_post").remove();

	// modal spinner
	$('.modal-loading').removeClass('div-hide');
	if (id_post) {

		$.post('action/etoll/fetchViewDataPosting.php', {id_post : id_post}, function(data) {
			$("#tabelViewDataPost").html(data);
			// modal spinner
			$('.modal-loading').addClass('hide');			
		});

		$.ajax({
			url  : 'action/etoll/fetchSelectedDataPosting.php',
			type : 'POST',
			data : {id_post : id_post},
			dataType : 'json',
			success:function(response){
				$("#NoEtoll").val(response.no_toll);
				$("#pemegang").val(response.pemegang);
				$("#no_pol").val(response.no_pol);
				$(".modal-footer").after('<input type="hidden" name="id_post" id="id_post" value="'+response.no_pos+'" />');
			}
		});

	}
}


function hurufBesar(a){
	setTimeout(function() {
		a.value = a.value.toUpperCase();
	}, 1);
}

function harusAngka(a){
	if (!/^[0-9.]+$/.test(a.value)) {
		a.value = a.value.substring(0,a.value.length-1000);
	}
}

