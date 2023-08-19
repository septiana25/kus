var tabelClaim;
$(document).ready(function() {
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

	var divRequest = $(".div-request").text();
	$('#activeClaim').addClass('active');

	tabelClaim = $('#tabelClaim').DataTable({
		'ajax' : 'action/claim/fetchClaim.php',
		'order':[]
	});// manage tabelClaim

	if (divRequest == 'tambahClaim') {
		$('#activeTamabahClaim').addClass('active');
	}

	$("#submitClaim").unbind('submit').bind('submit', function() {
		var tgl = $("#tgl").val();
		var no_claim = $("#no_claim").val();
		var daerah = $("#daerah").val();
		var dealer = $("#dealer").val();
		var toko = $("#toko").val();
		var id_brg = $("#id_brg").val();
		var pattern = $("#pattern").val();
		var dot = $("#dot").val();
		var tahun = $("#tahun").val();
		var keputusan = $("#keputusan").val();

		if (tgl == "") {

		}else{

		}

		if (no_claim == "") {
			$("#no_claim").after('<span class="error help-inline">No Claim Masih Kosong</span>');
			$("#no_claim").closest('#no_claim').addClass('error');
		}else{
			$("#no_claim").closest('.control-group').addClass('success');
			$("#no_claim").find('.error .help-inline').remove();
		}

		if (daerah == "") {
			$("#daerah").after('<span class="error help-inline">No Claim Masih Kosong</span>');
			$("#daerah").closest('#daerah').addClass('error');
		}else{
			$("#daerah").closest('.control-group').addClass('success');
			$("#daerah").find('.help-inline').remove();
		}

		if (dealer == "") {

		}else{

		}

		if (toko == "") {
			$("#toko").after('<span class="error help-inline">Toko Masih Kosong</span>');
			$("#toko").closest('#toko').addClass('error');
		}else{
			$("#toko").closest('#toko').addClass('success');
			$('.help-inline').remove();
		}

		if (id_brg == "") {
			$("#id_brg").after('<span class="help-inline">Ukuran Masih Kosong</span>');
			$("#id_brg").closest('.control-group').addClass('error');
		}else{
			$("#id_brg").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (pattern == "") {
			$("#pattern").after('<span class="error help-inline">Brand Masih Kosong</span>');
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
			$("#tahun").after('<span class="error help-inline">Serial Masih Kosong</span>');
			$("#tahun").closest('#tahun').addClass('error');
		}else{
			$("#tahun").closest('#tahun').addClass('success');
			$('.help-inline').remove();
		}

		if (keputusan == "") {
			$("#keputusan").after('<span class="help-inline">Keputusan Masih Kosong</span>');
			$("#keputusan").closest('.control-group').addClass('error');
		}else{
			$("#keputusan").closest('.control-group').addClass('success');
			$(".help-inline").remove();
		}

		if (tgl && no_claim && daerah && dealer && toko && id_brg && brand && dot && serial && keputusan) {
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
					$("#simpanClaim").button('reset');

					if (response.success == true) {
						//reset the form text
						$("#submitClaim")[0].reset();
						//
						$("#toko").trigger("chosen:updated");
						$("#brg").trigger("chosen:updated");
						$("#crown").trigger("chosen:updated");
						$("#sidewall").trigger("chosen:updated");
						$("#bead").trigger("chosen:updated");
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

//tambah kerusakan
function tambahRow(){
	$("#tambahRowBtn").button("loading");
	var tableLength = $("#kerusakanTable tbody tr").length;

	$("#tes").after('<input type="text" name="editIdKlr" id="editIdKlr" value="'+tableLength+'" />');
	var tableRow;
	var arrayNumber;
	var count;

	if (tableLength > 0) {
		tableRow = $("#kerusakanTable tbody tr:last").attr('id');
		arrayNumber = $("#kerusakanTable tbody tr:last").attr('class');
		count = tableRow.substring(3);	
		count = Number(count) + 1;
		arrayNumber = Number(arrayNumber) + 1;
	}else{
		// jika tidak ada row
		count = 1;
		arrayNumber = 0;
	}

	$.ajax({
		url: 'action/claim/fetchKerusakan.php',
		type: 'POST',
		dataType: 'json',
		success:function(response){
			$("#tambahRowBtn").button("reset");
			var tr = 
				'<tr id="row'+count+'" class="'+arrayNumber+'">'+
					'<td>'+
						'<div class="control-group">'+
							'<select style="width: 40%;" id="kerusakan" name="" class="chosen-select-no-results" data-placeholder="Pilih Kerusakan...">'+
								'<option></option>';
								$.each(response, function(index, value) {
									tr += '<option value="'+value[0]+'">'+value[1]+'</option>';
								});
							tr += '<option>a</option>'+
							'</select>'+
						'</div>'+
					'</td>'+
					'<td>'+
			            '<button onclick="hapusKerusakanRow('+count+')" class="btn btn-danger hapusKerusakanBtn" type="button" id="hapusKerusakanBtn"><i class="fa fa-trash"></i></button>'+
			        '</td>'+
				'<tr>';
				if (tableLength > 0) {
					$("#kerusakanTable tbody tr:last").after(tr);
				}else{
					$("#kerusakanTable tbody").append(tr);
				}	
		}
	});


}

//hapus kerusakan
function hapusKerusakanRow(row = null){
	if (row) {
		$("#row"+row).remove();
	}else{
		alert('Error! Lakukan refresh halaman')
	}
}
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