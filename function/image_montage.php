<?php
	session_start();

	if (!isset($_SESSION['id']))
		header("Location: ./index.php");

	include('image.php');
	include('error_img.php');

$error = array();

if (isset($_POST['submit']))
{
	if (isset($_POST['img']))
	{
		if ($_POST['img'] != $default_img && $_POST['img'] != $blank_img)
		{
			add_img_webcam($_POST['img'], $_POST['filter_alpha']);
			unset($_POST);
			return;
		}
	}
	else if (!empty($_FILES['upload']) && !empty($_POST['filter_alpha']))
	{
		add_img_upload($_FILES['upload'], $_POST['filter_alpha'], $error);
		unset($_POST);
		unset($_FILES);
	}
}
unset($_POST);
unset($_FILES);
?>