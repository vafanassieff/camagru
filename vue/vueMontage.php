<script language="JavaScript" type="text/javascript" src="./js/webcam.js"></script>
<div class="outer">
    <div class="montage">
		<div class="picture">
			<div class="camera">
				<div id="filter" class ="filter">
					<img class="filterimg" id="filterimg" src="">
				</div>
				<div class ="photo-result">
					<img id="photo" class ="photo" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==">
				</div>
         		<video id="video" class="video">Video stream not available.</video>
         	 	<button id="startbutton">Take photo</button> 
        	</div>
       		<canvas id="canvas-result"></canvas>
		</div>
		<div class="picture-action">
		<button id="resetbutton" onclick="clearphotobis();">Reset Photo</button> 
		<form action="./montage.php" method="POST" enctype="multipart/form-data">
			<input type="hidden"name="img" id="imgb64"/>
			<input type="radio" name="filter_alpha" value="illuminati" id="illuminati" onclick="filter_img(this.id);">Illuminati
			<input type="radio" name="filter_alpha" value="dog" id="dog"  onclick="filter_img(this.id);">Dog
			<input type="radio" name="filter_alpha" value="hearth" id="hearth"  onclick="filter_img(this.id);">Hearth
			<br>
			<input type="radio" name="filter_process" value="grayscale" id="grayscale" onclick="filter(this.id);">Grayscale
			<input type="radio" name="filter_process" value="sepia" id="sepia"  onclick="filter(this.id);">Sepia
			<input type="radio" name="filter_process" value="negative" id="negative"  onclick="filter(this.id);">Negative
			<br>
			<input type="file" name="upload"/>
			<br>
			<input type="submit" name="submit" value="Submit" onclick="testimg();"/>
		</form>
	</div>
	<center><?php print_error($error);?></center>
	</div>
  	<div class="side">
    	<h1>Previous Picture</h1>
		<?last_captured_img($_SESSION['id']);?>	
	</div>
</div>