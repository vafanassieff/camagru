<?php
	include('user.php');
	session_start();

$error = array();
$id = 0;

if (isset($_POST['submit']) && empty($_POST['mail'])){

	if ($_POST['submit'] == "OK")
	{
		if (find_same_user($_POST['login']) == 0)
		{
			$error[] = 'Cant find the username';
			return ;
		}
		else if (is_account_activated($_POST['login']) == FALSE)
		{
			$error[] = 'Email is not verified, please check your email and your spam folder';
			return ;
		}
		else if (log_user($_POST['login'], $_POST['pwd'], $id) == TRUE)
		{
			$_SESSION['id'] = $id;
			$_SESSION['user'] = $_POST['login'];
			header('Location: ./index.php');
		}
		else
		{
			$_SESSION['user'] = "";
			$error[] = 'An error as occured, please contact the admin';
		}
	}
}
if (isset($_POST['mail']))
{
	send_mail_reset($_POST['mail']);
	header('Location: ./verify.php?verif=reset');
	}