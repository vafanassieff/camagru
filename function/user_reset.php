<?php

include('user.php');

function find_mail_token($mail, $token){

	$db = getBdd();

	$req = $db->prepare("SELECT * FROM `camagru`.`users` WHERE mail = :mail AND token_verif = :token");
	$req->bindParam(':mail', $mail);
	$req->bindParam(':token', $token);
	$req->execute();

	if($req->rowCount() == 1)
		return (TRUE);
	else
		return (FALSE);
}

function update_password($password, $password2, $mail,  &$error){

	if (same_password($password, $password2))
		$error[] = "Password are not the same";
	if (password_strengh($password))
		$error[] = "Password is too weak, must be at least 8 char long and have one letter and one number";
	if ($error == "")
	{
		$db = getBdd();
		$verified = '42';

		$req = $db->prepare("UPDATE `camagru`.`users` SET token_verif = :token WHERE mail = :mail");
		$req->bindParam(':token', $verified);
		$req->bindParam(':mail', $mail);
		$req->execute();
		header('Location: ./verify.php?pass=changed');
	}
}

$error = "";

	if ($_GET['mail'])
		$_POST['resetmail'] = $_GET['mail'];
	if($_GET['token'])
		$_POST['resettoken'] = $_GET['token'];

	if (find_mail_token($_POST['resetmail'], $_POST['resettoken']) == TRUE || $_POST['submit'] == 'OK')
	{
		if ($_POST['pwd1'] != "" && $_POST['pwd2'] != "" && $_POST['resetmail'] != "")
		{
			update_password($_POST['pwd1'], $_POST['pwd2'], $_POST['resetmail'], $error);
			$_POST['submit'] = "";
		}
	}
	else
		header('Location: ./index.php');
?>