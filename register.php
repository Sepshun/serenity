<?php
	session_start();
	require_once('connect.php');

	// Redirect if user is already logged in
	if (isset($_SESSION['username'])) {header('location: index.php');}

	// If form has been filled out
	if (isset($_POST) &&
		!empty($_POST['username']) &&
		!empty($_POST['email']) &&
		!empty($_POST['password'])) {

		// If first password is equal to the confirm password field
		if ($_POST['password'] === $_POST['password-conf']) {

			// Store the input
			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$password = md5($_POST['password']);

			// Create the user
			$create_user = "INSERT INTO `userlogin` (username, email, password, usertype) VALUES ('$username', '$email', '$password', 'user')";
			$user_res = mysqli_query($conn, $create_user);
			if ($user_res) {
				header('location: index.php');
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Serenity - Register</title>
		<!--     Stylesheets     -->
		<link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!--       Scripts       -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/script.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				// USERNAME
				var usernameInput = $('.input-box:nth-child(1)');
				$('input[name="username"]').keyup(function() {
					var length = $(this).val().length;

					// Check if username is available
					$.post('check-data.php', {type: 'username', input: $(this).val()}, function(res) {
						if (res || length <= 5) {
							usernameInput.addClass('error');
							usernameInput.removeClass('success');
						} else if (length >= 5 && !res) {
							usernameInput.addClass('success');
							usernameInput.removeClass('error');
						} else {
							usernameInput.removeClass('error');
							usernameInput.addClass('success');
						}
					});
				});

				// EMAIL
				var emailInput = $('.input-box:nth-child(2)');
				$('input[name="email"]').keyup(function() {
					var emailCorrect = $(this).val().indexOf('@') >= 0 ? true : false;
					$.post('check-data.php', {type: 'email', input: $(this).val()}, function(res) {
						if (res || !emailCorrect) {
							emailInput.addClass('error');
							emailInput.removeClass('success');
						} else if (emailCorrect && !res) {
							emailInput.addClass('success');
							emailInput.removeClass('error');
						} else {
							emailInput.removeClass('error');
							emailInput.addClass('success');
						}
					});
				});
			});
		</script>
	</head>
	<body>
		<div class="user-form-container">
			<p id="serenity-name">Serenity</p>
			<img src="http://wallpapercave.com/wp/RCchnAA.jpg">
			<div class="user-form">
				<p class="title">Please Register</p>
				<form method="POST" autocomplete="off">
					<div class="input-box">
						<input type="text" name="username" placeholder="Username" autofocus>
					</div>
					<div class="input-box">
						<input type="text" name="email" placeholder="Email Address">
					</div>
					<div class="input-box">
						<input type="password" name="password" placeholder="Password">
					</div>
					<div class="input-box">
						<input type="password" name="password-conf" placeholder="Password Again">
					</div>
					<button type="submit" class="disabled">Register</button>
					<a href="login.php">Login</a>
				</form>
			</div>
		</div>
	</body>
</html>