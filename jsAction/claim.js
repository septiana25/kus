var tabelClaim;
$(document).ready(function() {

	var divRequest = $(".div-request").text();
	$('#activeClaim').addClass('active');

    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, tidak ditemukan!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

	//
	$('.datepicker').datepicker();

	 $('#toko').typeahead({
	  source: function(toko, result)
	  {
	   $.ajax({
	    url:"fetchAutoCompleteToko.php",
	    method:"POST",
	    data:{toko:toko},
	    dataType:"json",
	    success:function(data)
	    {
	     result($.map(data, function(item){
	      return item;
	     }));
	    }
	   })
	  }
	 });

	 $('#sales').typeahead({
	  source: function(sales, result)
	  {
	   $.ajax({
	    url:"fetchAutoCompleteSales.php",
	    method:"POST",
	    data:{sales:sales},
	    dataType:"json",
	    success:function(data)
	    {
	     result($.map(data, function(item){
	      return item;
	     }));
	    }
	   })
	  }
	 });

	 $('#pattern').typeahead({
	  source: function(pattern, result)
	  {
	   $.ajax({
	    url:"fetchAutoCompletePattern.php",
	    method:"POST",
	    data:{pattern:pattern},
	    dataType:"json",
	    success:function(data)
	    {
	     result($.map(data, function(item){
	      return item;
	     }));
	    }
	   })
	  }
	 });

	 $('#dot').typeahead({
	  source: function(dot, result)
	  {
	   $.ajax({
	    url:"fetchAutoCompleteDot.php",
	    method:"POST",
	    data:{dot:dot},
	    dataType:"json",
	    success:function(data)
	    {
	     result($.map(data, function(item){
	      return item;
	     }));
	    }
	   })
	  }
	 });

	 $('#kerusakan').typeahead({
	  source: function(kerusakan, result)
	  {
	   $.ajax({
	    url:"fetchAutoCompleteKerusakan.php",
	    method:"POST",
	    data:{kerusakan:kerusakan},
	    dataType:"json",
	    success:function(data)
	    {
	     result($.map(data, function(item){
	      return item;
	     }));
	    }
	   })
	  }
	 });

	tabelClaim = $('#tabelClaim').DataTable({
		'ajax' : 'action/claim/fetchClaim.php',
		'order':[]
	});// manage tabelClaim

	if (divRequest == 'tambahClaim') {
		$('#activeTamabahClaim').addClass('active');
	}

	$("#submitClaim").unbind('submit').bind('submit', function() {
		var tgl       = $("#tgl").val();
		var no_claim  = $("#no_claim").val();
		var pengaduan = $("#pengaduan").val();
		var daerah    = $("#daerah").val();
		var dealer    = $("#dealer").val();
		var toko      = $("#toko").val();
		var sales     = $("#sales").val();
		var brg       = $("#brg").val();
		var pattern   = $("#pattern").val();
		var dot       = $("#dot").val();
		var tahun     = $("#tahun").val();
		var kerusakan = $("#kerusakan").val();
		var tread     = $("#tread").val();

		if (tgl == "") {

		}else{

		}

		if (pengaduan == "") {
			$("#pengaduan").after('<span class="error help-inline">No Pengaduan Masih Kosong</span>');
			$("#pengaduan").closest('#pengaduan').addClass('error');
		}else{
			$("#pengaduan").closest('#pengaduan').addClass('success');
			$("#pengaduan").find('.help-inline').remove();
		}

		if (no_claim == "") {
			$("#no_claim").after('<span class="error help-inline">No Claim Masih Kosong</span>');
			$("#no_claim").closest('#no_claim').addClass('error');
		}else{
			$("#no_claim").closest('#no_claim').addClass('success');
			$('.help-inline').remove();
		}

		if (daerah == "") {
			$("#daerah").after('<span class="error help-inline">Daerah Masih Kosong</span>');
			$("#daerah").closest('#daerah').addClass('error');
		}else{
			$("#daerah").closest('.control-group').addClass('success');
			$("#daerah").find('.help-inline').remove();
		}

		if (dealer == "") {

		}else{

		}

		if (toko == "") {
			$("#toko").after('<span class="help-inline error">Nama Toko Masih Kosong</span>');
			$("#toko").closest('#toko').addClass('error');
		}else{
			$("#toko").closest('#toko').addClass('success');
			$(".help-inline").remove();
		}

		if (sales == "") {
			$("#sales").after('<span class="help-inline error">Nama Sales Masih Kosong</span>');
			$("#sales").closest('#sales').addClass('error');
		}else{
			$("#sales").closest('#sales').addClass('success');
			$(".help-inline").remove();
		}

		if (brg == "") {
			$("#brg").after('<span class="help-inline">Ukuran Masih Kosong</span>');
			$("#brg").closest('.control-group').addClass('error');
		}else{
			$("#brg").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (pattern == "") {
			$("#pattern").after('<span class="error help-inline">Pattern Masih Kosong</span>');
			$("#pattern").closest('#pattern').addClass('error');
		}else{
			$("#pattern").closest('#pattern').addClass('success');
			$('.help-inline').remove();
		}

		if (dot == "") {
			$("#dot").after('<span class="error help-inline">Dot Masih Kosong</span>');
			$("#dot").closest('#dot').addClass('error');
		}else{
			$("#dot").closest('#dot').addClass('success');
			$('.help-inline').remove();
		}

		if (tahun == "") {
			$("#tahun").after('<span class="error help-inline">Tahun Produksi Masih Kosong</span>');
			$("#tahun").closest('#tahun').addClass('error');
		}else{
			$("#tahun").closest('#tahun').addClass('success');
			$('.help-inline').remove();
		}

		if (kerusakan == "") {
			$("#kerusakan").after('<span class="error help-inline">Kerusakan Masih Kosong</span>');
			$("#kerusakan").closest('#kerusakan').addClass('error');
		}else{
			$("#kerusakan").closest('#kerusakan').addClass('success');
			$('.help-inline').remove();
		}

		if (tread == "") {
			$("#tread").after('<span class="error help-inline">Tread Dept Masih Kosong</span>');
			$("#tread").closest('#tread').addClass('error');
		}else{
			$("#tread").closest('#tread').addClass('success');
			$('.help-inline').remove();
		}

/*		if (tread == "") {
			$("#tread").after('<span class="help-inline">Tread Dept Masih Kosong</span>');
			$("#tread").closest('.control-group').addClass('error');
		}else{
			$("#tread").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}*/

		// if (keputusan == "") {
		// 	$("#keputusan").after('<span class="help-inline">Keputusan Masih Kosong</span>');
		// 	$("#keputusan").closest('.control-group').addClass('error');
		// }else{
		// 	$("#keputusan").closest('.control-group').addClass('success');
		// 	$(".help-inline").remove();
		// }

		// if (nominal == "") {
		// 	$("#nominal").after('<span class="error help-inline">Nominal Masih Kosong</span>');
		// 	$("#nominal").closest('#nominal').addClass('error');
		// }else{
		// 	$("#nominal").closest('#nominal').addClass('success');
		// 	$('.help-inline').remove();
		// }

		if (tgl && pengaduan && daerah && dealer && toko && sales && brg && pattern && dot && tahun && kerusakan && tread) {
			//ambil data form
			var form = $(this);
			//button loading
			$("#simpanClaim").button('loading');

			$.ajax({
				url : form.attr('action'),
				type: form.attr('method'),
				data: form.serialize(),
				dataType: 'json',
				success:function(response) {
					$("#simpanClaim").button('reset');//button reset ketika success

					if (response.success == true) {
						//reset the form text
						$("#submitClaim")[0].reset();
						//
						$("#toko").trigger("chosen:updated");
						$("#brg").trigger("chosen:updated");
						$("#keputusan").trigger("chosen:updated");
						//remove the error text
						$(".help-inline").remove();

						setTimeout(function(){
						   window.location.reload(1);
						}, 5000);

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
						
					}if (response.success == false){
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
				        
				         setTimeout(function(){

					         $.gritter.remove(unique_id, {
					         fade: true,
					         speed: 'slow'
					         });

				         }, 6000)
					}

				}
			});
		}
		return false;
	});

});


//selalu angka
function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}

//ajax combobox
function getId(val)
{
	//alert(val);
	$.ajax({
		type : "POST",
		url  : "action/claim/actionNoFaktur.php",
		data : "id_klr="+val,
		success: function(data){
			$("#id_brg").html(data);
		}
	});
}

function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
}

/*function choice1(select) {
   var keputusan = select.options[select.selectedIndex].text;
   // alert(keputusan);
   if (keputusan == 'Ganti') {
   	$("#nominal").removeClass('hidden');
   }else{
   	$("#nominal").addClass('hidden');
   }
}*/