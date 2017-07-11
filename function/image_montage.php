<?php
	session_start();

	if (!isset($_SESSION['id']))
		header("Location: ./index.php");

	include('image.php');

$smallest_pixel = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
$error = array();

if (isset($_POST['submit']))
{
	if ($_POST['img'] != $smallest_pixel)
	{
		add_img_webcam($_POST['img'], $_POST['filter_alpha'], $_POST['filter_process']);
		unset($_POST);
		return;
	}
	else if ($_FILES['upload'] != $error && (!empty($_POST['filter_alpha']) || $_POST['filter_process']))
	{
		add_img_upload($_FILES['upload'], $_POST['filter_alpha'], $_POST['filter_process'], $error);
		unset($_POST);
		unset($_FILES);
	}
	else 
		$error[] = "Upload or take a picture ! And choose at least one filter";
}
unset($_POST);
unset($_FILES);
?>