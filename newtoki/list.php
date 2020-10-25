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

$base_url = $newtoki_url;
$target = $base_url."bbs/board.php?bo_table=webtoon&wr_id=".$_GET['wr_id'];

$get_html_contents = file_get_html($target);
for($html_c = 0; $html_c < 3; $html_c++){
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
foreach($get_html_contents->find('div.view-img') as $e){
	$thumb = $e->innertext;
	$thumb = str_replace(">"," style='float:left; max-height:154px; margin-right:20px;'>", $thumb);
}

echo "<font size=5><b>".$title."</b></font><br><div>".$thumb."<p style='height:154px;display: table-cell;vertical-align: middle;'>".$contents."</p></div><br>";

$target_episode = array();

foreach($get_html_contents->find('li.list-item') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('a.item-subject') as $g){
		$targeturl = str_replace("?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0&spage=1","",$g->href);
		$targeturl = str_replace("?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0&spage=1","",$targeturl);
		$epiparse = explode('/' , $targeturl);
		echo "<font size=4><a href='view.php?title=".$title."&wr_id=".$epiparse[4]."&wr_id=".$_GET['wr_id']."&target=".$targeturl."'>".str_replace("-"," ",$epiparse[5])."</a></font><br>";
	}			
}

?>