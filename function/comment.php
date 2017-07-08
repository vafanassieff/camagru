<?php

function get_comment( $unique_id){

	$db = getBdd();

	$req = $db->prepare("SELECT comment FROM `camagru`.`images` WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();

	$comment = $req->fetchAll();
	return ($comment[0]['comment']);
	}

function add_comment($new_comment , $unique_id){

	$db = getBdd();
	$login = $_SESSION['user'];
	$comment = get_comment($unique_id);

	unserialize($comment);
	$comment = (array)$comment;
	$comment[] = $new_comment;
	$comment = serialize($comment);

	$req = $db->prepare("UPDATE `camagru`.`images` SET `comment` = :comment WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->bindParam(':comment' , $comment);
	$req->execute();
}

function print_comment($id){

	$comment = get_comment($id);
	$print = unserialize($comment);
	$print2 = unserialize($print[0]);
	print_r($print2);
}

function comment_form(){

	echo '<div class="form-style-10">';
	echo '<textarea name="comment" form="commentform" rows="4" cols="50">Enter text here...</textarea>';
	echo '		<form action="gallery.php?' . $_SERVER['QUERY_STRING'] .'" method="POST" id="commentform">
    			<div class="button-section">
     				<center><input type="submit" name="submit" value="OK"/></center>
				<div class="button-section">
    			</div>
			</div>';
}

