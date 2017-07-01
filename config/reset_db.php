<?php
include("mysql.php");
include("database.php");

	$db = getBdd();
	$db->query('DROP DATABASE `camagru`');
?>