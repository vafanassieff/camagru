<?php
	session_start();
	include('image.php');
	include('error_img.php');

	if (!isset($_SESSION['id']))
		header("Location: ./index.php");
	
	if (isset($_POST['img']))
	{
		if ($_POST['img'] == $default_img  || $_POST['img'] == $blank_img)
		{
			$_POST = array();
			header("Location: ./montage.php");
		}
		else
		{
			add_img($_POST['img'], $_POST['radio']);
			$_POST = array();
			header("Location: ./montage.php");
		}
	}

?>