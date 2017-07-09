<?php
	session_start();

	if (!isset($_SESSION['id']))
		header("Location: ./index.php");

	include('image.php');
	include('error_img.php');

$error = array();

if (isset($_POST['submit']))
{
	if (isset($_FILES['upload']) && isset($_POST['radio']))
	{
		add_img_upload($_FILES['upload'], $_POST['radio'], $error);
		$_POST = array();
		header("Location: ./montage.php");
	}
	if (isset($_POST['img']))
	{
		if ($_POST['img'] == $default_img  || $_POST['img'] == $blank_img)
		{
			$_POST = array();
			header("Location: ./montage.php");
		}

		else
		{
			add_img_webcam($_POST['img'], $_POST['radio']);
			$_POST = array();
			header("Location: ./montage.php");
		}
	}

}
?>