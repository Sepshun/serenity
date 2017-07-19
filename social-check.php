<?php
	include('simple_html_dom.php'); // Include DOM tools

	$url = $_POST['url'];           // Get URL
	$html = file_get_html($url);    // Get HTML

	// Echo links
	foreach ($html->find('.channel-links-item a') as $link) {
		echo '<a href="'.$link->href.'">'.$link->href.'</a><br>';
	}
?>