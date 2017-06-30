<?php
include('user.php');

function print_verif($state){
	if ($state == 1)
		echo '<center><span>Your account has been successfully activated please <a href = "./login.php">login<a> to continue !</span></center>';
	else if ($state == 2)
		echo '<center><span>An email has been sent to your account, please check your mail to make your account verified !</span></center>';
	else if ($state == 3)
		echo '<center><span>An email with the instruction for reseting your password has been sent.</span></center>';
	else
		echo '<center><span>Your account has already been activated please <a href = "./login.php">login<a> to continue !</span></center>';
}

	if($_GET['verif'] == 'mail')
		$state = 2;
	if($_GET['verif'] == 'reset')
		$state = 3;
	else if (isset($_GET['id']) && isset($_GET['token']))
		$state = verify_user($_GET['id'], $_GET['token']);