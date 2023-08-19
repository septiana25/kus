$(document).ready(function() {

	var divRequest = $(".div-request").text();

	$('#activeLaporan').addClass('active');

	$("#lihatFaktur").unbind('click').bind('click', function() {
		var mywindow = window.open('action/laporan/lihatFaktur.php', 'Laporan Perfaktur', '');
	});

	$('#submitFaktur').unbind('submit').bind('submit', function() {
		// var b = $("#bulan").val();
		// var t = $("#tahun").val();

		// if (b == "") {
		// 	$("#bulan").after('<span class="help-inline">Bulan Masih Kosong</span>');
		// 	$("#bulan").closest('.control-group').addClass('error');	
		// }else{
		// 	$(".help-inline").remove();
		// 	$("#bulan").closest('.control-group').addClass('success');
		// }
		// if (t == "") {
		// 	$("#tahun").after('<span class="help-inline">Tahun Masih Kosong</span>');
		// 	$("#tahun").closest('.control-group').addClass('error');
		// }else{
		// 	$("#tahun").removeClass('help-inline');
		// 	$("#tahun").closest('.control-group').addClass('success');
		// }

		// if (b && t) {
			var form = $(this);

			$.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'text',
				success:function(response){
					var mywindow = window.open('', 'Laporan Perfaktur', 'height=400,width=600');
					mywindow.document.write('<html><head>');
					mywindow.document.write('</head><body>');
					mywindow.document.write(response);
					mywindow.document.write('</body></html>');
					mywindow.document.close();
					mywindow.focus();
					mywindow.print();
					mywindow.close();
				}
			});
		// }

		return false;
	});	
});

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});