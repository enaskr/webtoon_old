<?php
	include('../lib/header.php');
?>
<?php
	$base_url = $toon11_url;
	$target_episode = $base_url."bbs/board.php?bo_table=toons&wr_id=".$_GET["ws_id"]."&stx=&is=".$_GET["wr_id"];
	$title = urldecode($_GET['title']);
	$siteid = $toon11_siteid;
	$toonid = $_GET["wr_id"];
	$toonurl = "/bbs/board.php?bo_table=toons&title=".$_GET["title"]."&is=".$_GET['wr_id'];
	$epiurl = "/bbs/board.php?bo_table=toons&wr_id=".$_GET["ws_id"]."&stx=&is=".$_GET["wr_id"];
	$get_images = array();

	$url = $target_episode; //주소셋팅
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36');
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($result) > 50000){
			break;
		} else {
			$result = curl_exec($ch);
		}
	}
	curl_close ($ch); // curl 종료
	$html_arr = explode("<title>", $result);
	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('span') as $e){
		if ( $e->class == "viewer-header__title" ) {
			$epititle = trim(strip_tags($e));
		}
	}
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo "<a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a> <a href='".$target_episode."'><img src='logo.png' height='25px'></a>"; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php

	$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
	$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$toonid."' AND EPIID = '".$epiurl."' ";
	$isAlreadyView = $isAlreadyView." LIMIT 1;";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$viewDate = $row["REGDTIME"];
	}
	if ( strlen($viewDate) == 0 ) {
		$sql_view = "INSERT INTO 'USER_VIEW_TOON' ('USERID', 'TOONSITEID', 'TOONID', 'TOONTITLE', 'TOONURL', 'EPIID', 'EPITITLE', 'EPIURL', 'REGDTIME')";
		$sql_view = $sql_view." VALUES ('".$userID."','".$siteid."','".$toonid."','".$title."','".$toonurl."','".$epiurl."','".$epititle."', '".$this_url."','".$thisTime."'); ";
		$webtoonDB->exec($sql_view);
	} else {
		$sql_view = "UPDATE 'USER_VIEW_TOON' SET REGDTIME = '".$thisTime."' ";
		$sql_view = $sql_view."WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$toonid."' AND EPIID = '".$epiurl."';";
		$webtoonDB->exec($sql_view);
	}

	foreach($get_html_contents->find('ul.inline-list') as $e){
		$f = str_get_html($e->innertext);
		$idx=0;
		foreach($f->find('button') as $g){
			if ( $idx==0 ) {
				if ( $g->onclick != null ) {
					$prevurl = $g->onclick;
					$prevurl = str_replace("location.href='./board.php?bo_table=toons&amp;wr_id=","",$prevurl);
					$prevpos = explode("&",$prevurl);
					$prevwsis = $prevpos[0];
				}
			}
			if ( $idx==1 ) {
				if ( $g->onclick != null ) {
					$nexturl = $g->onclick;
					$nexturl = str_replace("location.href='./board.php?bo_table=toons&amp;wr_id=","",$nexturl);
					$nextpos = explode("&",$nexturl);
					$nextwsis = $nextpos[0];
				}
			}
			$idx++;
		}
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevwsis != null && strlen($prevwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevwsis."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nextwsis != null && strlen($nextwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nextwsis."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevwsis != null && strlen($prevwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevwsis."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nextwsis != null && strlen($nextwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nextwsis."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
<?php

	$selector_arr = explode("var img_list = [", $result);
	$html_arr = explode("];", $selector_arr[1]);
	$img_html = $html_arr[0];
	$img_html = str_replace('"','',$img_html);

	$imgurl = explode(",",$img_html);
	foreach($imgurl as $images){
		if ( substr($images, 0,16) == "https://manatoki" ) {
			$images = str_replace(substr($images, 0,16).substr($images, 16,2).".net/", $base_url, $images);
		}
		if ( substr($images, 0,15) == "https://newtoki" ) {
			$images = str_replace(substr($images, 0,15).substr($images, 15,2).".com/", $base_url, $images);
		}
		echo "<img src='".$images."' width='100%'><br>";
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevwsis != null && strlen($prevwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevwsis."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nextwsis != null && strlen($nextwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nextwsis."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prevwsis != null && strlen($prevwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prevwsis."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $nextwsis != null && strlen($nextwsis) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$nextwsis."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</div>
</body>
</html>
