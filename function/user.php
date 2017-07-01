<?php

require('./config/mysql.php');

function print_error($error){
	if ($error == "")
		return ;
	echo '<br\>';
	foreach ($error as $e){
		echo '<center>'.$e.'</center>';
	}
}

function create_token(){
	$length = 64;
	$token = bin2hex(random_bytes($length));
	return ($token);
}

function find_same_user($name){

	$db = getBdd();

	$req = $db->prepare("SELECT `name` FROM `camagru`.`users` WHERE name = :name");
	$req->bindParam(':name', $name);
	$req->execute();

	if($req->rowCount() > 0)
       return(1);
	else 
        return(0);
}

function find_same_mail($mail){

	$db = getBdd();

	$req = $db->prepare("SELECT `mail` FROM `camagru`.`users` WHERE mail = :mail");
	$req->bindParam(':mail', $mail);
	$req->execute();

	if($req->rowCount() > 0)
       return(1);
	else 
        return(0);
}

function check_username($name){

	if(ctype_alnum($name))
		return(0);
	else
		return(1);
}

function check_mail($mail){
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
		return (1);
	else
		return(0);
}

function password_strengh($password){
	if (strlen($password) < 8)
       return(1);
	else if (!preg_match("#[0-9]+#", $password))
       return(1);
    else if (!preg_match("#[a-zA-Z]+#", $password))
        return(1);  
	else
		return(0);
}

function same_password($pwd, $pwd2){
	if ($pwd === $pwd2)
		return(0);
	else
		return (1);
}

function send_email_token($mail, $token, $name){

	$db = getBdd();

	$req = $db->prepare("SELECT `id` FROM `camagru`.`users` WHERE name = :name");
	$req->bindParam(':name', $name);
	$req->execute();

	$db_id = $req->fetch(PDO::FETCH_ASSOC);
	$id = $db_id['id'];
	$to = $mail;
	$subject = "Activation of your Camagru Account";
	$message = '<html><body>';
	$message .= 'Hello '.$name.' <br>';
	$message .= 'Please Click On This <a href = "http://localhost:8080/camagru/verify.php?id='.$id.'&token='.$token.'">link</a> to activate your account.';
	$message .= '</body></html>';
	$headers = "From: Camagru@camagru.com \r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
 	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	mail($to,$subject,$message,$headers);
}

function add_user_to_db($name, $mail, $password, $password2, &$error){

	$db = getBdd();

	if (same_password($password, $password2))
		$error[] = "Password are not the same";
	if (find_same_user($name))
		$error[] = "User already exist";
	if (find_same_mail($mail))
		$error[] = "Email already used";
	if (check_mail($mail))
		$error[] = "Wrong mail format";
	if (password_strengh($password))
		$error[] = "Password is too weak, must be at least 8 char long and have one letter and one number";
	if (check_username($name))
		$error[] = "Bad Username";
	if (empty($error))
	{
		$token = create_token();
		$req = $db->prepare("INSERT INTO `camagru`.`users` (`name`, `mail`, `password`, `token_verif`)
								VALUES (:name, :mail, :pass, :token)");
		$req->execute(array(
			':name' => $name,
			':mail' => $mail,
			':pass' => hash('whirlpool', $password),
			':token' => $token));
		send_email_token($mail, $token, $name);
		header('Location: ./verify.php?verif=mail');
	}
}

function log_user($login, $password){

	$db = getBdd();

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

function is_account_activated($login){

	$db = getBdd();
	$verified = '42';

	$req = $db->prepare("SELECT * FROM `camagru`.`users` WHERE name = :name AND token_verif = :token");
	$req->bindParam(':name', $login);
	$req->bindParam(':token', $verified);
	$req->execute();

	if($req->rowCount() == 1)
		return (TRUE); 
	else
		return (FALSE);
}

function verify_user($id, $token) {

	$db = getBdd();

	$req = $db->prepare("SELECT * FROM `camagru`.`users` WHERE id = :id AND token_verif = :token");
	$req->bindParam(':id', $id);
	$req->bindParam(':token', $token);
	$req->execute();

	if($req->rowCount() == 1)
	{
		$verified = '42';
		$req2 = $db->prepare("UPDATE `camagru`.`users` SET token_verif = :token WHERE id = :id");
		$req2->bindParam(':token', $verified);
		$req2->bindParam(':id', $id);
		$req2->execute();
		return (1);
	}
	return (4);
}

function send_mail_reset($mail){

	$token = create_token();

	$db = getBdd();

	$req = $db->prepare("UPDATE `camagru`.`users` SET token_verif = :token WHERE mail = :mail");
	$req->bindParam(':mail', $mail);
	$req->bindParam(':token', $token);
	$req->execute();

	$to = $mail;
	$subject = "Camagru Password Recovery";
	$message = '<html><body>';
	$message .= 'Hello <br>';
	$message .= 'Please Click On This <a href = "http://localhost:8080/camagru/reset.php?mail='.$mail.'&token='.$token.'">link</a> to reset your Camagru password';
	$message .= '</body></html>';
	$headers = "From: Camagru@camagru.com \r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
 	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	mail($to,$subject,$message,$headers);
}

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
	if (empty($error))
	{
		$db = getBdd();
		$verified = '42';
		$pwd = hash('whirlpool', $password);

		$req = $db->prepare("UPDATE `camagru`.`users` SET token_verif = :token WHERE mail = :mail");
		$req->bindParam(':token', $verified);
		$req->bindParam(':mail', $mail);
		$req->execute();

		$req2 = $db->prepare("UPDATE `camagru`.`users` SET password = :password WHERE mail = :mail");
		$req2->bindParam(':password', $pwd);
		$req2->bindParam(':mail', $mail);
		$req2->execute();
		header('Location: ./index.php?pass=changed');
		session_destroy();
	}
}