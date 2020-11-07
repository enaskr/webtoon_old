<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php if ($isLogin) { ?>
<table style="border-color:#ffffff;" border=0 width="100%" cellspacing=0 cellpadding=0>
	<tr style='background-color:#f8f8f8'>
		<td style='width:34%;height:30px;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./myview.php">내가 본 목록</a></td>
		<td style='width:33%;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./index.php">최신업데이트</a></td>
		<td style='width:33%;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./index.php?end=END">완결작</a></td>
	</tr>
</table>
<?php } ?>
<div id='container'>
	<div class='item'>
		<dl>
<?php
	$base_url = $newtoki_url;
	$siteid = $newtoki_siteid;
	echo "<script type='text/javascript'>console.log('BASE URL=".$base_url."');</script>";

	if($_GET['keyword'] != null){
?>
			<dt>뉴토끼 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$target = $base_url."bbs/search.php?stx=".$_GET['keyword'];
	echo "<script type='text/javascript'>console.log('TARGET URL=".$target."');</script>";

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
				if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
					$img_link = str_replace(substr($img_link, 0,23), $base_url, $img_link);
				}
				if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
					$img_link = str_replace(substr($img_link, 0,22), $base_url, $img_link);
				}
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
			}
			foreach($f->find('div') as $g){
				if ( $g->class == "media-info font-11 text-muted" ) {
					$genre = trim(strip_tags($g));
				}
			}
			if ( $genre == "성인웹툰" ) $adult = true;
			else $adult = false;

			$title_temp = explode("<b>", $f->innertext);
			$title_temp = explode("</a>", $title_temp[1]);
			$subject = str_replace("<b class=\"sch_word\">","",$title_temp[0]);
			$subject = str_replace("</b>","", $subject);
			$wr_id_arr = explode("wr_id=", $target_link);

			$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
			$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id_arr[1]."' ";
			$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["REGDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != true ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr[1]." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr[1].">".$subject."<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
			}
		}
	} else {
		if ( $_GET["end"] != "END" ) {
?>
			<dt>뉴토끼 연재목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
			for($p = 1; $p <= 1; $p++) {
	echo "<script type='text/javascript'>console.log('TARGET URL=".$base_url."webtoon/p".$p.urldecode("?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0")."');</script>";
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
							if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,23), $base_url, $img_link);
							}
							if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,22), $base_url, $img_link);
							}
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

						$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
						$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id_arr."' ";
						$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
						$webtoonView = $webtoonDB->query($isAlreadyView);
						$viewDate = "";
						$alreadyView = "";
						while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
							$viewDate = $row["REGDTIME"];
						}
						if ( strlen($viewDate) > 15 ) {
							$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
						}

						echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr.">".$subject."<br>[<font color=red>".$term."</font>]<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		} else {
?>
			<dt>뉴토끼 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
			for($p = 1; $p <= 4; $p++) {
	echo "<script type='text/javascript'>console.log('TARGET URL=".$base_url."webtoon/p".$p.urldecode("?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0")."');</script>";
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
							if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,23), $base_url, $img_link);
							}
							if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,22), $base_url, $img_link);
							}
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

						$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
						$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id_arr."' ";
						$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
						$webtoonView = $webtoonDB->query($isAlreadyView);
						$viewDate = "";
						$alreadyView = "";
						while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
							$viewDate = $row["REGDTIME"];
						}
						if ( strlen($viewDate) > 15 ) {
							$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
						}

						echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id_arr.">".$subject."<br>[<font color=red>".$term."</font>]<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
					} else {
						break;
					}
					$toonidx++;
				}
			}
		}
	}
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
