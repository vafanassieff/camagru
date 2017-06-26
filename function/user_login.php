<?php
	include('user.php');

if ($_POST['submit'] != "OK")
{
	header('Location: ./index.php');
}
else
	unset($_POST['submit']);

log_user($_POST['login'], $_POST['pwd']);
unset($_POST['login']);
unset($_POST['pwd']);
header('Location: ./index.php');