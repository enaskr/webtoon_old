<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px"><br>
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php
include('../lib/simple_html_dom.php');

$base_url = $protoon_url;
$pagenum = 1;
$pagecnt = 1;
do {
	$target = $base_url."toon/subs?gid_=".$_GET['wr_id']."&typ_=".$_GET['type']."&cpa_=1&cps_=".$pagenum;
	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = file_get_html($target);
		}
	}

	if ( $pagenum == 1 ) {
		foreach($get_html_contents->find('div.titl') as $e){
			if($e != null){
				$title = trim(strip_tags($e));
			} 
		}
		foreach($get_html_contents->find('div.comm') as $e){
			if($e != null){
				$contents = trim(strip_tags($e));
			} 
		}
		foreach($get_html_contents->find('div.ands') as $e){
			if($e != null){
				$genreinfo = explode('<span>&nbsp;|&nbsp;</span>' , $e);
				$genre = trim(strip_tags($genreinfo[0]));
				$term = trim(strip_tags($genreinfo[1]));
			} 
		}
		foreach($get_html_contents->find('div.gpos') as $e){
			if($e->style != null){
				$thumb = $e->style;
				$thumb = str_replace("background:url('","",$thumb);
				$thumb = str_replace("') no-repeat center center; background-size:cover;","",$thumb);
			} 
		}
		foreach($get_html_contents->find('div.page') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a.pags') as $g){
				$pagelink[] = $g->href;
			}
		}
		$pagecnt = count($pagelink) - 4;
		echo "<font size=5><b>".$title."</b></font><br><div><img src='".$thumb."' style='float:left; max-height:154px; margin-right:20px;'><p style='height:154px;display: table-cell;vertical-align: middle;'>".$contents."<br><br>[장르:".$genre."] [".$term."]</p></div><br>";
	}

	foreach($get_html_contents->find('div.body') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.boxs') as $g){
			$targeturl = $g->href;
			$urlparse = explode('?' , $targeturl);
			$uriparse = explode('&' , $urlparse[1]);
			$epiparse = explode('=' , $uriparse[0]);
			$t = str_get_html($g->innertext);
			$chasu = "";
			foreach($t->find('div') as $h){
				if ( $h->class == "econ ecen" && strlen($chasu) == 0) {
					$chasu = trim(strip_tags($h));
				}
				if ( $h->class == "econ elef" ) {
					$epititle = trim(strip_tags($h));
					if ( endsWith($epititle, "(0)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(1)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(2)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(3)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(4)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(5)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(6)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(7)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(8)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(9)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
				}
			}
			echo "<font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$epiparse[1]."&wr_id=".$_GET['wr_id']."&type=".$_GET['type']."&target=".urlencode($targeturl)."'>".$chasu."::".$epititle."</a></font><br>";
		}			
	}
	$pagenum++;
} while ($pagenum <= $pagecnt);
?>