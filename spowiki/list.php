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

$base_url = $spowiki_url;
$target = $base_url."bbs/board.php?bo_table=webtoon&sca=".$_GET['wr_id'];
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
foreach($get_html_contents->find('div.thumb') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('img') as $g){
		$thumb = $g->src;
		if ( startsWith($thumb, "http") == false ) $thumb = $base_url.$thumb;
	}
}
foreach($get_html_contents->find('div.overview') as $e){
	$contents = trim(strip_tags($e));
}
foreach($get_html_contents->find('dl.summ') as $e){
	$g = str_get_html($e->innertext);
	$idx=0;
	$term = "";
	$tooncnt = "";
	foreach($g->find('dd') as $h){
		if ( $idx == 0 ) $author = trim(strip_tags($h));
		if ( $idx == 1 ) $tooncnt = trim(strip_tags($h));
		$idx++;
	}
}

echo "<font size=5><b>".$title."</b></font><br><div><img src='".$thumb."' style='float:left; max-height:154px; margin-right:20px;'><p style='height:154px;display: table-cell;vertical-align: middle;'>".$contents."<br><br>[작가:".$author."] [총편수:".$tooncnt."]</p></div><br>";

$target_episode = array();

foreach($get_html_contents->find('table.bt-table') as $e){
	$f = str_get_html($e->innertext);
	$idx=0;
	foreach($f->find('tr') as $g){
		$h = str_get_html($g->innertext);
		if ( $idx>0 ) {
			foreach($h->find('a') as $i){
				$targeturl = $i->href;
				if ( startsWith($targeturl, "http") == false && startsWith($targeturl, "//") == false ) $targeturl = substr($targeturl, 1);
				$epititle = trim(strip_tags($i));
				$targetpos = explode("=",$targeturl);
				$wspos = explode("&",$targetpos[2]);
				$ws_id = $wspos[0];
				$wr_id = $targetpos[3];
				echo "<font size=4><a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$ws_id."'>".$epititle."</a></font><br>";
			}
		}
		$idx++;
	}
}
?>