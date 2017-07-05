<?php
	session_start();
	include('image.php');

	function last_captured_img($user_id){

		$db = getBdd();

		$req = $db->prepare("SELECT * FROM `camagru`.`images` WHERE user_id = :id ORDER BY timestamp DESC LIMIT 4");
		$req->bindParam(':id', $user_id);
		$req->execute();
		$result = $req->fetchAll();
		foreach ($result as $elem){
			echo '<img class="last-image" src="'.$elem['path'].'"> ';
			echo '<br>';
		}
	}

	if (!isset($_SESSION['id']))
		header("Location: ./index.php");
	if (isset($_POST['img']))
 		base64_to_png($_POST['img']);
	unset($_POST['img']);
?>