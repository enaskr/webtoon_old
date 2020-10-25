<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../lib/config.php');
	include('../lib/simple_html_dom.php');

	$target = $newtoki_url."notice/7754";

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<div itemprop="description" class="view-content">',$get_html_contents);
		$strpos2 = explode('<span style="color:#333333;">웹툰</span>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	} else {
		for($i=80;$i < 100;$i++){
			$base_url = "https://newtoki".$i.".com/";
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
	}

	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
		$config['newtoki_url'] = $newurl;
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

