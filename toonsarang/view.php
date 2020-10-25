<?php
	include('../lib/header.php');
	include('../lib/simple_html_dom.php');

	$base_url = $toonsarang_url;
	$target = $base_url.$_GET['ws_id'];
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

	foreach($get_html_contents->find('div.text-center') as $e){
		$idx = 0;
		$f = str_get_html($e->innertext);
		foreach($f->find('a') as $g){
			if ( $idx == 0 ) {
				$prev_url = $g->href;
				$prevpos = explode("/", $prev_url);
				if ( strlen($prev_url) > 1 && startsWith($prev_url, "http") == false ) { 
					$prev_url = $base_url.$prev_url;
					$prev_epi = $prevpos[1];
				} elseif (strlen($next_url) > 1) {
					$prev_epi = $prevpos[3];
				} else {
					$prev_url = null;
				}
			}
			if ( $idx == 2 ) {
				$next_url = $g->href;
				$nextpos = explode("/", $next_url);
				if ( strlen($next_url) > 1 && startsWith($next_url, "http") == false ) { 
					$next_url = $base_url.$next_url;
					$next_epi = $nextpos[1];
				} elseif (strlen($next_url) > 1) {
					$next_epi = $nextpos[3];
				} else {
					$next_url = null;
				}
			}
			$idx++;
		}
		break;
	}

	echo "<font size=4><b><a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a></b></font> <a href='".$url."'><img src='logo.png' height='25px'></a><br>\n";

	if ( $prev_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".urlencode($_GET['wr_id'])."&ws_id=".urlencode($prev_epi)."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if ( $next_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".urlencode($_GET['wr_id'])."&ws_id=".urlencode($next_epi)."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
	echo " <br> ";

	foreach($get_html_contents->find('div#bo_v_con') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('img') as $g){
			if($g->src != null){
				$get_images = $g->src;
				echo "<img src='".$get_images."' width='100%'><br>";
			}
		}
	}

	echo "<br>".$conts."<br>";

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