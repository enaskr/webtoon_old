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

$base_url = $funbe_url;
$try_count = $config['try_count'];
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
echo "<font size=5><b>".$title."</b></font><br><div><img src='".$thumb."' style='float:left; max-height:154px; margin-right:20px;'><p style='height:154px;display: table-cell;vertical-align: middle;'>".$contents."<br><br>[작가:".$author."] [총편수:".$tooncnt."]</p></div><br>";

$target_episode = array();

foreach($get_html_contents->find('tr.tborder') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('td.content__title') as $g){
		$targeturl = $g->getAttribute("data-role");
		if ( startsWith($targeturl, "/") == true ) $targeturl = substr($targeturl, 1);
		$epititle = trim(strip_tags($g));

		echo "<font size=4><a href='view.php?title=".urlencode($title)."&wr_id=".urlencode($targeturl)."&target=".urlencode($targeturl)."'>".$epititle."</a></font><br>";

	}			
}

?>