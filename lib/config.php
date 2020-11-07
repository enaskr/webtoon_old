<?php
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT+9");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Connection: close");

	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	$req_uri = urldecode($_SERVER['REQUEST_URI']);
	$php_name = basename($_SERVER["PHP_SELF"]);
	$req_query = urldecode(getenv("QUERY_STRING"));
	$this_url = $php_name."?".$req_query;

	$thisTime = date("Y.m.d H:i:s", time()); 
	$thisDate = date("Ymd", time()); 
?>