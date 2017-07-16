<?php
	session_start();
	session_destroy();
	header('location: login.php');
	setcookie('user', '', time() + (86400 * 30), "/");
?>