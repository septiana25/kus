let tabel;
$(document).ready(function() {
	$(".choiceChosen").chosen();
	//mengambil data div class div-request
	const divRequest = $(".div-request").text();
	// active manu barang
	$('#activeMaster').addClass('active');
	
	if (divRequest == 'barcodebrg') {
		// active submenu barang masuk
		$('#activeBarcodeBrg').addClass('active');

	}
	



});// /document


function upperCaseF(a){
  setTimeout(function(){
      a.value = a.value.toUpperCase();
  }, 1);
}

function validAngka(a)
{
  if(!/^[0-9.]+$/.test(a.value))
  {
  a.value = a.value.substring(0,a.value.length-1000);
  }
}


//pesan error ajax
$(document).ajaxError(function(){
	alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
});