<?php 
	if (isset($_GET['action']))
	{
		if ($_GET['action'] == "img" && isset($_GET['id']))
			display_one_image($_GET['id']);
	}
	else if (isset($_GET['page']))
			display_gallery($_GET['page']);
?>
	<div class="clearfix"></div>