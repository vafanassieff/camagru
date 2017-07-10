<div class="outer">
    <div class="montage">
		<div class="picture">
			<div class="camera">
				<div id="filter" class ="filter">
					<img id="filterimg" src="">
				</div>
         		<video id="video"class="video">Video stream not available.</video>
         	 	<button id="startbutton">Take photo</button> 
        	</div>
       		<canvas id="canvas-result"></canvas>
    		<div class="output">
      			 <img id="photo" alt="The screen capture will appear in this box.">
    		</div>
		</div>
		<div class="picture-action">
		<form action="./montage.php" method="POST" enctype="multipart/form-data">
			<input type="hidden"name="img" id="imgb64"/>
			<input type="radio" name="radio" value="illuminati" id="illuminati" onclick="filter_img(this.id);">Illuminati
			<input type="radio" name="radio" value="dog" id="dog"  onclick="filter_img(this.id);">Dog
			<input type="radio" name="radio" value="hearth" id="hearth"  onclick="filter_img(this.id);">Hearth
			<br>
			<input type="radio" name="radio" value="grayscale" id="grayscale" onclick="filter(this.id);">Grayscale
			<input type="radio" name="radio" value="sepia" id="sepia"  onclick="filter(this.id);">Sepia
			<input type="radio" name="radio" value="negative" id="negative"  onclick="filter(this.id);">Negative
			<br>
			<input type="file" name="upload"/>
			<br>
			<input type="submit" name="submit" value="Submit" onclick="testimg();"/>
		</form>
		<!--<?php print_r($error);?>-->
	</div>
	</div>
  	<div class="side">
    	<h1>Previous Picture</h1>
		<?last_captured_img($_SESSION['id']);?>	
	</div>
</div>