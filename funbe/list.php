<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px">
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php
$base_url = $funbe_url;
$siteid = $funbe_siteid;
$target = $base_url.$_GET['wr_id'];
$get_html_contents = file_get_html($target);
for($html_c = 0; $html_c < $try_count; $html_c++){
	if(strlen($get_html_contents) > 50000){
		break;
	} else {
		$get_html_contents = file_get_html($target);
	}
}

foreach($get_html_contents->find('meta') as $e){
	if($e->name == "title"){
		$title = $e->content;
	} 
}
foreach($get_html_contents->find('td.bt_thumb') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('img') as $g){
		$thumb = $g->src;
		if ( startsWith($thumb, "http") == false ) $thumb = $base_url.$thumb;
	}
}
foreach($get_html_contents->find('td.bt_over') as $e){
	$contents = trim(strip_tags($e));
}
foreach($get_html_contents->find('td.bt_label') as $e){
	$idx = 1;
	$f = str_get_html($e->innertext);
	foreach($f->find('span.bt_data') as $g){
		if ( $idx == 1 ) {
			$author = trim(strip_tags($g));
		} elseif ( $idx == 2 ) {
			$tooncnt = trim(strip_tags($g));
		}
		$idx++;
	}
}
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo $title; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "<img src='".$thumb."' style='width:100%'>"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[작가:".$author."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[총편수:".$tooncnt."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('tr.tborder') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('td.content__title') as $g){
		$targeturl = $g->getAttribute("data-role");
		if ( startsWith($targeturl, "/") == true ) $targeturl = substr($targeturl, 1);
		$epititle = trim(strip_tags($g));

		$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
		$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$_GET['wr_id']."' AND EPIID='/".$targeturl."' ";
		$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		$viewDate = "";
		$alreadyView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["REGDTIME"];
			$dbepiurl = $row["EPIURL"];
		}
		if ( strlen($viewDate) > 15 ) {
			$alreadyView = "<a href='../lib/remove_view.php?siteid=".$siteid."&toonid=".urlencode($_GET['wr_id'])."&epiurl=".urlencode($dbepiurl)."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
		}
		echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".urlencode($targeturl)."&wr_id=".urlencode($_GET['wr_id'])."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
	}			
}

?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>
