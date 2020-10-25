<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../lib/config.php');
	include('../lib/simple_html_dom.php');

	$base_url = $copytoon_url;

	$target = "https://jusoshow.me";

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	foreach($get_html_contents->find('li') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a') as $g){
			if ( $g->title == "카피툰" ) $newurl = $g->href;
			break;
		}
	}
	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
		$config['copytoon_url'] = $newurl;
		file_put_contents($server_path.'config.json', json_encode($config,JSON_UNESCAPED_UNICODE));
?>
	<script type="text/javascript">
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	}
?>
</body>
</html>
