var tabelLaporan;
$(document).ready(function() {


	$('.datepicker').datepicker();

    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"100%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    $(".chosen-select").chosen({width: "100%"});

	//var divRequest = $(".div-request").text();
	//export excel 
	var divRequest = $(".div-request").text();

	$('#activeLaporan').addClass('active');
	
	if (divRequest == "laporanKeluar")
	{

	$('#activeLaporanKeluar').addClass('active');

	//export Excel
	$('#exportExcelBtn').unbind('click').bind('click', function()
	{
		var b = $("#bulan").val();
		var t = $("#tahun").val();

		if (b == "") {
			$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
			$("#bulan").closest('.control-group').addClass('error');	
		}else{
			$(".help-inline").remove();
			$("#bulan").closest('.control-group').addClass('success');
		}
		if (t == "") {
			$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
			$("#tahun").closest('.control-group').addClass('error');
		}else{
			$("#tahun").removeClass('help-inline');
			$("#tahun").closest('.control-group').addClass('success');
		}

		if (b && t) {
			var mywindow = window.open('action/laporan/exportexcel.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
		}
	});

	//export PDF
	$('#exportPDFBtn').unbind('click').bind('click', function() {
		var b = $("#bulan").val();
		var t = $("#tahun").val();

		if (b == "") {
			$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
			$("#bulan").closest('.control-group').addClass('error');	
		}else{
			$(".help-inline").remove();
			$("#bulan").closest('.control-group').addClass('success');
		}
		if (t == "") {
			$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
			$("#tahun").closest('.control-group').addClass('error');
		}else{
			$("#tahun").removeClass('help-inline');
			$("#tahun").closest('.control-group').addClass('success');
		}

		if (b && t) {
			var mywindow = window.open('action/laporan/exportPDF.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
		}
	});

	//export excel rekap
	$('#submitLaporan').unbind('submit').bind('submit', function() {
		var bulan = $("#bulan").val();
		var tahun = $("#tahun").val();

		if (bulan == "") {
			$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
			$("#bulan").closest('.control-group').addClass('error');	
		}else{
			$(".help-inline").remove();
			$("#bulan").closest('.control-group').addClass('success');
		}
		if (tahun == "") {
			$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
			$("#tahun").closest('.control-group').addClass('error');
		}else{
			$("#tahun").removeClass('help-inline');
			$("#tahun").closest('.control-group').addClass('success');
		}

		if (bulan && tahun) {
			var form = $(this);
			//
			$("#cariLaporanBtn").button('loading');
			//
			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'text',
				success:function(response){
					//
					$("#cariLaporanBtn").button('reset');
		
					var mywindow = window.open('', 'Laporan Transaksi', 'height=380,width=700');
					mywindow.document.write(response);
				}
			});
		}
	return false;
	});

		//export Excel Rinci
		$('#exportLapKlrExcelRinciBtn').unbind('click').bind('click', function()
		{
			var b = $("#bulan1").val();
			var t = $("#tahun1").val();

			if (b == "") {
				$("#bulan1").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulan1").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulan1").closest('.control-group').addClass('success');
			}
			if (t == "") {
				$("#tahun1").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahun1").closest('.control-group').addClass('error');
			}else{
				$("#tahun1").removeClass('help-inline');
				$("#tahun1").closest('.control-group').addClass('success');
			}

			if (b && t) {
				var mywindow = window.open('action/laporan/excelTransKLRRinci.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
			}
		});

	}

	else if (divRequest == "laporanMasuk")
	{

		$('#activeLaporanMasuk').addClass('active');

		$('#submitLaporanMasuk').unbind('submit').bind('submit', function() {
			var bulan = $("#bulan").val();
			var tahun = $("#tahun").val();

			if (bulan == "") {
				$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulan").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulan").closest('.control-group').addClass('success');
			}
			if (tahun == "") {
				$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahun").closest('.control-group').addClass('error');
			}else{
				$("#tahun").removeClass('help-inline');
				$("#tahun").closest('.control-group').addClass('success');
			}

			if (bulan && tahun) {
				var form = $(this);
				//
				$("#cariLaporanMskBtn").button('loading');
				//
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					dataType: 'text',
					success:function(response){
						//
						$("#cariLaporanMskBtn").button('reset');
			
						var mywindow = window.open('', 'Laporan Transaksi Masuk', 'height=380,width=700');
						mywindow.document.write(response);
					}
				});
			}
		return false;
		});

		//export PDF
		$('#exportLapMskPDFBtn').unbind('click').bind('click', function() {
			var b = $("#bulan").val();
			var t = $("#tahun").val();

			if (b == "") {
				$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulan").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulan").closest('.control-group').addClass('success');
			}
			if (t == "") {
				$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahun").closest('.control-group').addClass('error');
			}else{
				$("#tahun").removeClass('help-inline');
				$("#tahun").closest('.control-group').addClass('success');
			}

			if (b && t) {
				var mywindow = window.open('action/laporan/exportLapMskPDF.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
			}
		});

		//export Excel
		$('#exportLapMskExcelBtn').unbind('click').bind('click', function()
		{
			var b = $("#bulan").val();
			var t = $("#tahun").val();

			if (b == "") {
				$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulan").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulan").closest('.control-group').addClass('success');
			}
			if (t == "") {
				$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahun").closest('.control-group').addClass('error');
			}else{
				$("#tahun").removeClass('help-inline');
				$("#tahun").closest('.control-group').addClass('success');
			}

			if (b && t) {
				var mywindow = window.open('action/laporan/exportexcelMasuk.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
			}
		});

		$('#exportLapMskExcelRinciBtn').unbind('click').bind('click', function()
		{
			var b = $("#bulan1").val();
			var t = $("#tahun1").val();

			if (b == "") {
				$("#bulan1").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulan1").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulan1").closest('.control-group').addClass('success');
			}
			if (t == "") {
				$("#tahun1").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahun1").closest('.control-group').addClass('error');
			}else{
				$("#tahun1").removeClass('help-inline');
				$("#tahun1").closest('.control-group').addClass('success');
			}

			if (b && t) {
				var mywindow = window.open('action/laporan/excelTransMSKRinci.php?b='+b+'&t='+t, 'Laporan Transaksi', '');
			}
		});


	}

	else if (divRequest == "laporanKartuStock")
	{

		$('#activeKartu').addClass('active');

		$('#submitLapKartuStok').unbind('submit').bind('submit', function() {

			var id_brgKartu = $("#id_brgKartu").val();
			var bulanKartu  = $("#bulanKartu").val();
			var tahunKartu  = $("#tahunKartu").val();

			if (id_brgKartu == "") {
				$("#id_brgKartu").after('<span class="help-inline">Ukuran Kartu Stock Masih Kosong</span>');
				$("#id_brgKartu").closest('.control-group').addClass('error');	
			}else{
				$("#id_brgKartu").closest('.control-group').addClass('success');
				$("span").remove(":contains('Ukuran Kartu Stock Masih Kosong')");
			}
			if (bulanKartu == "") {
				$("#bulanKartu").after('<span class="help-inline">Bulan Kartu Stock Masih Kosong</span>');
				$("#bulanKartu").closest('.control-group').addClass('error');	
			}else{
				$("#bulanKartu").closest('.control-group').addClass('success');
				$("span").remove(":contains('Bulan Kartu Stock Masih Kosong')");
			}
			if (tahunKartu == "") {
				$("#tahunKartu").after('<span class="help-inline">Tahun Kartu Stock Masih Kosong</span>');
				$("#tahunKartu").closest('.control-group').addClass('error');
			}else{
				$("#tahunKartu").closest('.control-group').addClass('success');
				$("span").remove(":contains('Tahun Kartu Stock Masih Kosong')");
			}

			if (id_brgKartu && bulanKartu && tahunKartu) {
				var form = $(this);
				//
				$("#cariLaporanKartuBtn").button('loading');
				//
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					dataType: 'text',
					success:function(response){
						//
						$("#cariLaporanKartuBtn").button('reset');
			
						var mywindow = window.open('', 'Laporan Transaksi Masuk', 'height=380,width=700');
						mywindow.document.write(response);
					}
				});
			}
		return false;
		});

		//export PDF
		$('#exportLapKartuPDFBtn').unbind('click').bind('click', function() {

			alert("Coming Soon");
		});

		//export Excel
		$('#exportLapKartuExcelBtn').unbind('click').bind('click', function()
		{
			
			var b  = $("#bulanKartu").val();
			var t  = $("#tahunKartu").val();
			var id = $("#id_brgKartu").val();

			if (b == "") {
				$("#bulanKartu").after('<span class="help-inline">Bulan Masih Kosong</span>');
				$("#bulanKartu").closest('.control-group').addClass('error');	
			}else{
				$(".help-inline").remove();
				$("#bulanKartu").closest('.control-group').addClass('success');
			}
			if (t == "") {
				$("#tahunKartu").after('<span class="help-inline">Tahun Masih Kosong</span>');
				$("#tahunKartu").closest('.control-group').addClass('error');
			}else{
				$("#tahunKartu").removeClass('help-inline');
				$("#tahunKartu").closest('.control-group').addClass('success');
			}

			if (b && t && id) {
				var mywindow = window.open('action/laporan/excelKartuStock.php?b=' + b + '&t=' + t + '&id=' + id , 'Laporan Transaksi', '');
			}

		});


	}

	else if (divRequest == "laporanRtr")
	{

		$('#activeLaporanRetur').addClass('active');

		$('#submitLaporanRetur').unbind('submit').bind('submit', function() {


			var tglAwalRtr   = $("#tglAwalRtr").val();
			var tglAkhirRtr  = $("#tglAkhirRtr").val();

			if (tglAwalRtr && tglAkhirRtr) {
				var form = $(this);
				//
				$("#cariLaporanKartuBtn").button('loading');
				//
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					dataType: 'text',
					success:function(response){
						//
						$("#cariLaporanKartuBtn").button('reset');
			
						var mywindow = window.open('', 'Laporan Transaksi Masuk', 'height=380,width=700');
						mywindow.document.write('<html><head>');
						mywindow.document.write('</head><body>');
						mywindow.document.write(response);
						mywindow.document.write('</body></html>');
					}
				});
			}
		return false;
		});

		//export PDF
		$('#exportLapRtrPDFBtn').unbind('click').bind('click', function() {

			alert("Coming Soon");
		});

		//export Excel
		$('#exportLapRtrExcelBtn').unbind('click').bind('click', function()
		{
			var a = $("#tglAwalRtr").val();
			var b = $("#tglAkhirRtr").val();

			if ( a && b)
			{

				var mywindow = window.open('action/return/excelReturn.php?a=' + a + '&b=' + b, 'Laporan Transaksi', '');
				// var mywindow = window.open('action/return/excelReturn.php?a=' + a + '&b=' + b, 'Laporan Transaksi', '');

			}
		});

	}

	else if (divRequest == "laporanMutasi")
	{

		$('#activeLaporanMutasi').addClass('active');

		$('#submitLaporanMutasi').unbind('submit').bind('submit', function() {


			var tglAwalMTS   = $("#tglAwalMTS").val();
			var tglAkhirMTS  = $("#tglAkhirMTS").val();

			if (tglAwalMTS && tglAkhirMTS) {

				var form = $(this);
				//
				$("#cariLaporanMTSBtn").button('loading');
				//
				$.ajax({
					url: form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					dataType: 'text',
					success:function(response){
						//
						$("#cariLaporanMTSBtn").button('reset');
			
						var mywindow = window.open('', 'Laporan Transaksi Masuk', 'height=380,width=700');
						mywindow.document.write('<html><head>');
						mywindow.document.write('</head><body>');
						mywindow.document.write(response);
						mywindow.document.write('</body></html>');
					}
				});
			}
		return false;
		});

		//export PDF
		$('#exportLapMTSPDFBtn').unbind('click').bind('click', function() {

			var a = $("#tglAwalMTS").val();
			var b = $("#tglAkhirMTS").val();

			if ( a && b)
			{

				alert("Coming Soon");

				// var mywindow = window.open('action/return/excelReturn.php?a=' + a + '&b=' + b, 'Laporan Transaksi', '');

			}

		});

		//export Excel
		$('#exportLapMTSExcelBtn').unbind('click').bind('click', function()
		{
			var a = $("#tglAwalMTS").val();
			var b = $("#tglAkhirMTS").val();

			if ( a && b)
			{

				var mywindow = window.open('action/laporan/excelMutasi.php?a=' + a + '&b=' + b, 'Laporan Transaksi', '');

			}
			
		});

	}

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});
	
});