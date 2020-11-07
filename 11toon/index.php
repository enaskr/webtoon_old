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
	$base_url = $toon11_url;
	$siteid = $toon11_siteid;
	echo "<script type='text/javascript'>console.log('BASE URL=".$base_url."');</script>";

	if($_GET['keyword'] != null){
?>
			<dt>일일툰 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$target = $base_url."bbs/search_stx.php?stx=".$_GET['keyword'];

		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($target);
			}
		}
		$toonidx = 0;
		foreach($get_html_contents->find('ul#library-recents-list') as $e) {
			if ( $toonidx < $list_count ) {
				$term = "";
				$f = str_get_html($e->innertext);
				$img_link = "";
				$subject = "";
				$wr_id = "";
				$target_link = "";
				$targeturl = "";
				foreach($f->find('li') as $g){
					$h = str_get_html($g->innertext);
					foreach($h->find('div.homelist-thumb') as $i){
						$img_link = $i->style;
						$img_link = str_replace("background-image: url('","",$img_link);
						$img_link = str_replace("');","",$img_link);
					}
					foreach($h->find('div.homelist-title') as $i){
						$subject = trim(strip_tags($i));
					}
					$wr_id = $g->getAttribute("data-id");

					$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
					$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id."' ";
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

					if ( $img_link != null && strlen($img_link) > 0 ) {
						echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
					}
				}
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
			if ( $_GET["end"] != "END" ) {
?>
			<dt>일일툰 연재목록</dt> 
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 1; $p++) {
					$get_html_contents = file_get_html($base_url."bbs/board.php?bo_table=toon_c&tablename=최신만화&type=upd&page=".$p);
					for($html_c = 0; $html_c < $try_count; $html_c++){
						if(strlen($get_html_contents) > 50000){
							break;
						} else {
							$get_html_contents = file_get_html($base_url."bbs/board.php?bo_table=toon_c&tablename=최신만화&type=upd&page=".$p);
						}
					}
					$toonidx = 0;
					foreach($get_html_contents->find('div.homelist-wrap') as $e) {
						if ( $toonidx < $list_count ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('li') as $g){
								$subject = "";
								$h = str_get_html($g->innertext);
								foreach($h->find('div.homelist-thumb') as $i){
									$img_link = $i->style;
									$img_link = str_replace("background-image: url('","",$img_link);
									$img_link = str_replace("');","",$img_link);
								}
								foreach($h->find('a') as $i){
									$target_link = $i->href;
									$targetpos = explode("=",$target_link);
									$wr_id = $targetpos[3];
									break;
								}
								foreach($h->find('div.homelist-title') as $i){
									$subject = trim(strip_tags($i));
								}
								foreach($h->find('div.homelist-genre') as $i){
									$genre = trim(strip_tags($i));
									$genre = substr($genre,0,strlen($genre)-3);
								}
								if ( $subject != null && strlen($subject) > 0 ) {
									$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
									$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id."' ";
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

									echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
									echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
									echo "</tr>\n";

								}
							}
						} else {
							break;
						}
						$toonidx++;
					}
				}
			} else {
?>
			<dt>일일툰 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 7; $p++) {
					$get_html_contents = file_get_html($base_url."bbs/board.php?bo_table=toon_c&is_over=1&tablename=완결만화&page=".$p);
					for($html_c = 0; $html_c < $try_count; $html_c++){
						if(strlen($get_html_contents) > 50000){
							break;
						} else {
							$get_html_contents = file_get_html($base_url."bbs/board.php?bo_table=toon_c&is_over=1&tablename=완결만화&page=".$p);
						}
					}
					$toonidx = 0;
					foreach($get_html_contents->find('div.homelist-wrap') as $e) {
						if ( $toonidx < $list_count ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('li') as $g){
								$h = str_get_html($g->innertext);
								foreach($h->find('div.homelist-thumb') as $i){
									$img_link = $i->style;
									$img_link = str_replace("background-image: url('","",$img_link);
									$img_link = str_replace("');","",$img_link);
								}
								foreach($h->find('a') as $i){
									$target_link = $i->href;
									$targetpos = explode("=",$target_link);
									$wr_id = $targetpos[3];
									break;
								}
								foreach($h->find('div.homelist-title') as $i){
									$subject = trim(strip_tags($i));
								}
								foreach($h->find('div.homelist-genre') as $i){
									$genre = trim(strip_tags($i));
									$genre = substr($genre,0,strlen($genre)-3);
								}

								$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
								$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$wr_id."' ";
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

								echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
								echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
								echo "</tr>\n";

							}
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
