<?php

function get_comment( $unique_id){

	$db = getBdd();

	$req = $db->prepare("SELECT comment FROM `camagru`.`images` WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();

	$comment = $req->fetchAll();
	return ($comment[0]['comment']);
	}

function add_comment($new_comment , $id){

	$db = getBdd(); 
	$login = $_SESSION['user'];
	$user_id = $_SESSION['id'];
	$time = time();
	$unserialized_comment = get_comment($id);

	$comment_array = unserialize($unserialized_comment);
	$new_com = ["comment" => $new_comment, "author" => $login, "timestamp" => $time, "user_id" => $user_id, ];
	$comment_array[] = $new_com;
	$serialized_comment = serialize($comment_array);

	$req = $db->prepare("UPDATE `camagru`.`images` SET `comment` = :comment WHERE name = :unique_id");
	$req->bindParam(':unique_id', $id);
	$req->bindParam(':comment' , $serialized_comment);
	$req->execute();
}

function print_comment($id){

	$serialized_comment = get_comment($id[0]);
	$unserialized_comment = unserialize($serialized_comment);
	foreach ($unserialized_comment as $comment){
		$date = date('m/d/Y H:i', $comment['timestamp']);
		if ($comment['user_id'] == $id[1])
			echo '<li class="comment author-comment">';
		else
			echo '<li class="comment user-comment">';
        echo  '<div class="info">
                    <a>'.$comment['author'].'</a>
                    <span>'.$date.'</span>
                </div>
                <a class="avatar"><img src="" /> </a>
                <p>'.$comment['comment'].'</p>
			</li>';
	}
}

function comment_form(){
	echo '<li class="write-new">
                <form action="gallery.php?' . $_SERVER['QUERY_STRING'] .'" method="POST">
                    <textarea name="comment" placeholder="Write your comment here" ></textarea>
                    <div>
                       <input type="submit" name="submit" value="OK"/>
                    </div>
                </form>
            </li>';
}

