<?php
	session_start();
	include('image.php');
	include('comment.php');

	if (isset($_POST['submit']))
	{
		if (isset($_POST['comment']))
		{
			$unique_id = explode(" ", $_GET['id']);
			$unique_id = $unique_id[0];
			add_comment($_POST['comment'], $unique_id);
		}
	}
	if (isset($_GET['id']))
	{
		$id = explode(' ', $_GET['id']);
		$com = get_comment($id[0]);
		if ($com == NULL)
			header('Location: ./index.php');
	}
?>