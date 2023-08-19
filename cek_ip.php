<?php
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $ip=$_SERVER['REMOTE_ADDR'];
    }
 $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
?>
<?php echo  "Nama Komputer=".$hostname;?><br />
<?php echo  "IP Address=".$ip;?>
