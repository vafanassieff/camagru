<?php

require('./config/mysql.php');

function resize_img($filepath){

	$old_img = imagecreatefrompng($filepath);
	$size_old = getimagesize($filepath);
	$new_h = 900;

	$resize = (($new_h * 100) / $size_old[1]);
	$new_w = (($size_old[0] * $resize)/ 100);

	$new_img = imagecreatetruecolor($new_w, $new_h);
	imagecopyresampled($new_img, $old_img,0 ,0 ,0 ,0 ,$new_w, $new_h, $size_old[0], $size_old[1]);

	imagedestroy($old_img);

	imagepng($new_img, $filepath);

}

function add_img_upload($file, $filter_type, &$errors){

		$check == TRUE;
		$file_name = $file['name'];
      	$file_size = $file['size'];
      	$file_tmp  = $file['tmp_name'];
      	$file_type = $file['type'];
		$file_ext  =   strtolower(end(explode('.',$file_name)));
		$unique_id = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 );
		$user_id = $_SESSION['id'];
		$filter['name'] = $filter_type;
		$expensions = array("jpeg","jpg","png");
		$type = array("image/png", "image/jpeg");

		if(in_array($file_type ,$type) === FALSE)
        	$errors[]="Type not allowed, please choose a PNG file.";

		if(in_array($file_ext , $expensions) === FALSE)
        	$errors[]="Type not allowed, please choose a PNG file.";

		if($file_size > 2097152)
         	$errors[]='File size must be excately 2 MB';

		if (empty($file_tmp) == FALSE && getimagesize($file_tmp) == FALSE)
			$errors[]='This is not an image';

		if(empty($errors) == TRUE)
		{
			$filepath = "./content/". $user_id . '/' . $unique_id . '.' . $file_ext;
			move_uploaded_file($file_tmp, $filepath);
			add_filter($filepath, $filter, $file_type, $check);
			resize_img($filepath);
			add_img_to_db($filepath, $unique_id, $user_id);
		}
}

function add_img_webcam($data, $filter_type){

	$type = "image/png";

	$user_id = $_SESSION['id'];
	$unique_id = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 );
	$filepath = "./content/" . $user_id . "/" .$unique_id . ".png";

	$filter = array();
	$filter['name'] = $filter_type;

	base64_to_png($data, $filepath);
	add_filter($filepath, $filter, $type);
	add_img_to_db($filepath, $unique_id, $user_id);
}

function add_filter($filepath, $filter, $type){

	if ($type == "image/png")
		$img_original = imagecreatefrompng($filepath);
	if ($type == "image/jpeg")
		$img_original = imagecreatefromjpeg($filepath);

		$img_filter = imagecreatefrompng('./asset/filter/'. $filter['name'] .'.png');
		imagealphablending($img_original, true);
		imagesavealpha($img_original, true);
		$width = imagesx($img_filter);
   		$height = imagesy($img_filter);
		imagecopy($img_original, $img_filter, 0, 0, 0, 0, $width, $height);
		imagepng($img_original, $filepath);
		imagedestroy($img_original);
		imagedestroy($img_filter);
}

function base64_to_png($data, $filepath){

	list($type, $data) = explode(';', $data);
	list(, $data)      = explode(',', $data);
	$data = base64_decode($data);

	file_put_contents($filepath, $data);
}

