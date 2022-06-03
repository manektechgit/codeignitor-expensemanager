<?php 
$loggedin_user_info = get_value_from_session_cookie(USER_INFO);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php echo SITE_NAME; ?></title>

	<!-- Bootstrap core CSS -->
	<link href="<?php echo base_url("css/bootstrap.min.css"); ?>" rel="stylesheet">

	<!-- font awesome -->
	<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">-->
	<link href="<?php echo base_url("plugins/fontawesome/css/all.min.css"); ?>" rel="stylesheet">

	<!-- sweet alert 2 -->
	<link href="<?php echo base_url("css/sweetalert2.min.css"); ?>" rel="stylesheet">
	
	<!-- datepicker -->
	<link href="<?php echo base_url("css/datepicker.css"); ?>" rel="stylesheet" />

	<!-- Custom styles for this template -->
	<link href="<?php echo base_url("css/simple-sidebar.css"); ?>" rel="stylesheet">
	<link href="<?php echo base_url("css/custom.css"); ?>" rel="stylesheet">

</head>

<body
data-base_url="<?php echo base_url(); ?>"
>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">
      	<?php //echo SITE_NAME; ?>
      	<div class="text-center text-primary">
      		<!-- <img src="<?php //echo base_url("img/logo.png"); ?>" class="img-fluid logo_menu_top" /> -->
          <?php echo SITE_NAME; ?>
      	</div>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?php echo base_url(); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="dashboard")?"list-group-item-active":""; ?>">Dashboard</a>
        <?php /* ?>
        <?php if(is_admin()){ ?>
        	<a href="<?php echo base_url("profile/accountmanager"); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="accountmanager")?"list-group-item-active":""; ?>">Account Manager</a>
        <?php } ?>
        
        <a href="<?php echo base_url("customer"); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="customer")?"list-group-item-active":""; ?>">Customers</a><?php */ ?>

        <a href="<?php echo base_url("income"); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="income")?"list-group-item-active":""; ?>">Income</a>

        <a href="<?php echo base_url("expense"); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="expense")?"list-group-item-active":""; ?>">Expense</a>

        <a href="<?php echo base_url("category"); ?>" class="list-group-item list-group-item-action bg-light <?php echo ($page=="category")?"list-group-item-active":""; ?>">Categories</a>

        <a class="list-group-item list-group-item-action bg-light"></a>

      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-default" id="menu-toggle">Menu</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $loggedin_user_info[SESS_FIRST_NAME]; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo base_url("user/profile"); ?>">My Profile</a>
                <a class="dropdown-item" href="<?php echo base_url("user/changepassword"); ?>">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url("user/logout"); ?>">Log out</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>