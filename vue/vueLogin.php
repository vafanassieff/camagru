<!DOCTYPE html>
<html>
	<body class="body-general" background="./asset/img/login-background.jpg">
		<div class="form-style-10">
		<h1>Sign In<span>Please enter your credidential</span></h1>
			<form action="./function/user_login.php" method="POST">
    			<div class="inner-wrap">
        			<label>Login name or email adress<input type="text" name="login" /></label>
    			</div>
        		<div class="inner-wrap">
        			<label>Password <input type="password" name="pwd" /></label>
    			</div>
    			<div class="button-section">
     				<center>
						 	<input type="submit" name="submit" value="OK"/>
					</center>
				<div class="button-section">
    			</div>
			</form>
			</form>
				<form METHOD="LINK" action="./login.php?submit=reset">
				 <br/>
				<center><input type="submit" name="submit" value="Reset Password"/></center>
			</form>
		</div>
	</body>
</html>