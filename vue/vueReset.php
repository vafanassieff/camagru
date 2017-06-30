<!DOCTYPE html>
<html>
	<body class="body-general" background="./asset/img/login-background.jpg">
		<div class="form-style-10">
		<h1>Password Reset<span>Please enter your new password</span></h1>
			<form action="reset.php" method="POST">
        		<div class="inner-wrap">
        			<label>Password <input type="password" name="resetpwd1" /></label>
    			</div>
				<div class="inner-wrap">
        			<label>Password <input type="password" name="resetpwd2" /></label>
    			</div>
    			<div class="button-section">
     				<center>
						 	<input type="submit" name="submit" value="OK"/>
					</center>
				<div class="button-section">
    			</div>
			</form>
		
			<?php print_error($error);?>
		</div>
	</body>
</html>