<?php

$background = "./asset/img/login-background.jpg";

require('./function/user_login.php');
include('./vue/header.php');

if (isset($_GET['submit']))
{
	if ($_GET['submit'] == 'Reset Password')
		include('./vue/vueResetpwd.php');
}
else
	include('./vue/vueLogin.php');

include('./vue/footer.php');
?>
