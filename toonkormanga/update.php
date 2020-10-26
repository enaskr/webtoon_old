<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../lib/config.php');
	include('../lib/simple_html_dom.php');

//	$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=10";
	$target = $toonkormanga_url;

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	foreach($get_html_contents->find('a.navbar-brand') as $e){
		$newurl = $e->href;
	}
/*
	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
*/
	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
		$config['toonkormanga_url'] = $newurl;
		file_put_contents($server_path.'config.json', json_encode($config,JSON_UNESCAPED_UNICODE));
?>
	<script type="text/javascript">
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	} else {
?>
	<script type="text/javascript">
		alert("주소를 업데이트하지 못하였습니다.");
		history.back();
	</script>
<?php
	}
?>
</body>
</html>

