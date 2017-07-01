<?php

include('user.php');

$error = array();
if (isset($_POST['submit'])){
	if ($_POST['submit'] == "OK")
		add_user_to_db($_POST['name'], $_POST['mail'], $_POST['pwd1'], $_POST['pwd2'], $error);
}