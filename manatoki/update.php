<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../lib/common.php');
	$target = $manatoki_url."notice/7754";

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
		$strpos3 = explode('<font color="#333333" style="color:#333333;">&nbsp;일본만화</font>',$strpos2[1]); 
		$newstr = $strpos3[0];
		$manatokistr = str_get_html($newstr);
		foreach($manatokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
	if ( strlen($get_html_contents) == 0 || $newurl == null || strlen($newurl) == 0 ) {
		for($i=85;$i < 300;$i++){
			$base_url = "https://manatoki".$i.".net/";
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
		$webtoonDB->exec("UPDATE 'TOON_CONFIG' SET CONF_VALUE = '".$newurl."', REGDTIME = '".$thisTime."' WHERE CONF_NAME = 'manatoki_url';");
?>
	<script type="text/javascript">
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	} else {
?>
	<script type="text/javascript">
		alert("주소 업데이트에 실패했습니다.");
		history.back();
	</script>
<?php
	}
?>
</body>
</html>

