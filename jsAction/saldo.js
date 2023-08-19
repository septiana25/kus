var tabelSaldo;
$(document).ready(function() {
	$(".loading").addClass('hidden');
	tabelSaldo = $('#tabelSaldo').DataTable({
		'ajax' : 'action/saldo/fetchSaldo.php',
		'order': [] 
	});
	//$("#addSaldoBtnModal").addClass('hidden');
	//$(".hidden-saldo").addClass('hidden');
	// $(".hidden-backup").addClass('hidden');
	$('#submitBackup').unbind('submit').bind('submit', function() {
		//ambil data form
		var form = $(this);
		$(".loading").removeClass('hidden');
		$("#modalBackup").modal('hide');
		$.ajax({
			url : form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(),
			dataType: 'json',
			success:function(response){

			if (response.success == true) {
				$("#addSaldoBtnModal").removeClass('hidden');
				//$(".hidden-saldo").removeClass('hidden');

				$("#BackupBtnModal").addClass('hidden');
				$(".hidden-backup").addClass('hidden');
				$(".loading").addClass('hidden');
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
	        	setTimeout(function(){
			         $.gritter.remove(unique_id, {
			         fade: true,
			         speed: 'slow'
			         });

		        }, 6000)
			}else{
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
				}
			}
		});
		return false;
	});

	$('#submitSaldo').unbind('submit').bind('submit', function() {
		//ambil data form
		var form = $(this);
		$(".loading").removeClass('hidden');
		$("#updateSaldo").modal('hide');
		$.ajax({
			url : form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(),
			dataType: 'json',
			success:function(response){
			//$(".loading").addClass('hidden');
			
			//show messages pesan
			//
			if (response.success == 'cek_tgl') {

				$(".loading").addClass('hidden');
				$("#addSaldoBtnModal").addClass('hidden');
				
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
			}
			if (response.success == true) {
				$(".loading").addClass('hidden');
				$("#addSaldoBtnModal").addClass('hidden');
				tabelSaldo.ajax.reload(null, false);
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
			}
			if (response.success == false) {
				$(".loading").addClass('hidden');
				tabelSaldo.ajax.reload(null, false);
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
			}
			}
		});
		return false;
	});
});

//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});
