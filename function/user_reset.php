<?php

session_start();
include('user.php');

$error = array();

	if (isset($_POST['submit']))
	{
		if (find_mail_token($_SESSION['resetmail'], $_SESSION['resettoken']) == TRUE)
		{
			if (isset($_POST['resetpwd1']) && isset($_POST['resetpwd2']))
			{
				update_password($_POST['resetpwd1'], $_POST['resetpwd2'], $_SESSION['resetmail'], $error);
			}
		}
	}

	if (isset($_GET['mail']) && isset($_GET['token']))
	{
		$_SESSION['resetmail'] = $_GET['mail'];
		$_SESSION['resettoken'] = $_GET['token'];
	}

	if(!isset($_SESSION['resetmail']))
		header('Location: ./index.php');