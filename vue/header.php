<?php
	if(!isset($_SESSION)) 
		session_start();
date_default_timezone_set('CET');
?>
<html>
<head>
  <meta charset="utf-8">
 		<link rel="icon" href=""/>
  		<link rel="stylesheet" type="text/css" href="css/style.css">
  		<link rel="stylesheet" type="text/css" href="css/form.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/social-media.css">
  		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
  <title>Camagru</title>
</head>
<header class="header-login-signup">
	<div class="header-limiter">
		<h1><a href="./">Camagru</span></a></h1>
		<nav>
			<a href="./">Home</a>
			<a href="./gallery.php" class="selected">Gallery</a>
			<?php if(isset($_SESSION['user']))
				echo '<a href="./montage.php" class="selected">Picture</a>';
?>
		</nav>
		<ul>
			<?php 
			if(isset($_SESSION['user']))
			{
	echo '<li><a href="logout.php">Logout</a></li>';
	echo '<li><a href="#">'.$_SESSION['user'].'</a></li>';
}
else
			{
	echo '<li><a href="login.php">Login</a></li>';
	echo '<li><a href="register.php">Sign up</a></li>';
}
?>
		</ul>
	</div>
</header>