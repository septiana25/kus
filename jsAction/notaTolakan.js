var tableNotaTolakan;
$(document).ready(function() {
	var divRequest = $(".div-request").text();
	$('#activeClaim').addClass('active');

	tableNotaTolakan = $('#tableNotaTolakan').DataTable({
		'ajax' : 'action/claim/fetchNotaTolakan.php',
		'order': []
	});

	if (divRequest == 'notaTolakan') {
		$('#activeNotaTolakan').addClass('active');
	}
});

function printPenggantian(idNota = null){
	if (idNota) {
		$.ajax({
			url  : 'action/claim/printNota.php',
			type : 'POST',
			data : {idNota: idNota},
			dataType : 'text',
			success:function(response){
				var mywindow = window.open('', 'Apliksai Inventori Gudang KTA', 'height=400, width=600');
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
	}
}

function lihatData(idNota = null){
	if (idNota) {
		$.ajax({
		url : 'action/claim/fetchLihatNota.php',
		type: 'post',
		data: {idNota: idNota},
		dataType: 'json',
		success:function(response) {
			alert(response.toko);
		}
		});
	}
}