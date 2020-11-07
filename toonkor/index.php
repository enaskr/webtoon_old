<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px">
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
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
$base_url = $toonkor_url;
$siteid = $toonkor_siteid;
	echo "<script type='text/javascript'>console.log('BASE URL=".$base_url."');</script>";

if($_GET['keyword'] != null){
?>
			<dt>툰코 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
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

			$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
			$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$target_link."' ";
			$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["REGDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<br><span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != "(19)" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$term."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
			}
		}
} else {
	if ( $ends != "END" ) {
?>
			<dt>툰코 연재목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$get_html_contents = file_get_html($base_url."무료웹툰?fil=최신");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."무료웹툰?fil=최신");
			}
		}

		foreach($get_html_contents->find('div.section-item') as $e){
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

			$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
			$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$target_link."' ";
			$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["REGDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<br><span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != "(19)" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
			}
		}
	} else {
?>
			<dt>툰코 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$get_html_contents = file_get_html($base_url."웹툰/완결?fil=최신");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."웹툰/완결?fil=최신");
			}
		}

		foreach($get_html_contents->find('div.section-item') as $e){
			//if ( $loopcnt > $list_count ) break;
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

			$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
			$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$target_link."' ";
			$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["REGDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<br><span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != "(19)" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
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
