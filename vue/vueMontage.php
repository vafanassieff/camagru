	<script src="./js/webcam.js">
	</script>
		<div class="main">
      <div class="montage">
        <div class="camera">
          <video id="video">Video stream not available.</video>
          <button id="startbutton">Take photo</button> 
        </div>
          <canvas id="canvas">
          </canvas>
     	 <div class="output">
      		 <img id="photo" alt="The screen capture will appear in this box.">
    	</div>
		<form action="./montage.php" method="POST">
			<input type="hidden" name="img" id="imgb64"/>
			<input type="submit" value="Submit" onclick="testimg();"/>
		</form>
			<br>
				<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>	<br>
	</div>
  	<div class="side">
    	<h1>Previous Picture</h1>
		<?last_captured_img($_SESSION['id']);?>	
	</div>
</div>
