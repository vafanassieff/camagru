<?php

require('./config/mysql.php');

function base64_to_png($data){

	$db = getBdd();
	$id = $_SESSION['id'];
	$login = $_SESSION['user'];

	list($type, $data) = explode(';', $data);
	list(, $data)      = explode(',', $data);
	$data = base64_decode($data);
	
	$unique_id = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 );
	$filepath = "./content/" . $id . "/" .$unique_id . ".png";

	file_put_contents($filepath, $data);

	$req = $db->prepare("INSERT INTO `camagru`.`images` (`user_id`, `name`, `comment`, `path`)
								VALUES (:user_id, :name, :comment, :path)");
	$req->execute(array(
		':user_id' => $id,
		':name' => $login,
		':comment' => "",
		':path' => $filepath));
}