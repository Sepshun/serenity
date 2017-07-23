<?php
	include('simple_html_dom.php'); // Include DOM tools

	$url = 'https://www.youtube.com/user/AmaranteMusic';
	$html = file_get_html($url);    // Get HTML

	// Echo links
	foreach ($html->find('.channel-links-item a') as $link) {
		echo '<a href="'.$link->href.'">'.$link->href.'</a><br>';
	}
?>