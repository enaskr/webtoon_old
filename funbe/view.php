<?php
	include('../lib/header.php');
	include('../lib/simple_html_dom.php');

	$base_url = $funbe_url;
	$try_count = $config['try_count'];
	$target = $base_url.$_GET['wr_id'];
	$title = urldecode($_GET['title']);

	$get_images = array();

	$url = $target; //주소셋팅
	$get_html_contents = file_get_html($url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = file_get_html($ch);
		}
	}

	foreach($get_html_contents->find('h1') as $e){
		if($e != null){
			$epititle = trim(strip_tags($e));
			break;
		} 
	}
	foreach($get_html_contents->find('div.view-wrap') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('div[class="btn-resource btn-prev at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$prev_url = $i->href;
				$prevpos = explode("/", $prev_url);
				if ( startsWith($prev_url, "http") == false ) { 
					$prev_url = $base_url.$prev_url;
					$prev_epi = $prevpos[1];
				} else {
					$prev_epi = $prevpos[3];
				}
			}
		}
		foreach($f->find('div[class="btn-resource btn-next at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$next_url = $i->href;
				$nextpos = explode("/", $next_url);
				if ( startsWith($next_url, "http") == false ) { 
					$next_url = $base_url.$next_url;
					$next_epi = $nextpos[1];
				} else {
					$next_epi = $nextpos[3];
				}
			}
		}
	}

	echo "<font size=4><b><a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a></b></font>:::<a href='".$url."'><img src='logo.png' height='25px'></a><br>\n";

	if ( $prev_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$prev_epi."&type=".$_GET['type']."&target=".urlencode($prev_url)."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if ( $next_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$next_epi."&type=".$_GET['type']."&target=".urlencode($next_url)."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
	echo " <br> ";

	$conts_arr = explode("var toon_img = ", $get_html_contents);
	$conts = trim($conts_arr[1]);
	$conts = str_replace("';","",$conts);
	$conts = str_replace("'","",$conts);
	$conts = base64_decode($conts);
	$conts = str_replace(' src="',' width="100%" src="',$conts);

	$f = str_get_html($conts);
	foreach($f->find('img') as $e){
		$get_images = $e->src;
		if ( startsWith($get_images, "http") == false ) $get_images = $base_url.$get_images;
		echo "<img src='".$get_images."' width='100%'><br>";
	}

	if ( $prev_epi[1] != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi[1]."&type=".$_GET['type']."&target=".urlencode($prev_url)."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if ( $next_epi[1] != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi[1]."&type=".$_GET['type']."&target=".urlencode($next_url)."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
?>