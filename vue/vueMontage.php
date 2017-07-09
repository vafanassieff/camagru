<script src="./js/webcam.js"></script>
<div class="outer">
    <div class="montage">
		<div class="picture">
			<div class="camera">
         		<video id="video">Video stream not available.</video>
         	 	<button id="startbutton">Take photo</button> 
        	</div>
       		<canvas id="canvas-result"></canvas>
    		<div class="output">
      			 <img id="photo" alt="The screen capture will appear in this box.">
    		</div>
		</div>
		<div class="picture-action">
		<form action="./montage.php" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="img" id="imgb64"/>
			<input type="radio" name="radio" value="illuminati">Illuminati
			<input type="radio" name="radio" value="dog">Dog
			<input type="radio" name="radio" value="coeur">Hearth
			<br>
			<input type="file" name="upload"/>
			<br>
			<input type="submit" name="submit" value="Submit" onclick="testimg();"/>
		</form>
	</div>
	</div>
  	<div class="side">
    	<h1>Previous Picture</h1>
		<?last_captured_img($_SESSION['id']);?>	
	</div>
</div>