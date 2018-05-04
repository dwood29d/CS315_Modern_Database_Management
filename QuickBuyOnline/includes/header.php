<html lang="en">
<head>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
	<link href="css/theme.css" rel="stylesheet" /> 
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <title><?php echo $page_title; ?></title>
</head>

<body role="document">
    
    <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!--<a class="navbar-brand" href="#"><?php echo $user_name ?></a>-->
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
			<li><a href="password.php">Change Password</a></li>
			<li><a href="cart.php">Cart</a></li>
			<li><a href="checkout.php">Check out</a></li>
			<li><?php // Create a login/logout link: 
				if ( (isset($_SESSION['user_id'])) && (basename($_SERVER['PHP_SELF']) != 'logout.php') ) {
					echo '<a href="logout.php">Logout</a>';
					$is_admin = (int)$_SESSION['administrator'];
					if ($is_admin == 1){
						echo '<li><a href="view_users.php">View Users</a></li>';
					}
				} else {
					echo '<li><a href="login.php">Login</a></li>';
					echo '<li><a href="register.php">Register</a></li>';
				}
				?>
			</li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
    
    <div class="container theme-showcase" role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
          <!-- Start of the page-specific content. -->
<!-- Script 8.1 - header.html -->
