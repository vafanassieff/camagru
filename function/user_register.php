<?php

include('./function/user.php');

$error = "";

function print_login_error($error){
	if ($error == "")
		return ;
	echo '<br\>';
	foreach ($error as $e){
		echo '<center>'.$e.'</center>';
	}
}

if ($_POST['submit'] == "OK")
	add_user_to_db($_POST['name'], $_POST['mail'], $_POST['pwd1'], $_POST['pwd2'], $error);
unset($_POST['submit']);