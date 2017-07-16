<?php
	session_start();
	require_once('connect.php');

	if(isset($_POST) && !empty($_POST)) {
		// Store the input
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = md5($_POST['password']);

		// Get users and check if there are any matches
		$get_user = "SELECT * FROM `userlogin` WHERE username='$username' AND password='$password'";
		$get_user_res = mysqli_num_rows(mysqli_query($conn, $get_user));
		if ($get_user_res == 1) {
			// Set session and cookie, then redirect to index
			$_SESSION['username'] = $username;
			setcookie('user', $username, time() + (86400 * 30), '');
			header('location: index.php');
		}
	}
	// If session already exists, redirect
	if (isset($_SESSION['username'])) {header('location: index.php');}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Serenity - Login</title>
		<!--     Stylesheets     -->
		<link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!--       Scripts       --><script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/script.js"></script>
	</head>
	<body>
		<div class="user-form-container">
			<p id="serenity-name">Serenity</p>
			<img src="http://wallpapercave.com/wp/RCchnAA.jpg">
			<div class="user-form">
				<p class="title">Please Login</p>
				<form method="POST" autocomplete="off">
					<div class="input-box">
						<input type="text" name="username" placeholder="Username">
					</div>
					<div class="input-box">
						<input type="password" name="password" placeholder="Password">
					</div>
					<button type="submit" class="disabled">Login</button>
					<a href="register.php">Register</a>
				</form>
			</div>
		</div>
	</body>
</html>