<?php
	require_once('connect.php');
	$input = $_POST["input"];

	$count = "";
	// USERNAME
	if ($_POST['type'] === 'username') {
		$count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `userlogin` WHERE username='$input'"));
	}
	// EMAIL
	if ($_POST['type'] === 'email') {
		$count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `userlogin` WHERE email='$input'"));
	}
	// PROMOTER
	if ($_POST['type'] === 'promo') {
		$count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `promoters` WHERE name='$input'"));
	}

	echo $count ? true : false;
?>