<?php
	session_start();
	include('image.php');
	include('comment.php');

if (isset($_GET['action']))
{
	if ($_GET['action'] == "img")
	{
		if (isset($_GET['id']))
		{
			if (find_image($_GET['id']) == FALSE)
			header('Location: ./index.php');
		}
	}
	if ($_GET['action'] == "like")
	{
		if (isset($_GET['id']))
		{
			if (find_image($_GET['id']))
				add_like($_GET['id']);
			else
				header('Location: ./index.php');
		}
	}
}

	if (isset($_POST['submit']))
	{
		if (isset($_POST['comment']) && ctype_space($_POST['comment']) == FALSE)
			add_comment($_POST['comment'], $_GET['id']);
	}
?>