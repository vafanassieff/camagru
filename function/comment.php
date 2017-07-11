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
	$id_temp = explode(" ", $id);
	$unique_id = $id_temp[0];
	$creator_id = $id_temp[1];

	$time = time();
	$unserialized_comment = get_comment($unique_id);

	$comment_array = unserialize($unserialized_comment);
	$new_com = ["comment" => $new_comment, "author" => $login, "timestamp" => $time, "user_id" => $user_id, ];
	$comment_array[] = $new_com;
	$serialized_comment = serialize($comment_array);

	$req = $db->prepare("UPDATE `camagru`.`images` SET `comment` = :comment WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->bindParam(':comment' , $serialized_comment);
	if ($req->execute())
	{
		count_comment($unique_id);
		if($creator_id != $user_id)
			mail_comment($new_comment , $creator_id , $login);
	}
}

function count_comment($unique_id){

	$db = getBdd();

	$req = $db->prepare("UPDATE `camagru`.`images` SET nb_comment = nb_comment + 1 WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();
}

function get_comment_count($unique_id){

	$db = getBdd();

	$req = $db->prepare("SELECT nb_comment FROM `camagru`.`images` WHERE name = :unique_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->execute();

	$test = $req->fetchAll();
	return ($test);
}

function mail_comment($new_comment, $user_id, $user_name){

	$db = getBdd();
	
	$req = $db->prepare("SELECT * FROM `camagru`.`users` WHERE id = :user_id");
	$req->bindParam(':user_id', $user_id);
	$req->execute();

	$result= $req->fetchAll();
	$mail = $result[0]['mail'];
	$name = $result[0]['name'];
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$to = $mail;
	$subject = "Your image has been commented !";
	$message = '<html><body>';
	$message .= 'Hello ' . $name .'<br>';
	$message .= 'A new comment ha been added on your <a href = "'. $actual_link .'">picture</a> by the user named ' . $user_name . '<br><br><br>';
	$message .= 'The comment is ' . $new_comment . '<br>';
	$message .= '</body></html>';
	$headers = "From: Camagru@camagru.com \r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
 	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	mail($to,$subject,$message,$headers);
}

function print_comment($id){

	$img_id = $id[0];
	$user_id = $id[1];

	$serialized_comment = get_comment($img_id);
	$unserialized_comment = unserialize($serialized_comment);

	foreach ($unserialized_comment as $comment)
	{
		$date = date('m/d/Y H:i', $comment['timestamp']);

		if ($comment['user_id'] == $user_id)
			echo '<li class="comment author-comment">';
		else
			echo '<li class="comment user-comment">';
        echo  '<div class="info">
                    <a>'.$comment['author'].'</a>
                    <span>'.$date.'</span>
                </div>
                <a class="avatar"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" /> </a>
                <p>'.$comment['comment'].'</p>
			</li>';
	}
}

function comment_form(){
	echo '<li class="write-new" id="comment">
                <form action="gallery.php?' . $_SERVER['QUERY_STRING'] .'#comment" method="POST">
                    <textarea name="comment" placeholder="Write your comment here" ></textarea>
                    <div>
                       <input type="submit" name="submit" value="OK"/>
                    </div>
                </form>
            </li>';
}

