<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../lib/config.php');
	include('../lib/simple_html_dom.php');

	for($i=43;$i < 100;$i++){
		$base_url = "https://spowiki".$i.".com/";
		$get_html_contents = file_get_html($base_url);
		if ( strlen($get_html_contents) > 0 ) {
			foreach($get_html_contents->find('meta') as $e){
				if($e->property == "og:url"){
					$newurl = str_replace("http://","https://",$e->content);
					break;
				}
			}
			break;
		}
	}

	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
		$config['spowiki_url'] = $newurl;
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

