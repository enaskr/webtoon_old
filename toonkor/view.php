<?php
	include('../lib/header.php');
	$base_url = $toonkor_url;
	$target = $base_url.$_GET['ws_id'];
	$title = urldecode($_GET['title']);
	$siteid = $toonkor_siteid;

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
	foreach($get_html_contents->find('div.view-wrap') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('div[class="btn-resource btn-prev at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$prev_url = $i->href;
				$prevpos = explode("/", $prev_url);
				if ( startsWith($prev_url, "http") == false ) { 
					$prev_url = $base_url.$prev_url;
					$prev_epi = $prevpos[1];
				} else {
					$prev_epi = $prevpos[3];
				}
			}
		}
		foreach($f->find('div[class="btn-resource btn-next at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$next_url = $i->href;
				$nextpos = explode("/", $next_url);
				if ( startsWith($next_url, "http") == false ) { 
					$next_url = $base_url.$next_url;
					$next_epi = $nextpos[1];
				} else {
					$next_epi = $nextpos[3];
				}
			}
		}
	}
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo "<a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a> <a href='".$url."'><img src='logo.png' height='25px'></a>"; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prev_epi)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($next_epi)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prev_epi)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($next_epi)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
<?php

	if ( $isLogin ) {
		$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
		$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$_GET['wr_id']."' AND EPIID = '"."/".$_GET['ws_id']."' ";
		$isAlreadyView = $isAlreadyView." LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["REGDTIME"];
		}
		if ( strlen($viewDate) == 0 ) {
			$sql_view = "INSERT INTO 'USER_VIEW_TOON' ('USERID', 'TOONSITEID', 'TOONID', 'TOONTITLE', 'TOONURL', 'EPIID', 'EPITITLE', 'EPIURL', 'REGDTIME')";
			$sql_view = $sql_view." VALUES ('".$userID."','".$siteid."','".$_GET['wr_id']."','".$title."','"."/".$_GET['wr_id']."','"."/".$_GET['ws_id']."','".$epititle."', '".$this_url."','".$thisTime."'); ";
			$webtoonDB->exec($sql_view);
		} else {
			$sql_view = "UPDATE 'USER_VIEW_TOON' SET REGDTIME = '".$thisTime."' ";
			$sql_view = $sql_view."WHERE USERID = '".$userID."' AND TOONSITEID = '/".$siteid."' AND TOONID = '".$_GET['wr_id']."' AND EPIID = '/".$_GET['ws_id']."';";
			$webtoonDB->exec($sql_view);
		}
	}

	$conts_arr = explode("var toon_img = ", $get_html_contents);
	$conts = trim($conts_arr[1]);
	$conts = str_replace("';","",$conts);
	$conts = str_replace("'","",$conts);
	$conts = base64_decode($conts);
	$conts = str_replace(' src="',' width="100%" src="',$conts);

	$f = str_get_html($conts);
	foreach($f->find('img') as $e){
		$get_images = $e->src;
		if ( startsWith($get_images, "http") == false ) $get_images = $base_url.$get_images;
		echo "<img src='".$get_images."' width='100%'><br>";
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prev_epi)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($next_epi)."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($prev_epi)."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".urlencode($next_epi)."'>▶</a>";
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
