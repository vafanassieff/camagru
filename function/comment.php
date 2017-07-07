<?php

function comment_form(){

	echo '<div class="form-style-10">';
	//echo '<textarea name="comment" form="commentform" rows="4" cols="50">Enter text here...</textarea>';
	echo '		<form action="gallery.php" method="POST" id="commentform">
    			<div class="button-section">
     				<center><input type="submit" name="submit" value="OK"/></center>
				<div class="button-section">
    			</div>
			</div>';
}