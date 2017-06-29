<?php

require('./config/mysql.php');

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
	$body = 'Hello '.$name.' Your Activation Code is '.$token.'\n Please Click On This link  \n http://localhost:8080/camagru/verify.php?id='.$id.'&code='.$token.' \n to activate your account.';
	
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
		$error[] = "Wrong mail fornat";
	if (password_strengh($password))
		$error[] = "Password is too weak, must be at least 8 char long and have letter and one number";
	if (check_username($name))
		$error[] = "Bad Username";
	if ($error == "")
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