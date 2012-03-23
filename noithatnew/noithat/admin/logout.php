<?php

	@session_start();
	unset($_SESSION['admin_User']);
	@session_destroy();	
	header('Location:'.'login.php');

?>