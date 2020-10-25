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

$base_url = $copytoon_url;

if($_GET['keyword'] != null){

	$target = $base_url."bbs/search_webtoon.php?stx=".$_GET['keyword'];

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	foreach($get_html_contents->find('div.list-row') as $e){
		$loopcnt++;
		if ( $loopcnt > $list_count ) break;
		$term = "";
		$f = str_get_html($e->innertext);
		foreach($f->find('img.toon-thumb') as $g){
			$img_link = trim($g->src);
		}
		foreach($f->find('h3') as $g){
			$subject = trim(strip_tags($g));
		}
		foreach($f->find('a') as $g){
			$target_link = $g->href;
		}
		$tgparse = explode('/' , $target_link);
		$wr_id = $tgparse[1];

		foreach($f->find('div.toon-before') as $e){
			$term = trim(strip_tags($e));
		}
		foreach($f->find('div.section-item-genre') as $e){
			$genre = trim(strip_tags($e));
		}

		if ( $_GET['user'] == "jackie" ) {
			$down = "<br><br><a href='down.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."&type=".$type."'>다운로드</a>";
		} else $down = "";

		echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'><img class='rounded-lg' src=".$img_link." style='float:left;' width='180px'></a><p style='height:100px;padding-left:20px;display:table-cell;vertical-align:middle;'><b><font size=3>";
		echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'>".$subject."</a><font color=red>[".$term."]</font></font></b><br>";
		echo "[장르:".$genre."]".$down."</p></span></div>";
	}
} else {
	if ( $ends != "END" ) {
		$get_html_contents = file_get_html($base_url."웹툰/작품?sort=new");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url);
			}
		}

		foreach($get_html_contents->find('div.list-row') as $e){
			$loopcnt++;
			if ( $loopcnt > $list_count ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img.toon-thumb') as $g){
				$img_link = trim($g->src);
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
			}
			$tgparse = explode('/' , $target_link);
			$wr_id = $tgparse[1];

			foreach($f->find('div.toon-date') as $e){
				$term = trim(strip_tags($e));
			}
			foreach($f->find('div.section-item-genre') as $e){
				$genre = trim(strip_tags($e));
			}

			if ( $_GET['user'] == "jackie" ) {
				$down = "<br><br><a href='down.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."&type=".$type."'>다운로드</a>";
			} else $down = "";

			echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'><img class='rounded-lg' src=".$img_link." style='float:left;' width='180px'></a><p style='height:100px;padding-left:20px;display:table-cell;vertical-align:middle;'><b><font size=3>";
			echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'>".$subject."</a><font color=red>[".$term."]</font></font></b><br>";
			echo "[장르:".$genre."]".$down."</p></span></div>";
		}
	} else {
		$get_html_contents = file_get_html($base_url."웹툰/작품/완결?sort=latest");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url);
			}
		}

		foreach($get_html_contents->find('div.list-row') as $e){
			$loopcnt++;
			#if ( $loopcnt > $list_count ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img.toon-thumb') as $g){
				$img_link = trim($g->src);
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
			}
			$tgparse = explode('/' , $target_link);
			$wr_id = $tgparse[1];

			foreach($f->find('div.section-item-genre') as $e){
				$genre = trim(strip_tags($e));
			}

			if ( $_GET['user'] == "jackie" ) {
				$down = "<br><br><a href='down.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."&type=".$type."'>다운로드</a>";
			} else $down = "";

			echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'><img class='rounded-lg' src=".$img_link." style='float:left;' width='180px'></a><p style='height:100px;padding-left:20px;display:table-cell;vertical-align:middle;'><b><font size=3>";
			echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($wr_id)."'>".$subject."</a><font color=red>[완결]</font></font></b><br>";
			echo "[장르:".$genre."]".$down."</p></span></div>";
		}
	}
}
?>
</body>
</html>
