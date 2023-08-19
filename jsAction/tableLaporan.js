var tableLaporan;
$(document).ready(function() {

	//
	//var divRequest = $(".div-request").text();
	//
	$('#activeLaporan').addClass('active');

	//if (divRequest == 'laporan') {
		//$('#activeRak').addClass('active');
	$("#submitLaporan").unbind('submit').bind('submit', function() {
		var bulan = $("#bulan");
		if (bulan == "") {
			$("bulan").closest('.control-group').addClass('error');
		}
		return false;
	});
		// tabelLaporan = $('#tableLaporan').DataTable({
		// 'ajax' : 'action/laporan/fetchLaporan.php',
		// 'order': []
		// });//manage Table Rak
	//}
});