function add_img_to_db($filepath, $unique_id, $user_id){

	$db = getBdd();

	$req = $db->prepare("INSERT INTO `camagru`.`images` (`user_id`, `name`, `path`, `comment`, `nb_comment` , `like_array` ,`nb_like`)
								VALUES (:user_id, :name, :path, :comment, :nb_comment, :like_array, :nb_like)");
	$req->execute(array(
		':user_id' => $user_id,
		':name' => $unique_id,
		':path' => $filepath,
		':comment' => "a:0:{}",
		':nb_comment' => 0,
		':like_array' => "a:0:{}",
		':nb_like' => 0));
}

function delete_image($id){

	$db = getBdd();

	$id_temp = explode(" ", $id);
	$unique_id = $id_temp[0];
	$user_id = $id_temp[1];

	if ($user_id == $_SESSION['id'])
	{
		$req = $db->prepare("DELETE FROM `camagru`.`images` WHERE name = :unique_id AND user_id = :user_id");
		$req->bindParam(':unique_id', $unique_id);
		$req->bindParam(':user_id', $user_id);
		$req->execute();
		header('Location: ./gallery.php');
	}
	else
		header('Location: ./gallery.php');
}

function last_captured_img($user_id){

	$db = getBdd();

	$req = $db->prepare("SELECT * FROM `camagru`.`images` WHERE user_id = :id ORDER BY timestamp DESC LIMIT 4");
	$req->bindParam(':id', $user_id);
	$req->execute();
	$result = $req->fetchAll();
	foreach ($result as $elem){
		
		$link = './gallery.php?action=img&id='. $elem['name'] .'+'. $user_id;

		echo '';
		echo '<a href="'. $link . '"><img class="last-image" src="' . $elem['path'] .'"></a> ';
		echo '<br>';
	}
}

function display_gallery($page){

	$db = getBdd();

	$offset = $page * 11;
	$nextpage = $page + 1;
	$previouspage = $page - 1;

	$req = $db->prepare("SELECT * FROM `camagru`.`images` ORDER BY timestamp DESC LIMIT 12 OFFSET :offset");
	$req->bindValue('offset', $offset, PDO::PARAM_INT);
	$req->execute();
	$result = $req->fetchAll();
	
	echo '<div class="page">';
	if ($previouspage >= 0)
		echo '<a href="./gallery.php?page='. $previouspage .'"><i class="fa fa-arrow-left"></i></a>';
	if ($req->rowCount() == 12 )
		echo '&nbsp; <a href="./gallery.php?page='. $nextpage .'"><i class="fa fa-arrow-right"></i></a>';
	echo '</div>';

	foreach ($result as $elem){
		$id = explode('/', $elem['path']);
		$user_id = $id[2];
		$nb_com = get_comment_count($elem['name']);
		$link = './gallery.php?action=img&id='. $elem['name'] .'+'. $user_id;
		$link_like = './gallery.php?action=like&id='. $elem['name'] .'+'. $user_id;
		$nb_like = get_number_like($elem['name']);
		$link_delete = './gallery.php?action=del&id='. $elem['name'] .'+'. $user_id;

		echo '<div class="responsive">
				<div class="gallery">
					<a href="'. $link . '"><img src="' . $elem['path'] . '"></a>
						<div class="desc">';
	if(isset($_SESSION['user']))
	{
		echo '<a href="'. $link . '#comment"><i class="fa fa-comments"></i></a> '. $nb_com[0]['nb_comment'] .'&nbsp;'; 
		echo '<a href="'. $link_like .'"><i class="fa fa-heart" aria-hidden="true"></i></a> ' . $nb_like;
		if ($_SESSION['id'] == $user_id)
			echo '&nbsp; <a href="'. $link_delete .'"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
	}
	else
		echo '<i class="fa fa-comments"></i> '. $nb_com[0]['nb_comment'] .' <i class="fa fa-heart" aria-hidden="true"></i> ' . $nb_like;

		echo '	</div>
			</div>
		</div>
		';
	}
}

function display_one_image($id){

	$id = explode(' ', $id);
	$path = './content/'. $id[1] . '/'. $id[0] . '.png';
	$actual_link = $_SERVER[HTTP_HOST] .$_SERVER[REQUEST_URI];

	echo '<div class="responsive-large">
			<div class="gallery-large">
				<img src="' . $path . '">
					<center>';
				social_media($actual_link);
	echo '</center>
			<ul class="comment-section">';
				print_comment($id);
	if(isset($_SESSION['user']))
				comment_form();
	echo '</ul>
		</div>
		</div>
		<div class="clear"></div>';
}

function find_image($id){

	$db = getBdd();
	$id = explode(" ", $id);
	$unique_id = $id[0];
	$user_id = $id[1];

	$req = $db->prepare("SELECT id FROM `camagru`.`images` WHERE name = :unique_id AND user_id = :user_id");
	$req->bindParam(':unique_id', $unique_id);
	$req->bindParam(':user_id', $user_id);
	$req->execute();
	
	if ($req->rowCount() == 1)
		return (TRUE);
	else
		return (FALSE);
}

function social_media($link){

	echo '<!-- Sharingbutton Facebook -->
<a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u=http%3A%2F%2F'. $link . '" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Twitter -->
<a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text=Super%20picture%20taken%20with%20Camagru%20!.%20No%20JavaScript.%20No%20tracking.&amp;url=http%3A%2F%2F'. $link . '" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Google+ -->
<a class="resp-sharing-button__link" href="https://plus.google.com/share?url=http%3A%2F%2F'. $link . '" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--google resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.37 12.93c-.73-.52-1.4-1.27-1.4-1.5 0-.43.03-.63.98-1.37 1.23-.97 1.9-2.23 1.9-3.57 0-1.22-.36-2.3-1-3.05h.5c.1 0 .2-.04.28-.1l1.36-.98c.16-.12.23-.34.17-.54-.07-.2-.25-.33-.46-.33H7.6c-.66 0-1.34.12-2 .35-2.23.76-3.78 2.66-3.78 4.6 0 2.76 2.13 4.85 5 4.9-.07.23-.1.45-.1.66 0 .43.1.83.33 1.22h-.08c-2.72 0-5.17 1.34-6.1 3.32-.25.52-.37 1.04-.37 1.56 0 .5.13.98.38 1.44.6 1.04 1.84 1.86 3.55 2.28.87.23 1.82.34 2.8.34.88 0 1.7-.1 2.5-.34 2.4-.7 3.97-2.48 3.97-4.54 0-1.97-.63-3.15-2.33-4.35zm-7.7 4.5c0-1.42 1.8-2.68 3.9-2.68h.05c.45 0 .9.07 1.3.2l.42.28c.96.66 1.6 1.1 1.77 1.8.05.16.07.33.07.5 0 1.8-1.33 2.7-3.96 2.7-1.98 0-3.54-1.23-3.54-2.8zM5.54 3.9c.33-.38.75-.58 1.23-.58h.05c1.35.05 2.64 1.55 2.88 3.35.14 1.02-.08 1.97-.6 2.55-.32.37-.74.56-1.23.56h-.03c-1.32-.04-2.63-1.6-2.87-3.4-.13-1 .08-1.92.58-2.5zM23.5 9.5h-3v-3h-2v3h-3v2h3v3h2v-3h3"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton E-Mail -->
<a class="resp-sharing-button__link" href="mailto:?subject=Super%20picture%20taken%20with%20Camagru%20!.%20No%20JavaScript.%20No%20tracking.&amp;body=http%3A%2F%2F'. $link . '" target="_self" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg>
    </div>
  </div>
</a>

';
}