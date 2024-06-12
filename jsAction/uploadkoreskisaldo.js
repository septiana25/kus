$(document).ready(function() {

	$('#activeUpload').addClass('active');
	$('#activeUploadKoreksiSaldo').addClass('active');

	$('#tabelKoreksiSaldo').DataTable({
		'ajax' : 'action/upload/fetchkoreksisaldo.php',
		'order':[],
	});
	
//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});
	
});