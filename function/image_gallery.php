<?php
	session_start();
	include('image.php');

function display_gallery(){

	$db = getBdd();

	$req = $db->prepare("SELECT * FROM `camagru`.`images` ORDER BY timestamp DESC");
	$req->execute();
	$result = $req->fetchAll();
	foreach ($result as $elem){
		$id = explode('/', $elem['path']);
		$id = $id[2];
		echo '<div class="responsive">';
		echo '<div class="gallery">';
		echo '<a href="./gallery.php?action=img&id='. $elem['name'] .'+'. $id .'">';
		echo '<img src="' . $elem['path'] . '">';
		echo '</a>';
		echo '<div class="desc">'. $_GET['action'] . $_GET['id'];
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}

function display_one_image($id){
	$id = explode(' ', $id);
	$path = './content/'. $id[1] . '/'. $id[0] . '.png';
	
	echo '<div class ="solo-img">';
	echo '<img src ="'.$path.'">';
	echo '</div>';
}
?>