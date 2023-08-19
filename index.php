<?php
require_once 'function/koneksi.php';

session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.

if (isset($_SESSION['id_userKUS']))
{
  header('location: dashboard.php');
}

$errors = array();

if ($_POST)
{

  $nama     = $koneksi->real_escape_string($_POST["nama"]);
  $password = $koneksi->real_escape_string($_POST["password"]);

  if (empty($nama) || empty($password))
  {
    if ($nama == "") 
    {
      $errors[] = "Nama belum diisi";
    }
    if ($password == "")
    {
      $errors[] = "Password belum diisi";
    }
  }
  else
  {
    $sql = "SELECT nama FROM user WHERE nama = '$nama'";
    $result = $koneksi->query($sql);
    if ($result->num_rows == 1) //jika ada nama
    {

      $password = md5($password);//enkripsi md5
      $cekLogin = "SELECT * FROM user WHERE nama='$nama' AND password='$password'";
      $resultLogin = $koneksi->query($cekLogin);

      if ($resultLogin->num_rows == 1)
      {
        $login = $resultLogin->fetch_assoc();
        $id_user = $login['id_user'];
        $nama = $login['nama'];
        $level = $login['level'];
        $aksi = $login['aksi'];

        //set session
        $_SESSION['id_userKUS'] = $id_user;
        $_SESSION['nama']    = $nama;
        $_SESSION['level']   = $level;
        $_SESSION['aksi']    = $aksi;

        header('location: dashboard.php');
      }
      else
      {
        $errors[] = "Username atau Password salah";
      }
    }
    else
    {
      $errors[] = "Username tidak ada";
    }
  }
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Login</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/font/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="assets/css/style.css" rel="stylesheet" />
   <link href="assets/css/custom.css" rel="stylesheet" />
   <link href="assets/css/style-responsive.css" rel="stylesheet" />
   <link href="assets/css/style-default.css" rel="stylesheet" id="style_color" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="lock">
    <div class="lock-header">
        <!-- BEGIN LOGO -->
        <a class="center" id="logo" href="index.html">
            <img class="center" alt="logo" src="img/logo.png">
        </a>
        <!-- END LOGO -->
    </div>
    <div class="login-wrap">
        <div class="metro single-size red">
            <div class="locked">
                <i class="icon-lock"></i>
                <span>Login</span>

            </div>
        </div>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="metro double-size green">
                <div class="input-append lock-input">
                    <input type="text" class="" id="nama" name="nama" placeholder="Username">

                </div>
        </div>
        <div class="metro double-size yellow">
                <div class="input-append lock-input">
                    <input type="password" class="" name="password" placeholder="Password">
                </div>
        </div>
        <div class="metro single-size terques login">
                <button type="submit" class="btn login-btn">
                    Login
                    <i class=" icon-long-arrow-right"></i>
                </button>
        </div>
    </form>
    </div>
        <div class="paddding-top">
      <?php 
      if ($errors) 
      {
        foreach ($errors as $key => $value) 
        {
          echo '
            <div class="alert alert-error batas-atas center">
                <strong>Error! </strong>'.$value.'
            </div>';
        }
      }

      ?>
    </div>
    <script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript">
      document.getElementById('nama').focus();
      //alert("tes");
    </script>
</body>
<!-- END BODY -->
</html>