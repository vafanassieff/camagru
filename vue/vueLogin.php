	<div class="form-style-10">
		<h1>Sign In<span>Please enter your credidential</span></h1>
			<form action="login.php" method="POST">
    			<div class="inner-wrap">
        			<label>Login<input type="text" name="login" /></label>
    			</div>
        		<div class="inner-wrap">
        			<label>Password <input type="password" name="pwd" /></label>
    			</div>
    			<div class="button-section">
     				<center>
						 	<input type="submit" name="submit" value="OK"/>
					</center>
				</div>
			</form>
				<form METHOD="LINK" action="./login.php?submit=reset">
				 <br>
				<center><input type="submit" name="submit" value="Reset Password"/></center>
			</form>
			<?php if (empty(!$error)){print_error($error);}?>
</div>
