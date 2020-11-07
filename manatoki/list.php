<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px">
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php
$base_url = $manatoki_url;
$target = $base_url."bbs/board.php?bo_table=comic&wr_id=".$_GET['wr_id'];
$siteid = $manatoki_siteid;

$get_html_contents = file_get_html($target);
for($html_c = 0; $html_c < $try_count; $html_c++){
	if(strlen($get_html_contents) > 50000){
		break;
	} else {
		$get_html_contents = file_get_html($target);
	}
}

foreach($get_html_contents->find('meta') as $e){
	if($e->name == "subject"){
		$title = $e->content;
	} 
}
foreach($get_html_contents->find('span') as $e){
	if($e->style == "background-color:#ffffff;color:#222222;font-family:Consolas, 'Lucida Console', 'Courier New', monospace;white-space:pre-wrap;" ) {
		$contents = trim(strip_tags($e));;
		break;
	}
}
foreach($get_html_contents->find('div.view-content1') as $e){
	foreach($e->find('img') as $g){
		$thumb = $g->src;
	}
}
$idx = 0;
foreach($get_html_contents->find('div') as $e){
	if($e->class == "view-content"){
		if ( $idx == 2 ) $author = trim(strip_tags($e));
		if ( $idx == 3 ) $publish = trim(strip_tags($e));
		$idx++;
	}
	if($e->class == "view-content tags"){
		$genre = trim(strip_tags($e));
	}
}
$author = trim(str_replace("•","",$author));
$genre = trim(str_replace("•","",$genre));
$publish = trim(str_replace("•","",$publish));
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $author; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $publish; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('li.list-item') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('a.item-subject') as $g){
		$targeturl = str_replace("?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0&spage=1","",$g->href);
		$targeturl = str_replace("?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0&spage=1","",$targeturl);
		$targeturl = str_replace("?spage=1","",$targeturl);
		$epiparse = explode('/' , $targeturl);
		$epititle = strip_tags($g);
		$epititle = str_replace("화 1 ","화",$epititle);
		$epititle = str_replace("   1  ","",$epititle);
		$epiurl = str_replace($base_url,"/",$base_url."comic/".$epiparse[4]);

		$isAlreadyView = "SELECT USERID, TOONSITEID, TOONID, EPIURL, REGDTIME FROM USER_VIEW_TOON ";
		$isAlreadyView = $isAlreadyView." WHERE USERID = '".$userID."' AND TOONSITEID = '".$siteid."' AND TOONID = '".$_GET['wr_id']."' AND EPIID='".$epiurl."' ";
		$isAlreadyView = $isAlreadyView." ORDER BY REGDTIME DESC LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		$viewDate = "";
		$alreadyView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["REGDTIME"];
			$dbepiurl = $row["EPIURL"];
		}
		if ( strlen($viewDate) > 15 ) {
			$alreadyView = "<a href='../lib/remove_view.php?siteid=".$siteid."&toonid=".$_GET['wr_id']."&epiurl=".urlencode($dbepiurl)."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
		}
		echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".$title."&ws_id=".$epiparse[4]."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
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
