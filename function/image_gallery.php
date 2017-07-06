<?php
	session_start();
	include('image.php');

function display_gallery(){

	$db = getBdd();

	$req = $db->prepare("SELECT * FROM `camagru`.`images` ORDER BY timestamp DESC");
	$req->execute();
	$result = $req->fetchAll();
	foreach ($result as $elem){
		echo '<div class="responsive">';
		echo '<div class="gallery">';
		echo '<a target="_blank" href="' . $elem['path'] . '">';
		echo '<img src="' . $elem['path'] . '">';
		echo '</a>';
		echo '<div class="desc">Truc</div>';
		echo ' </div>';
		echo ' </div>';
	}
}

?>