<?php
	$file_server_path = realpath(__FILE__);
	$php_filename = basename(__FILE__);
	$server_path = str_replace(basename(__FILE__), "", $file_server_path);

	if(is_file($server_path.'config.json') == true){
		$config = json_decode(file_get_contents($server_path.'config.json'), true);
	}
	$newtoki_url = $config['newtoki_url'];
	$manatoki_url = $config['manatoki_url'];
	$protoon_url = $config['protoon_url'];
	$toonkor_url = $config['toonkor_url'];
	$toonkormanga_url = $config['toonkormanga_url'];
	$funbe_url = $config['funbe_url'];
	$copytoon_url = $config['copytoon_url'];
	$toonsarang_url = $config['toonsarang_url'];
	$spowiki_url = $config['spowiki_url'];
	$manga_view = $config['manga_view'];
	$list_count = $config['list_count'];
	$try_count = $config['try_count'];
	$is_adult = $config['is_adult'];
?>