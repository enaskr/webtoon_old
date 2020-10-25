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

$base_url = $funbe_url;
$list_count = $config['list_count'];
$try_count = $config['try_count'];

if($_GET['keyword'] != null){
	$target = $base_url."bbs/search.php?sfl=wr_subject||wr_content&stx=".$_GET['keyword'];

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = file_get_html($target);
		}
	}

		foreach($get_html_contents->find('div.section-item') as $e){
			$loopcnt++;
			if ( $loopcnt > $list_count ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
			}
			foreach($f->find('div.section-item-addon') as $e){
				$term = trim(strip_tags($e));
			}
			$termpos = explode(' ' , $term);
			$term = $termpos[0];
			foreach($f->find('div.toon_gen') as $e){
				$genre = trim(strip_tags($e));
			}
			foreach($f->find('div.toon-adult') as $e){
				$adult = "(".trim(strip_tags($e)).")";
			}

			if ( $is_adult == "true" || $adult != "(19)" ) {
				echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."><img class='rounded-lg' src='".$img_link."' style='float:left; padding:10px;' width='180px'></a><p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
				echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)."'>".$subject.$adult."</a><font color=red>[".$term."]</font></font></b><br>";
				echo $down."</p></span></div>";
			}
		}
} else {
	if ( $ends != "END" ) {
		$get_html_contents = file_get_html($base_url."무료웹툰?fil=최신");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."무료웹툰?fil=최신");
			}
		}

		foreach($get_html_contents->find('div.section-item') as $e){
			$loopcnt++;
			if ( $loopcnt > $list_count ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
			}
			foreach($f->find('div.section-item-addon') as $e){
				$term = trim(strip_tags($e));
			}
			$termpos = explode(' ' , $term);
			$term = $termpos[0];
			foreach($f->find('div.toon_gen') as $e){
				$genre = trim(strip_tags($e));
			}
			foreach($f->find('div.toon-adult') as $e){
				$adult = "(".trim(strip_tags($e)).")";
			}

			if ( $is_adult == "true" || $adult != "(19)" ) {
				echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."><img class='rounded-lg' src='".$img_link."' style='float:left; padding:10px;' width='180px'></a><p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
				echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)."'>".$subject.$adult."</a><font color=red>[".$term."]</font></font></b><br>";
				echo "[장르:".$genre."]".$down."</p></span></div>";
			}
		}
	} else {
		$get_html_contents = file_get_html($base_url."웹툰/완결?fil=최신");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."웹툰/완결?fil=최신");
			}
		}

		foreach($get_html_contents->find('div.section-item') as $e){
			$loopcnt++;
			#if ( $loopcnt > $list_count ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
			}
			foreach($f->find('div.section-item-addon') as $e){
				$term = trim(strip_tags($e));
			}
			$termpos = explode(' ' , $term);
			$term = $termpos[0];
			foreach($f->find('div.toon_gen') as $e){
				$genre = trim(strip_tags($e));
			}
			foreach($f->find('div.toon-adult') as $e){
				$adult = "(".trim(strip_tags($e)).")";
			}

			if ( $is_adult == "true" || $adult != "(19)" ) {
				echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."><img class='rounded-lg' src='".$img_link."' style='float:left; padding:10px;' width='180px'></a><p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
				echo "<a href='list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)."'>".$subject.$adult."</a><font color=red>[".$term."]</font></font></b><br>";
				echo "[장르:".$genre."]".$down."</p></span></div>";
			}
		}
	}
}
?>
</body>
</html>
