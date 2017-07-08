	<?php 
		if (isset($_GET['action']))
		{
			if ($_GET['action'] == "img" && isset($_GET['id']))
				display_one_image($_GET['id']);
		}
		else
			display_gallery();
			?>
	<div class="clearfix"></div>