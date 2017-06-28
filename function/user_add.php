<?php

include('./user.php');

if ($_POST['submit'] != "OK")
{
	header('Location: ../index.php');
}
else
	unset($_POST['submit']);

add_user_to_db($_POST['name'], $_POST['mail'], $_POST['pwd1'], $_POST['pwd2']);