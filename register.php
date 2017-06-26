<?php
include('header.php');
include('footer.php');
?>

<!DOCTYPE html>
<html>
	<body class="body-general">
		<div class="form-style-10">
		<h1>Sign Up Now!<span>Sign up ,take incredible pictures and share it with your friends !</span></h1>
			<form action="add_user.php" method="POST">
    			<div class="inner-wrap">
        			<label>Login name <input type="text" name="name" /></label>
    			</div>
    			<div class="inner-wrap">
        			<label>Email Address <input type="email" name="mail" /></label>
        		<div class="inner-wrap">
        			<label>Password <input type="password" name="pwd1" /></label>
        			<label>Confirm Password <input type="password" name="pwd2" /></label>
    			</div>
    			<div class="button-section">
     				<input type="submit" name="submit" value="OK"/>
     				<span class="privacy-policy">
						 <input type="checkbox" name="field7">You agree to our Terms and Policy.
     				</span>
    			</div>
			</form>
		</div>
	</body>
</html>