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
	$base_url = $manatoki_url;
	$siteid = $manatoki_siteid;
	echo "<script type='text/javascript'>console.log('BASE URL=".$base_url."');</script>";

	if($_GET['keyword'] != null){
?>
			<dt>마나토끼 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$target = $base_url."comic/p1?bo_table=comic&stx=".$_GET['keyword'];

		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($target);
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
					$targeturl = str_replace("?stx=".$_GET['keyword'],"",$target_link);
					$targeturl = str_replace("?stx=".urlencode($_GET['keyword']),"",$targeturl);
					$targeturl = str_replace("&page=1","",$targeturl);
					break;
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

					if($e->class == "in-lable trans-bg-black"){
						$subject = trim(strip_tags($e));
					}
					if($e->class == "list-artist trans-bg-yellow en"){
						$author = trim(strip_tags($e));
					}
					if($e->class == "list-publish trans-bg-blue en"){
						$publish = trim(strip_tags($e));
					}

				}
				$titleparse = explode('/' , $targeturl);
				$wr_id = $titleparse[4];

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
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[작가:".$author."][발행주기:<font color=red>".$publish."</font>]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
			if ( $_GET["end"] != "END" ) {
?>
			<dt>마나토끼 신규목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 1; $p++) {
					$get_html_contents = file_get_html($base_url."bbs/page.php?hid=update&page=".$p);
					for($html_c = 0; $html_c < $try_count; $html_c++){
						if(strlen($get_html_contents) > 50000){
							break;
						} else {
							$get_html_contents = file_get_html($base_url."bbs/page.php?hid=update&page=".$p);
						}
					}
					$toonidx = 0;
					foreach($get_html_contents->find('div.post-row') as $e) {
						if ( $toonidx < $list_count ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('img') as $g){
								$img_link = $g->src;
							}
							$idx = 0;
							foreach($f->find('a') as $g){
								if ( $idx == 0 ) $epilink = $g->href;
								if ( $idx == 1 ) $target_link = $g->href;
								$idx++;
							}
							$idx = 0;
							foreach($f->find('div.post-subject') as $g){
								$title = trim(strip_tags($g));
								$titlepos = explode("&nbsp;",$title);
								$title = trim($titlepos[0]);
							}
							foreach($f->find('div') as $g){
								if($g->class == "post-text post-ko txt-short ellipsis"){
									$content = trim(strip_tags($g));
									$content = "[작가: ".$content;
									$content = str_replace("&nbsp;","] [장르: ",$content);
									$content = $content."]";
								}
							}
							$epiparse = explode('/' , $epilink);
							$ws_id = $epiparse[4];
							$titleparse = explode('/' , $target_link);
							$wr_id = $titleparse[4];

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

							echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
							echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br><font size=2>".$content."</font><br>".$alreadyView."</a>\n";
							echo "</tr>\n";
						} else {
							break;
						}
						$toonidx++;
					}
				}
			} else {
?>
			<dt>마나토끼 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 7; $p++) {
					$get_html_contents = file_get_html($base_url."comic/p".$p."?publish=%EC%99%84%EA%B2%B0");
					for($html_c = 0; $html_c < $try_count; $html_c++){
						if(strlen($get_html_contents) > 50000){
							break;
						} else {
							$get_html_contents = file_get_html($base_url."comic/p".$p."?publish=%EC%99%84%EA%B2%B0");
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
								$targeturl = str_replace("?page=1&publish=%EC%99%84%EA%B2%B0","",$target_link);
								$targeturl = str_replace("?page=1&publish=완결","",$targeturl);
								$targeturl = str_replace("?page=1","",$targeturl);
								break;
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

								if($e->class == "in-lable trans-bg-black"){
									$subject = trim(strip_tags($e));
								}
								if($e->class == "list-artist trans-bg-yellow en"){
									$author = trim(strip_tags($e));
								}
								$publish = "완결";
							}
							$titleparse = explode('/' , $targeturl);
							$wr_id = $titleparse[4];

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

							echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
							echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br><font size=2>[".$author."]</font><br>".$alreadyView."</a>\n";
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
