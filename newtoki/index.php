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

	$base_url = $newtoki_url;
	$list_count = $config['list_count'];

	if($_GET['keyword'] != null){
		$target = $base_url."bbs/search.php?stx=".$_GET['keyword'];

		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < 3; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($target);
			}
		}
		foreach($get_html_contents->find('div.media') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
			}
			$title_temp = explode("<b>", $f->innertext);
			$title_temp = explode("</a>", $title_temp[1]);
			$subject = str_replace("<b class=\"sch_word\">","",$title_temp[0]);
			$subject = str_replace("</b>","", $subject);
			$wr_id_arr = explode("wr_id=", $target_link);

			echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr[1]."><img class='rounded-lg' src=".$img_link." style='float:left; padding:10px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
			echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr[1].">".str_replace("-"," ",$subject)."(".$wr_id_arr[1].")</a></font></b>".$down."</p></span></div>";
		}
	} else {
		if ( $_GET["end"] != "END" ) {
			for($p = 1; $p <= 1; $p++) {
				$get_html_contents = file_get_html($base_url."webtoon/p".$p."?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0");
				for($html_c = 0; $html_c < 3; $html_c++){
					if(strlen($get_html_contents) > 50000){
						break;
					} else {
						$get_html_contents = file_get_html($base_url."webtoon/p".$p."?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0");
					}
				}
				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < $list_count ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}
						}
						$titleparse = explode('/' , $target_link);
						$title_temp = explode("?", $titleparse[5]);
						$subject = $title_temp[0];
						$wr_id_arr = $titleparse[4];

						echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr."><img class='rounded-lg' src=".$img_link." style='float:left; margin-right:20px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
						echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr.">".str_replace("-"," ",$subject)."(".$wr_id_arr.")</a></font>[<font color=red>".$term."</font>]".$down."</p></span></div>";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		} else {
			for($p = 1; $p <= 4; $p++) {
				$get_html_contents = file_get_html($base_url."webtoon/p".$p."?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0");
				for($html_c = 0; $html_c < 3; $html_c++){
					if(strlen($get_html_contents) > 50000){
						break;
					} else {
						$get_html_contents = file_get_html($base_url."webtoon/p".$p."?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0");
					}
				}
				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < $list_count ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}
						}
						$titleparse = explode('/' , $target_link);
						$title_temp = explode("?", $titleparse[5]);
						$subject = $title_temp[0];
						$wr_id_arr = $titleparse[4];

						echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr."><img class='rounded-lg' src=".$img_link." style='float:left; margin-right:20px;' width='180px'></a><p style='height:100px;display: table-cell;vertical-align: middle;'><b><font size=3>";
						echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr.">".str_replace("-"," ",$subject)."(".$wr_id_arr.")</a></font>[<font color=red>".$term."</font>]".$down."</p></span></div>";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		}
	}
?>
</body>
</html>