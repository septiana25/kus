$(document).ready(function(){

	$('#activeHarga').addClass('active');
	$('.chosen-select').chosen(
		{
			width:"50%",
			no_results_text:'Oops, tidak ditemukan!'
	});
	var divRequest = $('.div-request').text();
	
	$('.chosen-select').trigger('chosen:activate');

	$("#gambar").attr('required', true);
	$('#tidakGambar').on('click',function(){

	   if( this.checked == true){	    	
	    	$("#gambar").removeAttr('required');
	   }
	   else if (this.checked == false) {
	   		$("#gambar").attr('required', true);
	   }
	});

	if (divRequest == 'harga')
	{
		/*var table_imit = $('#table_imit').DataTable({
			'ajax'  : 'action/limit/fetchLimit.php',
			'order' : []
		});*/
		$('#activeTamabahHarga').addClass('active');

		//$('#addLimitBtnModal').unbind('click').bind('click', function(){
			
			$("#hargaCustomer").change(function (){
				$("#hargaCustomer option:selected").each(function () {
					hargaCustomer = $(this).val(); //mengambil data combobox
					$('#namaHarga').val('');
					$('#namaHarga').typeahead({
					 minLength : 3,

					 source: function(namaHarga, result)
					 {
					  $.ajax({
					   url:"action/harga/fetchAutoCompliteName.php",
					   type:"POST",
					   data:{lihatNama :namaHarga, lihatCustomer : hargaCustomer},
					   dataType:"json",
					   success:function(data)
					   {
					    result($.map(data, function(item){
					     return item;
					    }));

					    /*$('#kodeLimit').change(function(){
					    	var kode = $('#kodeLimit').val();
					    	$.ajax({
					    		url  : 'action/customer/fetchSelectedNamaCS.php',
					    		type : 'POST',
					    		data : {kode : kode, lihatCustomer : limitCustomer},
					    		dataType : 'JSON',
					    		success:function(response){

					    			$('#namaLimit').val(response.namaCS);
					    		}
					    	});
					    });*/

					   }
					  })
					 },
				// 	 templates:{
			//  suggestion:Handlebars.compile('<div class="row"><div class="col-md-2" style="padding-right:5px; padding-left:5px;"><img src="uploads/100/TSK-TYM-01-20181010-133214205896514.jpeg" class="img-thumbnail" width="48" /></div><div class="col-md-10" style="padding-right:5px; padding-left:5px;">Ian Septiana</div></div>')
			// }
					});
				});
			});


			$('.submitHargaCustomer').unbind('submit').bind('submit', function(){

				//var limitCustomer = $('#limitCustomer').val();
				var namaHarga     = $('#namaHarga').val();

				/*if (limitCustomer == "") {
					$("#limitCustomer").after('<span class="help-inline">Customer Masih Kosong</span>');
					$("#limitCustomer").closest('.control-group').addClass('error');
					$('.chosen-select').trigger('chosen:activate');
				}else{
					$("#limitCustomer").closest('.control-group').addClass('success');
					$("span").remove(":contains('Customer Masih Kosong')");		
				}*/

				if (namaHarga == "") {
					$("#namaHarga").after('<span class="help-inline">Nama Customer Masih Kosong</span>');
					$("#namaHarga").closest('.control-group').addClass('error');
					$('.chosen-select').trigger('chosen:activate');
				}else{
					$("#namaHarga").closest('.control-group').addClass('success');
					$("span").remove(":contains('Nama Customer Masih Kosong')");		
				}

				if ( namaHarga )
				{
					$('#simpanHargaCS').button('loading');
					$('#statusUpload').removeClass('hidden');

					var form = $(this);
					var formData = new FormData(this);

					$.ajax({
						url  : form.attr('action'),
						type : form.attr('method'),
						data : formData,
						dataType : 'JSON',
						cache: false,
						contentType: false,
						processData: false,
						success:function(response){

							$('#simpanHargaCS').button('reset');
							$('#statusUpload').addClass('hidden');

							//table_imit.ajax.reload(false, null);

							if (response.success == 'warning') 
							{

								var unique_id = $.gritter.add({
						            // (string | mandatory) the heading of the notification
						            title: 'Pesan Warning!',
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
				                 setTimeout(function(){

				        	         $.gritter.remove(unique_id, {
				        	         fade: true,
				        	         speed: 'slow'
				        	         });

				                 }, 6000)
							}

							else if (response.success == true)
							{
								$('.submitHargaCustomer')[0].reset();
								$(".control-group").removeClass('error').removeClass('success');
								$('.chosen-select').trigger("chosen:updated");
								$('.chosen-select').trigger("chosen:activate");
								
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
							else if (response.success == false)
							{

								var unique_id = $.gritter.add({
						            // (string | mandatory) the heading of the notification
						            title: 'Pesan Warning!',
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
				}


				return false;
			});
		//});
	}
	else
	{
		$('#activeLiatHarga').addClass('active');

			$("#lihatCustomer").change(function (){
				$("#lihatCustomer option:selected").each(function () {
					lihatCustomer = $(this).val(); //mengambil data combobox
					$('#lihatNama').val('');
					$('#lihatNama').typeahead({
					 minLength : 2,

					 source: function(lihatNama, result)
					 {
					  $.ajax({
					   url:"action/harga/fetchAutoCompliteHarga.php",
					   type:"POST",
					   data:{lihatNama :lihatNama, lihatCustomer : lihatCustomer},
					   dataType:"json",
					   success:function(data)
					   {
					    result($.map(data, function(item){
					     return item;
					    }));
					   }
					  })
					 },
				// 	 templates:{
	   //  suggestion:Handlebars.compile('<div class="row"><div class="col-md-2" style="padding-right:5px; padding-left:5px;"><img src="uploads/100/TSK-TYM-01-20181010-133214205896514.jpeg" class="img-thumbnail" width="48" /></div><div class="col-md-10" style="padding-right:5px; padding-left:5px;">Ian Septiana</div></div>')
	   // }
					});
				});
			});


			$('.submitCariCustomer').unbind('submit').bind('submit', function(){

				var lihatCustomer    = $("#lihatCustomer").val();

				if (lihatCustomer == "") {
					$("#lihatCustomer").after('<span class="help-inline">Customer Masih Kosong</span>');
					$("#lihatCustomer").closest('.control-group').addClass('error');
					$('.chosen-select').trigger('chosen:activate');
				}else{
					$("#lihatCustomer").closest('.control-group').addClass('success');
					$("span").remove(":contains('Customer Masih Kosong')");		
				}

				if (lihatCustomer)
				{
					var form = $(this);
					// var showTableSearch = $('#t_lihatDataCS').DataTable({});
					$('#showTableSearch').html('');
					$.ajax({
						url  : form.attr('action'),
						type : form.attr('method'),
						data : form.serialize(),
						dataType : 'text',
					 	success:function(data){

					 		//remove pesan error
					 		$(".control-group").removeClass('error').removeClass('success');
					 		$('#showTableSearch').html(data);
					 		$('#lihatNama').focus();

					 		$('#t_searchCS').DataTable();
					 		
					 	}

					});
				}
				return false;

			});

			$('.submitUploadCustomer').unbind('submit').bind('submit', function(){

				$('#btnSubmitUpload').button('loading');
				$('#statusUpload').removeClass('hidden');
				
				var idHarga = $('#idHarga').val();
				var form = $(this);
				var formData = new FormData(this);

				$.ajax({
					url  : form.attr('action'),
					type : form.attr('method'),
					data : formData,
					dataType : 'JSON',
					cache: false,
					contentType: false,
					processData: false,
					success:function(response){

						$('#statusUpload').addClass('hidden');
						$('#btnSubmitUpload').button('reset');


						if (response.success == true) {

							$('.submitUploadCustomer')[0].reset();
							$('.lihatHasilGambar').html('');
							$('#UploadGambar').modal('hide');
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

						        $.post('action/harga/viewGaleryHarga.php', {idHarga : idHarga}, function(data) {
						        	$(".gmbrUpload").html(data);
						        	// modal spinner
						        	//$('.modal-loading').addClass('hide');	
						        			
						        });
						}
						else if (response.success == false) {

							var unique_id = $.gritter.add({
					            // (string | mandatory) the heading of the notification
					            title: 'Pesan Warning!',
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
		
	}//end else

});

function lihatGambar(idHarga)
{
	$('.lihatHasilGambar').html('');
	//console.log(idHarga);
	if (idHarga)
	{

		$.ajax({

			url  : 'action/harga/viewGaleryHarga.php',
			type : 'POST',
			data : {idHarga : idHarga},
			dataType : 'text',
			success:function(data)
			{
				$('.lihatHasilGambar').html(data);
			}

		});

	}

}

function uploadGambar(idHarga, noBuku)
{
	$('.gmbrUpload').html('');
	$('#idHarga').val('');
	$('#noBukuHargaEdit').val('');
	//console.log(idHarga);
	if (idHarga)
	{

		$.ajax({

			url  : 'action/harga/viewGaleryHarga.php',
			type : 'POST',
			data : {idHarga : idHarga},
			dataType : 'text',
			success:function(data)
			{
				$('.gmbrUpload').html(data);
				$('#idHarga').val(idHarga);
				$('#noBukuHargaEdit').val(noBuku);
			}

		});

	}

}


/*function pilihCustomer(a) {
	
	var customer = document.getElementById();
	alert('tes');
}
*/
//selalu angka
function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}
//huruf capital
function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
}
