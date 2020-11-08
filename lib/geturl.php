<?php
	include('../lib/config.php');
	include('../lib/simple_html_dom.php');

	$target_url = $_GET["target_url"];

	$get_html_contents = file_get_html($target_url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = file_get_html($target_url);
		}
	}
	echo $get_html_contents;
?>
