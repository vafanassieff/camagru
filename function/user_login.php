<?php
	require('../config/mysql.php');
	session_start();
	function log_user($login, $password){

	global $db;

	$pwd = hash('whirlpool', $password);
	$verified = '42';

	$req = $db->prepare("SELECT * FROM `camagru`.`users` WHERE name = :name AND token_verif = :token AND password = :password");
	$req->bindParam(':name', $login);
	$req->bindParam(':token', $verified);
	$req->bindParam(':password', $pwd);
	$req->execute();

	if($req->rowCount() == 1)
	{
		return (TRUE); 
	}
	else
		return (FALSE);
	}

	if ($_POST['submit'] != "OK")
	{
		header('Location: ./index.php');
	}

	if (log_user($_POST['login'], $_POST['pwd']) == TRUE)
	{
		$_SESSION['user'] = $_POST['login'];
		header('Location: ../index.php');
	}
	else
	{
		$_SESSION['user'] = "";
		header('Location: ../login.php?fail=ultime');
	}