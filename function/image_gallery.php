<?php
	session_start();
	include('image.php');
	include('comment.php');
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
		echo '<div class="desc">toto';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}

function display_one_image($id){
	$id = explode(' ', $id);
	$path = './content/'. $id[1] . '/'. $id[0] . '.png';
	$actual_link = $_SERVER[HTTP_HOST] .$_SERVER[REQUEST_URI];

	echo '<div class="responsive-large">';
	echo '<div class="gallery-large">';
	echo '<img src="' . $path . '">';
	echo '<div class="desc">';
	echo '<div class="social-media">';
		social_media($actual_link);
	echo '</div>';
	$comment[0] = "la vie est belle";
	$comment[1] = "toto est beau";
	$stock = serialize($comment);
	print($stock);
	$res = unserialize($stock);
	print($res[0]);
	if (isset($_SESSION['user']))
		comment_form();
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<div class="clear"></div>';
}
?>