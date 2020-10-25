<?php
	include('../lib/header.php');
	include('../lib/simple_html_dom.php');

$base_url = $spowiki_url;
	$target = $base_url."bbs/board.php?bo_table=webtoon&wr_id=".$_GET['ws_id']."&sca=".$_GET['wr_id'];
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
	foreach($get_html_contents->find('li.next_href') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.view_list_button') as $g){
				$prev_url = $g->href;
				if ( startsWith($prev_url, "http") == false && startsWith($prev_url, "//") == false ) $prev_url = substr($prev_url, 1);
				$prev_pos = explode("=",$prev_url);
				$prev_wspos = explode("&",$prev_pos[2]);
				$prev_epi = $prev_wspos[0];
				$prev_wr_id = $prev_pos[3];
		}
	}
	foreach($get_html_contents->find('li.prev_href') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.view_list_button') as $g){
				$next_url = $g->href;
				if ( startsWith($next_url, "http") == false && startsWith($next_url, "//") == false ) $next_url = substr($next_url, 1);
				$next_pos = explode("=",$next_url);
				$next_wspos = explode("&",$next_pos[2]);
				$next_epi = $next_wspos[0];
				$next_wr_id = $next_pos[3];
		}
	}

	echo "<font size=4><b><a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a></b></font>:::<a href='".$url."'><img src='logo.jpg' height='25px'></a><br>\n";

	if ( $prev_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."'>이전화 보기</a></b> | </font>";
	} else {
		echo "이전화없음 | ";
	}
	if ( $next_url != null ) {
		echo "<font size=3><b><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."'>다음화 보기</a></b></font>";
	} else {
		echo "다음화없음";
	}
	echo " <br> ";

	foreach($get_html_contents->find('div#bo_v_img') as $e){
		echo $e;
		$f = str_get_html($e->innertext);
		foreach($f->find('img') as $g){
			echo $g;
			$get_images = $g->content;
			if ( startsWith($get_images, "http") == false && startsWith($get_images, "//") == false ) $get_images = $base_url.substr($get_images,1);
			echo "<img src='".$get_images."' width='100%'><br>";
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