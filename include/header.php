<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
  $bulan          = date("m");
  $tahun          = date("Y");
  //$bulan          = 7;

  //aray bulan
  $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
  $query = $koneksi->query("SELECT id_saldo FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun");
  if ($query->num_rows >=1 ) {
    $count = 0;
    $button= '<a class="btn btn-primary disabled"> Disabled</a>';
    $notif = '
     <li>
         <a>
             <span class="label label-info"><i class="icon-bullhorn"></i></span>
             Bulan '.$BulanIndo[(int)$bulan-1].' Peremajaan Selesai
             <!-- <span class="small italic">10 mins</span> -->
         </a>
     </li>
    ';
  }else{
    $count = 1;
    $notif = '                 
   <li>
       <a href="saldo.php">
           <span class="label label-warning"><i class="icon-bell"></i></span>
           Bulan '.$BulanIndo[(int)$bulan-1].' Belum Peremajaan
           <!-- <span class="small italic">1 Hours</span> -->
       </a>
   </li>';
  }

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Aplikasi Inventori Gudang KUS</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <!-- <link href="assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" /> -->
   <link href="assets/font/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="assets/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" />
   <link href="assets/css/style.css" rel="stylesheet" />
   <link href="assets/css/style-responsive.css" rel="stylesheet" />
   <link href="assets/css/style-default.css" rel="stylesheet" id="style_color" />
   <link href="assets/css/custom.css" rel="stylesheet" />
   <link href="assets/pace/pace-flash.css" rel="stylesheet" />
   <link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
   <link rel="stylesheet" href="assets/chosen/chosen.css">
   <link rel="stylesheet" href="assets/gritter/css/jquery.gritter.css">
   <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
   <link href="assets/datepicker/css/datepicker.css" rel="stylesheet" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
 
   <!-- BEGIN HEADER -->
   <div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <div class="navbar-inner">
           <div class="container-fluid">
               <!--BEGIN SIDEBAR TOGGLE-->
               <div class="sidebar-toggle-box hidden-phone">
                   <div class="icon-reorder tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
               </div>
               <!--END SIDEBAR TOGGLE-->
               <!-- BEGIN LOGO -->
               <a class="brand" href="dashboard.php">
                   <!-- <img src="img/logo.png" alt="Metro Lab" /> -->
                   Aplikasi IG KUS
               </a>
               <!-- END LOGO -->
               <!-- BEGIN RESPONSIVE MENU TOGGLER -->
               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="arrow"></span>
               </a>
               <!-- END RESPONSIVE MENU TOGGLER -->
               <div id="top_menu" class="nav notify-row">
                   <!-- BEGIN NOTIFICATION -->
                   <ul class="nav top-menu">
                       <!-- BEGIN SETTINGS -->
                       
                       <!-- END SETTINGS -->
                       <!-- BEGIN INBOX DROPDOWN -->
                       
                       <!-- END INBOX DROPDOWN -->
                       <!-- BEGIN NOTIFICATION DROPDOWN -->
                       <li class="dropdown" id="header_notification_bar">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                               <i class="icon-bell-alt"></i>
                               <span class="badge badge-warning"><?php echo $count; ?></span>
                           </a>
                           <ul class="dropdown-menu extended notification">
                               <li>
                                   <p><?php echo $count; ?> Pemberitahuna</p>
                               </li>
                           <?php
                              echo $notif;
                           ?>
                               <!-- <li>
                                   <a href="#">See all notifications</a>
                               </li> -->
                           </ul>
                       </li>
                       <!-- END NOTIFICATION DROPDOWN -->

                   </ul>
               </div>
               
               <!-- END  NOTIFICATION -->
               <div class="top-nav ">
                   <ul class="nav pull-right top-menu" >
                       <!-- BEGIN SUPPORT -->
                       <!-- <li class="dropdown mtop5">
                       
                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Chat">
                               <i class="icon-comments-alt"></i>
                           </a>
                       </li>
                       <li class="dropdown mtop5">
                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Help">
                               <i class="icon-headphones"></i>
                           </a>
                       </li> -->
                       <!-- END SUPPORT -->
                       <!-- BEGIN USER LOGIN DROPDOWN -->
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <img src="img/avatar-mini.png" alt="">
                               <span class="username capitalize"><?php echo $_SESSION['nama'] ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                              <!--  <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
                              <li><a href="#"><i class="icon-cog"></i> My Settings</a></li> -->
                               <li><a href="logout.php"><i class="icon-key"></i> Log Out</a></li>
                           </ul>
                       </li>
                       <!-- END USER LOGIN DROPDOWN -->
                   </ul>
                   <!-- END TOP NAVIGATION MENU -->
               </div>
           </div>
       </div>
       <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">