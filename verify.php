<?php
include('header.php');
include('footer.php');
include('./function/user_verify.php');

?>

<!DOCTYPE html>
<html>
	<body class="body-general" background="./asset/img/register-background.jpg">
		<div class="form-style-10">
				<?php print_verif($state);?>
    			</div>
			</form>
		</div>
	</body>
</html>