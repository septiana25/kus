var tableNotaPengganti;
$(document).ready(function() {
	var divRequest = $(".div-request").text();
	$('#activeClaim').addClass('active');

	tableNotaPengganti = $('#tableNotaPengganti').DataTable({
		'ajax' : 'action/claim/fetchNotaPenggantian.php',
		'order': []
	});

	if (divRequest == 'notaPengganti') {
		$('#activeNotaPengganti').addClass('active');
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
			$('#toko').val(response.toko);
			$('#keputusan').val(response.keputusan);
			$('#idNota').val(response.idNota);

			var tabelNota;
			tabelNota = $('#tabelNota').DataTable({
				'ajax' : {
					'url' : 'action/claim/fetchTableLihatNota.php',
					'type': 'POST',
					'data': {idNota: idNota},
					// 'dataType': 'json'
				},
				'order': [],
				// 'displayLength' : 25,
				// 'searching' : false,
				'paging' 	: false,
				'ordering'	: false,

			}); 

			tabelNota.destroy();// hapus isi table
		}
		});
	}else{
		alert('Oops! Reload this page');
	}
}