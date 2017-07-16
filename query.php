<?php
	require_once('connect.php');

	$type = $_POST['type'];
	$id = $_POST['id'];
	$display = $_POST['display'];
	$name = $_POST['name'];
	$sub = $_POST['sub'];
	$genres = $_POST['genres'];
	$facebook = $_POST['fb'];
	$soundcloud = $_POST['sc'];
	$youtube = $_POST['yt'];
	$instagram = $_POST['ig'];
	$twitter = $_POST['tw'];
	$webpage = $_POST['wp'];
	$flags = $_POST['flags'];

	if ($type === 'add') {
		$query = "INSERT INTO promoters (display,name,sub,genres,facebook,soundcloud,youtube,instagram,twitter,webpage,flags) VALUES ('$display','$name','$sub','$genres','$facebook','$soundcloud','$youtube','$instagram','$twitter','$webpage','$flags')";
	}
	if ($type === 'edit') {
		$query = "UPDATE promoters SET display='$display',name='$name',sub='$sub',genres='$genres',facebook='$facebook',soundcloud='$soundcloud',youtube='$youtube',instagram='$instagram',twitter='$twitter',webpage='$webpage',flags='$flags' WHERE id='$id'";
	}
	if ($type === 'remove') {
		$query = "DELETE FROM promoters WHERE id='$id'";
	}
	//echo $query;
	$res = mysqli_query($conn, $query);
?>