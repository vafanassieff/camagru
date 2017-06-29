<?php
	require('./config/mysql.php');

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
		$success = 1;
		return ($success);
	}

}

function print_verif($state){
	if ($state == 1)
		echo '<center><span>Your account has been successfully activated please <a href = "./login.php">login<a> to continue !</span></center>';
	else if ($state == 2)
		echo '<center><span>An email has been sent to your account, please check your mail to make your account verified !</span></center>';
	else
		echo '<center><span>Your account has already been activated please <a href = "./login.php">login<a> to continue !</span></center>';
}
	if($_GET['verif'] == 'mail')
	{
		$state = 2;
	}
	else if (isset($_GET['id']) && isset($_GET['token']))
	{
		$state = verify_user($_GET['id'], $_GET['token']);
	}