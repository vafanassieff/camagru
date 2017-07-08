
		<div class="form-style-10">
		<h1>Sign Up Now !<span>Sign up ,take incredible pictures and share it with your friends !</span></h1>
			<form action="register.php" method="POST" autocomplete="off">
	
    			<div class="inner-wrap">
        			<label>Login name <input type="text" name="name" /></label>
    			</div>
    			<div class="inner-wrap">
        			<label>Email Address <input type="email" name="mail" /></label>
				</div>
        		<div class="inner-wrap">
        			<label>Password <input type="password" name="pwd1" /></label>
        			<label>Confirm Password <input type="password" name="pwd2" /></label>
    			</div>
    			<div class="button-section">
     				<center><input type="submit" name="submit" value="OK"/></center>

				</div>
			</form>
			<?php if (empty(!$error))
				{print_error($error);}?>
		</div>
		