<?php

function get_number_like($unique_id){

	$db = getBdd();

	$req = $db->prepare("SELECT nb_like FROM `camagru`.`images` WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();

	$result = $req->fetchAll();

	return($result[0]['nb_like']);
}

function get_like_array($unique_id){

	$db = getBdd();

	$req = $db->prepare("SELECT like_array FROM `camagru`.`images` WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();

	$tmp = $req->fetchAll();
	return ($tmp[0]['like_array']);
}

function add_like($id){

	$db = getBdd();
	$login = $_SESSION['user'];

	$id_temp = explode(" ", $id);
	$unique_id = $id_temp[0];
	$like_serialized = get_like_array($unique_id);
	$like_array = unserialize($like_serialized);

	if (in_array($login, $like_array) == TRUE)
		remove_like($login, $like_array);
	else
		$like_array[] = $login;
	$like_serialized = serialize($like_array);

	$req = $db->prepare("UPDATE `camagru`.`images` SET `like_array` = :like_array WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->bindParam(':like_array' , $like_serialized);
	$req->execute();
	update_nb_like($like_array, $unique_id);
	header('Location: ./gallery.php');
}

function remove_like($login, &$like_array){

	$index = array_search($login, $like_array);
	if($index !== FALSE)
        unset($like_array[$index]);

}

function update_nb_like($like_array, $unique_id){

	$db = getBdd();

	$nb_like = count($like_array);

	$req = $db->prepare("UPDATE `camagru`.`images` SET `nb_like` = :nb_like WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->bindParam(':nb_like' , $nb_like);
	$req->execute();
}