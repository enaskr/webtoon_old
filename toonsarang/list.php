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

$base_url = $toonsarang_url;
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
foreach($get_html_contents->find('div.toon-info-wrap') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('img') as $g){
		$thumb = $g->src;
		if ( startsWith($thumb, "http") == false && startsWith($thumb, "//") == false ) $thumb = $base_url.$thumb;
	}
}
foreach($get_html_contents->find('h2.title') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('span') as $g){
		$tooncnt = trim(strip_tags($g));
		$tooncnt = str_replace("(","",$tooncnt);
		$tooncnt = str_replace(")","",$tooncnt);
		$tooncnt = trim($tooncnt);
	}
}
foreach($get_html_contents->find('div.synop') as $e){
	$contents = trim(strip_tags($e));
}
foreach($get_html_contents->find('div.genre') as $e){
	$genre = trim(strip_tags($e));
}
echo "<font size=5><b>".$title."</b></font><br><div><img src='".$thumb."' style='float:left; height:154px; margin-right:20px;'><p style='height:154px;display: table-cell;vertical-align: middle;'>".$contents."<br><br>[장르:".$genre."] [총편수:".$tooncnt."]</p></div><br>";

$target_episode = array();
foreach($get_html_contents->find('div.contents-list') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('a') as $g){
		$h = str_get_html($g->innertext);
		$targeturl = $g->href;
		if ( startsWith($targeturl, "/") == true ) $targeturl = substr($targeturl, 1);
		foreach($h->find('span.s_episode') as $i) {
			$chasu = trim(strip_tags($i));
		}
		foreach($h->find('div') as $i) {
			$epititle = trim(strip_tags($i));
			break;
		}
		echo "<font size=4><a href='view.php?title=".urlencode($title)."&wr_id=".urlencode($_GET['wr_id'])."&ws_id=".urlencode($targeturl)."'>".$epititle."</a></font><br>";
	}			
}
?>