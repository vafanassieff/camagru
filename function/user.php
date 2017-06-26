<?php

require('config/mysql.php');

function create_token(){
	$length = 64;
	$token = bin2hex(random_bytes($length));
	return ($token);
}

function find_same_user($name){
	return(0);
}

function check_mail($mail){
	return (0);
}

function password_strengh($password){
	return(0);
}

function same_password($pwd, $pwd2){
	return(0);
}

function add_user_to_db($name, $mail, $password, $password2){

	global $db;

	if (same_password($password, $password2))
	{
		header("Location:register.php?href=inscription&error=dif_pwd");
	}
	if (find_same_user($name))
	{
		header("Location:register.php?href=inscription&error=name_use");
	}
	else if (check_mail($mail))
	{
		header("Location:register.php?href=inscription&error=bad_mail");
	}

	else if (password_strengh($password))
	{
		header("Location:register.php?href=inscription&error=pwd_str");
	}
	else 
	$token = create_token();
	$req = $db->prepare("INSERT INTO `camagru`.`users` (`name`, `mail`, `password`, `token_verif`)
							VALUES (:name, :mail, :pass, :token)");
	$req->execute(array(
		':name' => $name,
		':mail' => $mail,
		':pass' => hash(whirlpool, $password),
		':token' => $token));
}

function log_user($login, $pwd){

	global $db;

	$req = $db->prepare("SELECT * FROM `users` WHERE name = 'toto'");
	$req->execute(array(
		':name' => $name,
		':mail' => $mail,
		':pass' => hash(whirlpool, $password),
		':token' => $token));
}