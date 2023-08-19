<?php
// $password_string = "claim";
// $password_hash = "$2y$10$gBTmYKD8jjAfFbmdAvsbj.rRwnN2rrQ2ECZwf2zXuFHyKftk466OK";
// 	// $password_hash = password_hash($password_string, PASSWORD_BCRYPT);

// 	// echo $password_hash;
// 	if (password_verify($password_string, $password_hash)) {
// 		echo "Sussces Login";
// 	}else{
// 		echo "Gagal Loagin";
// 	}
// 	

  // $password_string = "abc123";
  // $password_hash = "$2y$10$aHhnT035EnQGbWAd8PfEROs7PJTHmr6rmzE2SvCQWOygSpGwX2rtW";

  // if (password_verify($password_string, $password_hash)) {
  // 	echo "string";
  //   // Correct password
  // } else {
  //   // Incorrect password
  // }
  
?>

<form method="POST" action="#" id="frm1">
Kota : <select name="idkota" onchange="choice1(this)">
        <option value="JK">Jakarta</option>
        <option value="BG">Bogor</option>
        <option value="BD">Bandung</option>
        <option value="SB">Surabaya</option>
        <option value="KB">Kebumen</option>
      </select>
<br/>
Kota Pilihan : <input type="text" name="kota" value="" size="20"/>
</form>

<script type="text/javascript">
// function pilihKota()
// {
//   var objfrm = document.getElementById("frm1");
//   var idx_opsi = objfrm.idkota.selectedIndex;
//   // alert('idx ' + idx_opsi );
//   // objfrm.kota.value= objfrm.idkota.options[idx_opsi].text;
//   if (idx_opsi == '0') {
//   	alert('Tes');
//   }
// }
// 
function choice1(select) {
     alert(select.options[select.selectedIndex].text);
}
</script>