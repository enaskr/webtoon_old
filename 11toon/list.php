<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px">
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php
$base_url = $toon11_url;
$siteid = $toon11_siteid;
$pagenum = 1;
$pagecnt = 1;

do {
	$target = $base_url."bbs/board.php?bo_table=toons&title=".$_GET["title"]."&is=".$_GET['wr_id']."&page=".$pagenum;
	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = file_get_html($target);
		}
	}

	if ( $pagenum == 1 ) {
		foreach($get_html_contents->find('h2.title') as $e){
			$title = trim(strip_tags($e));
		}
		if ( $title == null || strlen($title)==0 ) {
			$title = $_GET["title"];
		}
		foreach($get_html_contents->find('p.artist') as $e){
			$author = trim(strip_tags($e));;
			if ( $genre == "작가 :" ) {
				$genre = "";
			} else {
				$genre = "[".$genre."]";
			}
		}
		foreach($get_html_contents->find('div.genre') as $e){
			$genre = trim(strip_tags($e));
			if ( $genre == "장르 :" ) {
				$genre = "";
			} else {
				$genre = "[".$genre."]";
			}
		}

		foreach($get_html_contents->find('div.cover-info-wrap') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$thumb = $g->src;
				break;
			}
		}

		foreach($get_html_contents->find('nav.pg_wrap') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$pagecnt = $g->href;
			}
		}
		$pagepos = explode("page=",$pagecnt);
		$pagecnt = $pagepos[1];
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $author.$genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
	}

	$idx = 0;
	$target_episode = array();

	foreach($get_html_contents->find('ul.episode-list') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('li') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('button') as $i){
				$targeturl = $i->onclick;
				$targeturl = str_replace("location.href='./board.php?","",$targeturl);
				$targeturl = str_replace("'","",$targeturl);
				$targetpos1 = explode("&",$targeturl);
				$wr_id = (explode("=",$targetpos1[3]))[1];
				$ws_id = (explode("=",$targetpos1[1]))[1];
				// location.href='./board.php?bo_table=toons&wr_id=292075&stx=&is=24548'
	//			echo $targeturl.", wr_id=".$wr_id.", ws_id=".$ws_id;
			}
			$idx = 0;
			foreach($h->find('div') as $i){
				if ( $idx==5 ) $epititle = trim(strip_tags($i));
				$idx++;
			}
			$epiurl = "/bbs/board.php?bo_table=toons&wr_id=".$ws_id."&stx=&is=".$wr_id;
	//		echo ", epititle=".$epititle.", epiurl=".$epiurl;

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
			echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$ws_id."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
		}
	}
	$pagenum++;
} while  ($pagenum <= $pagecnt);
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>
