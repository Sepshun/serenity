<?php
	// CONNECT
	$conn = mysqli_connect('localhost', 'root', '');
	if (!$conn) {
		die("Database connection failed: " . mysqli_error($conn));
	}

	// SELECT DATABASE
	$select_db = mysqli_select_db($conn, 'serenity');
	if (!$select_db) {
		die("Database selection failed: " . mysqli_error($conn));
	}
?>