<?php
	include('../lib/header.php');
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px"><br>
<input type="hidden" name="user" value="<?= $_GET['user'] ?>">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php if ( $is_adult == "false" ) { ?>
<font size="3" color="red"><b>프로툰 사이트에서는 성인 컨텐츠 차단 설정이 활성화된 경우는 썸네일 이미지가 노출되지 않습니다.</b></font><br>
<?php } ?>
<?php
include('../lib/simple_html_dom.php');

$base_url = $protoon_url;
$page = $_GET["page"];
if ( $page == null ) $page = "1";
if($_GET['keyword'] != null){

	$target = $base_url."search/main?tse_key_=".urlencode($_GET['keyword']);

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 50000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}
	foreach($get_html_contents->find('a.boxs') as $e) {
		$loopcnt++;
		if ( $loopcnt > $list_count ) break;
		$term = "";
		$f = str_get_html($e->innertext);
		$img_link = $e->getAttribute("style");
		$img_link = str_replace("background:url('","",$img_link);
		$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);

		foreach($f->find('div.stit') as $g){
			$subject = trim(strip_tags($g));
		}
		$target_link = $e->href;
		$tgparse = explode('?' , $target_link);
		$idparse = explode('&' , $tgparse[1]);
		$wridparse = explode('=' , $idparse[0]);
		$wr_id = $wridparse[1];
		$genreparse = explode('=' , $idparse[1]);
		$genre = $genreparse[1];

		echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span>";
		if ( $is_adult == "true" ) {
			echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$genre."><img class='rounded-lg' src='".$img_link."' style='float:left; padding:10px;' width='180px'></a>";
		}
		echo "<p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
		echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$genre.">".$subject."(".$wr_id.")</a></font></b><br>";
		echo "[장르:".$genre."]".$down."</p></span></div>";
	}
} else {
	if ( $_GET["end"] != "END" ) {
		$get_html_contents = file_get_html($base_url."toon/mais?typ_=normal");
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."toon/mais?typ_=normal");
			}
		}

		foreach($get_html_contents->find('a.boxs') as $e){
			$loopcnt++;
			if ( $loopcnt > $list_count ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			$img_link = $e->getAttribute("style");
			$img_link = str_replace("background:url('","",$img_link);
			$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);
			
			$idx = 0;
			foreach($f->find('div') as $g){
				if ( $idx == 0 ) {
					$term = trim(strip_tags($g));
				}
				if ( $idx == 1 ) {
					$subject = trim(strip_tags($g));
				}
				$idx++;
			}
			$idx = 0;

			$target_link = $e->href;
			$tgparse = explode('?' , $target_link);
			$idparse = explode('&' , $tgparse[1]);
			$wridparse = explode('=' , $idparse[0]);
			$wr_id = $wridparse[1];
			$typeparse = explode('=' , $idparse[1]);
			$type = $typeparse[1];

			echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span>";
			if ( $is_adult == "true" ) {
				echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."><img class='rounded-lg' src=".$img_link." style='float:left; padding:10px;' width='180px'></a>";
			}
			echo "<p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
			echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type.">".$subject."(".$wr_id.")</a><font color='red'>[".$term."]</font></font></b><br>";
			echo $down."</p></span></div>";
		}
	} else {
		$get_html_contents = file_get_html($base_url."toon/mais?typ_=ends&cpa_=".$page);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 50000){
				break;
			} else {
				$get_html_contents = file_get_html($base_url."toon/mais?typ_=ends&cpa_=".$page);
			}
		}

		foreach($get_html_contents->find('a.boxs') as $e){
			$loopcnt++;
			if ( $loopcnt > $list_count ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			$img_link = $e->getAttribute("style");
			$img_link = str_replace("background:url('","",$img_link);
			$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);
			
			$subject = trim(strip_tags($e));

			$target_link = $e->href;
			$tgparse = explode('?' , $target_link);
			$idparse = explode('&' , $tgparse[1]);
			$wridparse = explode('=' , $idparse[0]);
			$wr_id = $wridparse[1];
			$typeparse = explode('=' , $idparse[1]);
			$type = $typeparse[1];

			echo "<br><div class='card' style='padding:10px 0px 10px 0px;'><span>";
			if ( $is_adult == "true" ) {
				echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."><img class='rounded-lg' src=".$img_link." style='float:left; padding:10px;' width='180px'></a>";
			}
			echo "<p style='height:100px;display:table-cell;vertical-align:middle;'><b><font size=3>";
			echo "<a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type.">".$subject."(".$wr_id.")</a><font color='red'>[완결]</font></font></b><br>";
			echo $down."</p></span></div>";
		}
?>
<center><font size="4"><a href="index.php?end=END&page=1"><?php if ($page=="1") {echo "<font color=red><b>1</b></font>"; } else {echo "1";} ?></a> | <a href="index.php?end=END&page=2"><?php if ($page=="2") {echo "<font color=red><b>2</b></font>"; } else {echo "2";} ?></a> |  <a href="index.php?end=END&page=3"><?php if ($page=="3") {echo "<font color=red><b>3</b></font>"; } else {echo "3";} ?></a> |  <a href="index.php?end=END&page=4"><?php if ($page=="4") {echo "<font color=red><b>4</b></font>"; } else {echo "4";} ?></a> |  <a href="index.php?end=END&page=5"><?php if ($page=="5") {echo "<font color=red><b>5</b></font>"; } else {echo "5";} ?></a> |  <a href="index.php?end=END&page=6"<?php if ($page=="6") {echo "<font color=red><b>6</b></font>"; } else {echo "6";} ?></a> |  <a href="index.php?end=END&page=7"><?php if ($page=="7") {echo "<font color=red><b>7</b></font>"; } else {echo "7";} ?></a> |  <a href="index.php?end=END&page=8"><?php if ($page=="8") {echo "<font color=red><b>8</b></font>"; } else {echo "8";} ?></a> |  <a href="index.php?end=END&page=9"><?php if ($page=="9") {echo "<font color=red><b>9</b></font>"; } else {echo "9";} ?></a> |  <a href="index.php?end=END&page=10"><?php if ($page=="10") {echo "<font color=red><b>10</b></font>"; } else {echo "10";} ?></a> |  <a href="index.php?end=END&page=11"><?php if ($page=="11") {echo "<font color=red><b>11/b></font>"; } else {echo "11";} ?></a> |  <a href="index.php?end=END&page=12"><?php if ($page=="12") {echo "<font color=red><b>12</b></font>"; } else {echo "12";} ?></a> |  <a href="index.php?end=END&page=13"><?php if ($page=="13") {echo "<font color=red><b>13</b></font>"; } else {echo "13";} ?></a> |  <a href="index.php?end=END&page=14"><?php if ($page=="14") {echo "<font color=red><b>14</b></font>"; } else {echo "14";} ?></a></font></center>
<?php
	}
}
?>
</body>
</html>
