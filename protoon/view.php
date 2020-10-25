<?php
include('../lib/header.php');
include('../lib/simple_html_dom.php');
	$base_url = $protoon_url;
	$target_episode = "toon/vies?idx_=".$_GET['ws_id']."gid_=".$_GET['wr_id']."&typ_=".$_GET['type']."&cpa_=1&cps_=1";
	$title = urldecode($_GET['title']);

	$get_images = array();

	$url = $base_url.$target_episode; //주소셋팅
	$get_html_contents = file_get_html($url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = file_get_html($ch);
		}
	}

	foreach($get_html_contents->find('div.etit') as $e){
		if($e != null){
			$epititle = trim(strip_tags($e));
			break;
		} 
	}
	foreach($get_html_contents->find('a.pbtn') as $e){
		if($e->href != null){
			$prev_url = $e->href;
			$urlparse = explode('?' , $prev_url);
			$uriparse = explode('&' , $urlparse[1]);
			$prev_epi = explode('=' , $uriparse[0]);
		}
	}
	foreach($get_html_contents->find('a.nbtn') as $e){
		if($e->href != null){
			$next_url = $e->href;
			$urlparse = explode('?' , $next_url);
			$uriparse = explode('&' , $urlparse[1]);
			$next_epi = explode('=' , $uriparse[0]);
		}
	}

	echo "<font size=4><b><a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a></b></font> <a href='".$url."'><img src='logo.png' height='25px'></a><br>\n";

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
	echo " <br> ";

	foreach($get_html_contents->find('div.eimg') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('img') as $g){
			if($g->getAttribute("data-src") != null){
				$get_images = $g->getAttribute("data-src");
				echo "<img src='".$get_images."' width='100%'><br>";
			}
		}
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